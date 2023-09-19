<?php

namespace App\Http\Controllers;

use App\Models\GrapeJsImages;
use App\Models\Languages;
use App\Models\Layouts;
use App\Models\Page;
use App\Models\PageContents;
use App\Models\Menu;
use App\Models\PagesTranslations;
use App\Models\Settings;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\PushoverHandler;
use Illuminate\Support\Facades\Artisan;

class MenuController extends Controller
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
    public function index()
    {
        $allmenus = Menu::orderBy('id', 'asc')->get();
        return view('admin.menu.index', compact('allmenus'));
    }

    public function status($id)
    {
        Menu::where('id', $id)->update([
            'status' => '1'
        ]);
        return redirect()->route("menu.all");
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.menu.create');
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
            'menu_title' => 'required',
            'menu_type' => 'required', 
            'number_item' => 'required'
        ]);
        Menu::create([
            'title' => $request->menu_title,
            'type' => $request->menu_type,
            'number_item' => $request->number_item,
            'status' => '0'
        ]);
        
        return redirect()->route('menu.all')->with('success_message', 'Page Add Successfully!');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::find($id);
        return view('admin.menu.edit', compact('menu'));
    }

    public function delete($id)
    {
        Menu::where('id', $id)->first()->delete();
        return redirect()->route("menu.all")->with('success_message', 'Page Delete Successfully!');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_title' => 'required',
            'menu_type' => 'required', 
            'number_item' => 'required'
        ]);
       
        Menu::where('id', $id)->update([
            'title' => $request->menu_title,
            'type' => $request->menu_type,
            'number_item' => $request->number_item,
        ]);
        return redirect()->route('menu.all')->with('success_message', 'Page Update Successfully!');
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
   
}
