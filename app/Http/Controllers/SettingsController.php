<?php
namespace App\Http\Controllers;
use App\Models\Languages;
use App\Models\Pages;
use App\Models\PagesTranslations;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use PDO;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;
class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $languages = Languages::all();
        $selected_language = Settings::where('setting', 'language')->pluck("value")->first();
        $selected_page = Settings::where('setting', 'page')->pluck("value")->first();
        $selected_title_max = Settings::where('setting', 'title_max')->pluck("value")->first();
        $selected_meta_max = Settings::where('setting', 'meta_max')->pluck("value")->first();
        $selected_page_extension = Settings::where('setting', 'page_extension')->pluck("value")->first();
        $pages = Pages::select("pages.*")
            ->join("pages_translations", "pages_translations.page_id", '=', 'pages.id')
            ->where("pages_translations.status", 1)
            ->where("pages_translations.locale", 1)
            ->get();
        return view('admin.settings.update', compact('languages', 'selected_language', 'selected_page', 'pages', 'selected_title_max', 'selected_meta_max', 'selected_page_extension'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $language = implode(",", $request->languages);
        Settings::where('setting', 'language')->update([
            'value' => $language
        ]);
        Settings::where('setting', 'page')->update([
            'value' => $request->page
        ]);
        Settings::where('setting', 'title_max')->update([
            'value' => $request->title_max
        ]);
        Settings::where('setting', 'meta_max')->update([
            'value' => $request->meta_desc_max
        ]);
        Settings::where('setting', 'page_extension')->update([
            'value' => $request->extension
        ]);
        Artisan::call('route:clear');
        return redirect()->route('setting.update')->with('success_message', 'Settings Update Successfully!');
    }
    public function source($filename)
    {
        // Define the name of your zip file.
        $zipFileName = $filename . '.zip';
        // Define the path where your zip file will be created.
        $zipFilePath = public_path('installation/' . $zipFileName);
        // Define the path to the directory you want to zip.
        $directoryPath = base_path();
        
        // Create a new ZipArchive instance.
        $zip = new ZipArchive();
        // Open the zip file for writing.
        $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        // Add the files from the directory to the zip file.
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directoryPath));
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            if ($fileName !== '.' && $fileName !== '..') {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($directoryPath) + 1);
                    $excludeFolders = ['.git'];
                    $exclude = false;
                    foreach ($excludeFolders as $excludeFolder) {
                        if (strpos($relativePath, $excludeFolder) === 0) {
                            $exclude = true;
                            break;
                        }
                    }
                    if (!$exclude) {
                        $zip->addFile($filePath, $relativePath);
                    }
                } else {
                    $relativePath = substr($file, strlen($directoryPath) + 1);
                    $excludeFolders = ['.git'];
                    $exclude = false;
                    foreach ($excludeFolders as $excludeFolder) {
                        if (strpos($relativePath, $excludeFolder) === 0) {
                            $exclude = true;
                            break;
                        }
                    }
                    if (!$exclude) {
                        $zip->addEmptyDir($relativePath);
                    }
                }
            }
        }
        $dbHost = env('DB_HOST', 'localhost');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');
        // Create a PDO connection to the database
        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
            $connection = new PDO($dsn, $dbUser, $dbPassword, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        $tables = array();
        $result = $connection->query("SHOW TABLES");
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        
        $return = '';
        foreach ($tables as $table) {
            $result = $connection->query("SELECT * FROM " . $table);
            $num_fields = $result->columnCount();
            $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = $connection->query("SHOW CREATE TABLE " . $table)->fetch(PDO::FETCH_NUM);
            $return .= "\n\n" . $row2[1] . ";\n\n";
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $return .= "INSERT INTO " . $table . " VALUES(";
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    if (isset($row[$j])) {
                        $return .= '"' . $row[$j] . '"';
                    } else {
                        $return .= '""';
                    }
                    if ($j < $num_fields - 1) {
                        $return .= ',';
                    }
                }
                $return .= ");\n";
            }
            $return .= "\n\n\n";
        }
        // Save the backup file
        $handle = fopen(base_path("backup.sql"), "w+");
        fwrite($handle, $return);
        fclose($handle);
        
        // Add the database file to the zip file.
        $zip->addFile(base_path('backup.sql'), 'database.sql');
        if ($zip->locateName('backup.sql') !== false) {
            $zip->deleteName('backup.sql');
        }
        // Close the zip file.
        $zip->close();
        unlink(base_path('backup.sql'));

        // Return the zip file for download.
    }
    public function download_code()
    {
        $this->source("source-code");
        // Create a new ZipArchive instance.
        $zip = new ZipArchive();
        // Define the path where your zip file will be created.
        $zipFilePath = storage_path('app/' . "cms-v04.zip");
        // Open the zip file for writing.
        $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        // Add the files from the directory to the zip file.
        
        $files = array(
            public_path('installation/source-code.zip'),
            public_path('installation/index.php')
        );
        $directoryPath = public_path('installation/');
        foreach ($files as $file) {
            $relativePath = substr($file, strlen($directoryPath));
            $zip->addFile($file, $relativePath);
        }
        $zip->close();
        unlink(public_path('installation/source-code.zip'));
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
    public function clear_website()
    {
        $dbHost = env('DB_HOST', 'localhost');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');
        // Create a PDO connection to the database
        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
            $connection = new PDO($dsn, $dbUser, $dbPassword, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        $tables = array('pages', 'pages_translations', 'page_contents');
        $return = '';
        foreach ($tables as $table) {
            $result = $connection->query("TRUNCATE TABLE " . $table);
        }
        return redirect()->route('setting.update')->with('success_message', 'Clear the site Successfully!');
    }
}
