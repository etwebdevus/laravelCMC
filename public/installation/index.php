<?php
$actualLink = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$basePath = parse_url($actualLink, PHP_URL_PATH);
$pageName = pathinfo(basename($basePath), PATHINFO_EXTENSION) ? basename($basePath) : "";
$baseUrl = str_replace($pageName, '', $actualLink);
if (isset($_POST["install"])) {
    $domain = $_POST["domain"];
    $host = $_POST["host"];
    $database = $_POST["database"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $zip = new ZipArchive();
    if ($zip->open('source-code.zip') === true) {
        // Modify the contents of the file as needed
        $new_contents = '
       APP_NAME=Laravel
       APP_TITLE = "CMS VO.4"
       APP_ENV=local
       APP_KEY=base64:pwd/Ox0JL+/igIxoFjP+Zx6PvtTxe/ANeMGYS6HL4Rc=
       APP_DEBUG=true
       APP_URL=' . $domain . '
       LOG_CHANNEL=stack
       LOG_DEPRECATIONS_CHANNEL=null
       LOG_LEVEL=debug
       DB_CONNECTION=mysql
       DB_HOST=' . $host . '
       DB_PORT=3306
       DB_DATABASE=' . $database . '
       DB_USERNAME=' . $username . '
       DB_PASSWORD=' . $password . '
       BROADCAST_DRIVER=log
       CACHE_DRIVER=file
       FILESYSTEM_DRIVER=local
       QUEUE_CONNECTION=sync
       SESSION_DRIVER=file
       SESSION_LIFETIME=120
       MEMCACHED_HOST=127.0.0.1
       REDIS_HOST=127.0.0.1
       REDIS_PASSWORD=null
       REDIS_PORT=6379
       MAIL_MAILER=smtp
       MAIL_HOST=mailhog
       MAIL_PORT=1025
       MAIL_USERNAME=null
       MAIL_PASSWORD=null
       MAIL_ENCRYPTION=null
       MAIL_FROM_ADDRESS=null
       MAIL_FROM_NAME="${APP_NAME}"
       AWS_ACCESS_KEY_ID=
       AWS_SECRET_ACCESS_KEY=
       AWS_DEFAULT_REGION=us-east-1
       AWS_BUCKET=
       AWS_USE_PATH_STYLE_ENDPOINT=false
       PUSHER_APP_ID=
       PUSHER_APP_KEY=
       PUSHER_APP_SECRET=
       PUSHER_APP_CLUSTER=mt1
       MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
       MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
       DB_MYSQLDUMP_PATH=G:\xampp\mysql\bin
       ';
        // $zip->deleteName($file_to_modify);
        $index1 = $zip->locateName("database.sql", ZipArchive::FL_NOCASE);
        if ($index1 !== false) {
            $zip->extractTo("./", "database.sql");
        }
        // Create a PDO connection to the database
        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
            $connection = new PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        $tables = $connection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $connection->query("DROP TABLE IF EXISTS $table");
        }
        // Read the backup file
        $backup_file = "database.sql";
        if (file_exists($backup_file)) {
            $sql = file_get_contents($backup_file);
            $statements = explode(';', $sql);
            // Execute the SQL statements
            foreach ($statements as $statement) {
                if (trim($statement) != '') {
                    $result = $connection->exec($statement);
                }
            }
        }
        $extractPath = getcwd();
        $parentDirectory = dirname(__DIR__); // Get the parent directory path
        $folderName = 'laravel'; // Specify the name of the folder you want to create
        $folderPath = $parentDirectory . '/' . $folderName; // Construct the complete path for the new folder
        if (!file_exists($folderPath)) {
            if (mkdir($folderPath, 0755)) {
                // echo "Folder '$folderName' created successfully in the parent directory.";
            }
        }
        $zip->extractTo($folderPath);
        $zip->close();

        unlink("database.sql");
        unlink("source-code.zip");
        $sourceFolder =  $folderPath . '/public';
        $files = scandir($sourceFolder);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $source = $sourceFolder . '/' . $file;
                $destination = $extractPath . '/' . $file;
                if (is_dir($source)) {
                    // Copy sub-directory recursively
                    // echo "Is Dir $source \n <br>";
                    copyDirectory($source, $destination);
                } else {
                    // Copy file
                    if (strpos($source, "index.php") == false && strpos($source, "index-1.php") == false) {
                        // echo "Is File $source  \n <br>";
                        copy($source, $destination);
                    }
                }
            }
        }
        file_put_contents("$folderPath/.env", $new_contents);
        $index_new_contents = file_get_contents($sourceFolder . "/index-1.php");
        file_put_contents("index.php", $index_new_contents);
        // unlink("index.php");
        echo "<script>window.open('/','_self');</script>";
    } else {
        echo "Failed to open the ZIP archive.";
    }
}
function copyDirectory($source, $destination)
{
    if (!file_exists($destination)) {
        mkdir($destination, 0777, true);
    }
    $files = scandir($source);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $sourceFile = $source . '/' . $file;
            $destinationFile = $destination . '/' . $file;
            if (is_dir($sourceFile)) {
                // Copy sub-directory recursively
                copyDirectory($sourceFile, $destinationFile);
            } else {
                // Copy file
                copy($sourceFile, $destinationFile);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Description" content="Enter your description here" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .main-row {
            height: 100vh;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
        }
    </style>
    <title>CMS VO.4</title>
</head>

<body class="bg-dark">
    <div class="container">
        <div class="row main-row">
            <div class="col-md-6 mx-auto my-auto">
                <div class="card">
                    <div class="card-header text-center bg-white">
                        <h4>Installation Setup</h4>
                    </div>
                    <div class="card-body">
                        <form action="index.php" method="post">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Website Url:</label>
                                    <input type="url" readonly value="<?= $baseUrl ?>" required name="domain" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Host Name:</label>
                                    <input type="text" readonly value="localhost" required name="host" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Database Name:</label>
                                    <input type="text" required name="database" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Database Username:</label>
                                    <input type="text" required name="username" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Database Password:</label>
                                    <input type="text" required name="password" class="form-control">
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-outline-dark" name="install">Installation</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>

</html>