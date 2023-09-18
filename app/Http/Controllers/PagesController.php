<?php

namespace App\Http\Controllers;

use App\Models\GrapeJsImages;
use App\Models\Languages;
use App\Models\Layouts;
use App\Models\Page;
use App\Models\PageContents;
use App\Models\Pages;
use App\Models\PagesTranslations;
use App\Models\Settings;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\PushoverHandler;
use Illuminate\Support\Facades\Artisan;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        $selected_page_extension = Settings::where('setting', 'page_extension')->pluck("value")->first();
        $pages = Pages::all();
        if ($id != null) {
            $allpages = PagesTranslations::where('page_id', $id)->groupBy('connect_same')->orderBy('id', 'asc')->get();
        } else {
            $allpages = PagesTranslations::groupBy('connect_same')->orderBy('id', 'asc')->get();
        }
        return view('admin.pages.index', compact('pages', 'allpages', 'id','selected_page_extension'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title_max = Settings::where('setting', 'title_max')->pluck("value")->first();
        $meta_max = Settings::where('setting', 'meta_max')->pluck("value")->first();
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        $languages = Languages::whereIn('id', $setting_language)->get();
        $layouts = Layouts::all();
        return view('admin.pages.create', compact('languages', 'layouts', 'title_max', 'meta_max'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_title' => 'required',
            'page_layout' => 'required'
        ]);
        Pages::create([
            'title' => $request->page_title,
            'layout' => $request->page_layout
        ]);
        $page_id = Pages::latest('id')->first()->id;
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        $languages = Languages::whereIn('id', $setting_language)->get();
        $page_same = rand(99999, 10000);
        PageContents::create([
            'page_id' => $page_id,
            'page_translation_connect' => $page_same,
            'data' => ($request->page_layout == 0) ? "" : ""
        ]);
        foreach ($languages as $language) {
            PagesTranslations::create([
                'page_id' => $page_id,
                'locale' => $language->id,
                'title' => $request->input("title_" . $language->id),
                'link' => $request->input("link_" . $language->id),
                'meta_description' => $request->input("meta_description_" . $language->id),
                'notes' => $request->input("notes_" . $language->id),
                "connect_same" => $page_same,
                'status' => 1,
                'cloned' => 0
            ]);
        }
        return redirect()->route('page.active')->with('success_message', 'Page Add Successfully!');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $connect_same)
    {
        $page = Pages::find($id);
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        $title_max = Settings::where('setting', 'title_max')->pluck("value")->first();
        $meta_max = Settings::where('setting', 'meta_max')->pluck("value")->first();
        $page_translations = array();
        $languages = Languages::whereIn('id', $setting_language)->get();
        foreach ($languages as $language) {
            $page_translation = PagesTranslations::where('page_id', $id)
                ->where('connect_same', $connect_same)
                ->where('locale', $language->id)
                ->first();
            array_push($page_translations, array("language_name" => $language->name, "page_translate" => ($page_translation)  ? $page_translation->toArray() : array("id" => 0, "page_id" => $id, "locale" => $language->id, "title" => "", "link" => "", "meta_keywords" => "", "meta_description" =>  " ", "notes" => "", "connect_same" => $connect_same, "status" => 1)));
        }
        $layouts = Layouts::all();
        return view('admin.pages.edit', compact('page', 'connect_same', 'page_translations', 'layouts', 'title_max', 'meta_max'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $connect_same)
    {
        $request->validate([
            'page_title' => 'required',
            'page_layout' => 'required'
        ]);
        Pages::where('id', $id)->update([
            'title' => $request->page_title,
            'layout' => $request->page_layout
        ]);
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        $page_translations = array();
        $languages = Languages::whereIn('id', $setting_language)->get();
        foreach ($languages as $language) {
            $page_translation = PagesTranslations::where('page_id', $id)
                ->where('connect_same', $connect_same)
                ->where('locale', $language->id)
                ->first();
            array_push($page_translations, array("language_name" => $language->name, "page_translate" => ($page_translation)  ? $page_translation->toArray() : array("id" => 0, "page_id" => $id, "locale" => $language->id, "title" => "", "link" => "", "meta_keywords" => "", "meta_description" =>  " ", "notes" => "", "connect_same" => $connect_same, "status" => 1)));
        }
        foreach ($page_translations as $page_translation) {
            if ($page_translation["page_translate"]["id"] == 0) {
                PagesTranslations::create([
                    'page_id' => $page_translation["page_translate"]["page_id"],
                    'locale' => $page_translation["page_translate"]["locale"],
                    'title' => $request->input("title_" . $page_translation["page_translate"]["id"]),
                    'link' => $request->input("link_" . $page_translation["page_translate"]["id"]),
                    'meta_description' => $request->input("meta_description_" . $page_translation["page_translate"]["id"]),
                    'notes' => $request->input("notes_" . $page_translation["page_translate"]["id"]),
                    "connect_same" => $page_translation["page_translate"]["connect_same"],
                    'status' => $page_translation["page_translate"]["status"],
                    'cloned' => 0
                ]);
            } else {
                PagesTranslations::where('id', $page_translation["page_translate"]["id"])->update([
                    'title' => $request->input("title_" . $page_translation["page_translate"]["id"]),
                    'link' => $request->input("link_" . $page_translation["page_translate"]["id"]),
                    'meta_description' => $request->input("meta_description_" . $page_translation["page_translate"]["id"]),
                    'notes' => $request->input("notes_" . $page_translation["page_translate"]["id"]),
                ]);
            }
        }
        return redirect()->route('page.active')->with('success_message', 'Page Update Successfully!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pages::destroy($id);
        $PageContents = PageContents::where('page_id', $id)->get();
        foreach ($PageContents as $PageContent) {
            PageContents::find($PageContent->id)->delete();
        }
        $pages = PagesTranslations::where('page_id', $id)->get();
        foreach ($pages as $page) {
            PagesTranslations::destroy($page->id);
        }
        return redirect()->route("page.active")->with('success_message', 'All Pages Delete Successfully!');
    }
    public function deleted($id, $connect_same)
    {
        $count = count(PagesTranslations::where('page_id', $id)->groupBy('connect_same')->get());
        $pages = PagesTranslations::where('page_id', $id)
            ->where('connect_same', $connect_same)
            ->get();
        foreach ($pages as $page) {
            PagesTranslations::destroy($page->id);
        }
        PageContents::where('page_id', $id)->where('page_translation_connect', $connect_same)->first()->delete();
        return redirect()->route("page.active")->with('success_message', 'Page Delete Successfully!');
    }
    public function status($id, $connect_same)
    {
        $page_translations = PagesTranslations::where('page_id', $id)->get();
        foreach ($page_translations as $page_translation) {
            PagesTranslations::where('id', $page_translation->id)->update([
                'status' => ($connect_same == $page_translation->connect_same) ? 1 : 0
            ]);
        }
        return redirect()->route("page.inner", $id)->with('success_message', 'Page Active Successfully!');
    }
    public function active_page()
    {   
        $selected_page_extension = Settings::where('setting', 'page_extension')->pluck("value")->first();
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        if (count($setting_language) > 1) {
            $allpages = PagesTranslations::where('status', 1)->groupBy('page_id')->orderBy("id", "asc")->get();
            return view('admin.pages.active-page', compact('allpages','selected_page_extension'));
        } else {
            $allpages = PagesTranslations::where('status', 1)->whereIn('locale', $setting_language)->get();
            return view('admin.pages.active-page', compact('allpages','selected_page_extension'));
        }
    }
    public function seo_page()
    {
        $title_max = Settings::where('setting', 'title_max')->pluck("value")->first();
        $meta_max = Settings::where('setting', 'meta_max')->pluck("value")->first();
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        if (count($setting_language) > 1) {
            $allpages = PagesTranslations::where('status', 1)->groupBy('page_id')->orderBy("id", "asc")->get();
            return view('admin.pages.seo-page', compact('allpages', 'title_max', 'meta_max'));
        } else {
            $allpages = PagesTranslations::where('status', 1)->whereIn('locale', $setting_language)->get();
            return view('admin.pages.seo-page', compact('allpages', 'title_max', 'meta_max'));
        }
    }
    public function seo_store(Request $request)
    {
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        if (count($setting_language) > 1) {
            $allpages = PagesTranslations::where('status', 1)->groupBy('page_id')->orderBy("id", "asc")->get();
            foreach ($allpages  as $allpage) {
                PagesTranslations::where("id", $allpage->id)->update([
                    'title' => $request->input("title_" . $allpage->id),
                    'meta_description' => $request->input("meta_description_" . $allpage->id),
                ]);
            }
        } else {
            $allpages = PagesTranslations::where('status', 1)->whereIn('locale', $setting_language)->get();
            foreach ($allpages  as $allpage) {
                PagesTranslations::where("id", $allpage->id)->update([
                    'title' => $request->input("title_" . $allpage->id),
                    'meta_description' => $request->input("meta_description_" . $allpage->id),
                ]);
            }
        }
        return redirect()->route("page.seo")->with('success_message', 'SEO Update Successfully!');
    }
    public function edit_grapejs($id, $connect_same)
    {
        
        $selected_page_extension = Settings::where('setting', 'page_extension')->pluck("value")->first();
        $selected_page = Settings::where('setting', 'page')->pluck("value")->first();
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());

        $page = PagesTranslations::where("connect_same", $connect_same)
            ->where('page_id', $id)
            ->whereIn('locale', $setting_language)
            ->groupBy('connect_same')
            ->orderBy('id', 'asc')
            ->first();
        $layout = Layouts::where('id', $page->page->layout)->pluck('link')->first();
        $page_link = ("index" != $page->link) ? "$page->link" : "my-main-home";
        $page_content = PageContents::where('page_id', $id)->where('page_translation_connect', $connect_same)->pluck('data')->first();
        // dd($id, $connect_same);
        return view('admin.pages.edit-grapejs', compact('page_link', 'id', 'connect_same', 'page', 'layout', 'page_content'));
    }
    public function clone($id, $connect_same)
    {
        $setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
        $page_translations = PagesTranslations::where('page_id', $id)
            ->whereIn('locale', $setting_language)
            ->where('connect_same', $connect_same)
            ->get();
        $page_content = PageContents::where('page_id', $id)->where('page_translation_connect', $connect_same)->first();
        $page_same = rand(99999, 10000);
        PageContents::create([
            'page_id' => $id,
            'page_translation_connect' => $page_same,
            'data' => $page_content->data
        ]);
        foreach ($page_translations as $page_translation) {
            PagesTranslations::create([
                'page_id' => $page_translation->page_id,
                'locale' => $page_translation->locale,
                'title' => $page_translation->title,
                'link' => $page_translation->link,
                'meta_keywords' => $page_translation->meta_keywords,
                'meta_description' => $page_translation->meta_description,
                'notes' => $page_translation->notes,
                "connect_same" => $page_same,
                'status' => 0,
                'cloned' => $connect_same
            ]);
        }
        return redirect()->route("page.inner", $id)->with('success_message', 'Page Clone Successfully!');
    }
    public function saveDesign(Request $request, $id, $connect_same)
    {
        // Get the design data from the request
        $designData = $request->data;
        // Save the design data to the database
        $contentid = PageContents::where('page_id', $id)->where('page_translation_connect', $connect_same)->pluck('id')->first();
        PageContents::where('id', $contentid)->update([
            'data' => base64_encode(json_encode($designData))
        ]);
        return response()->json([
            'message' => 'Design saved successfully',
        ]);
    }
    public function upload_image(Request $request)
    {
        // Get the uploaded files from the request
        $files = $request->file('images');
        $path = getcwd().'/uploads/';
        // Store the files in the database
        foreach ($files as $file) {
            $filename =  preg_replace("/[^.a-zA-Z0-9]/m", "-", $file->getClientOriginalName());
            $filename = str_replace(" ", "", $filename);
            // $path = public_path() . '/uploads/';
            $width = getimagesize($file)[0]; // getting the image width
            $height = getimagesize($file)[1];
            $file->move($path, $filename);
            // $file->storeAs('uploads', $filename, 'public_html');
            // Store the file information in the database
            GrapeJsImages::create([
                'image_name' => $filename,
                'image_width' => $width,
                'image_height' => $height
            ]);
        }
        // Return a success response
        // return response()->json(["src" => env("APP_URL") . "/uploads/" . $filename, "width" => $width, "height" => $height]);
        return response()->json(["src" => env("APP_URL") . "/uploads/" . $filename, "width" => $width, "height" => $height]);
    }
    public function remove_image(Request $request)
    {
        $path = getcwd().'/uploads/';
        $name = $request->post('name');
        if (\File::exists($path.$name)) {
            \File::delete($path.$name);
        }
        return response()->json([
            'message' => 'Remove saved successfully',
        ]);

    }
    public function fetch_image()
    {
        // $allimages = GrapeJsImages::all();

        $path = getcwd().'/uploads/';
        $allimages = \File::allFiles($path);
        $data = array();
        foreach ($allimages as $allimage) {
            list($width, $height) = getimagesize($path.$allimage->getFilename());
            array_push($data, array("src" => env("APP_URL") . "/uploads/" . $allimage->getFilename(), "width" => $width, "height" => $height));
        }
        return response()->json($data);
    }
}
