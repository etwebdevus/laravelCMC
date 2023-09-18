<?php
namespace App\Http\Controllers;
use App\Models\Layouts;
use App\Models\PageContents;
use App\Models\PagesTranslations;
use App\Models\Settings;
use Illuminate\Http\Request;
class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selected_page_extension = Settings::where('setting', 'page_extension')->pluck("value")->first();
        // $pagename = explode(".",basename($_SERVER['PHP_SELF']))[0];
        $pagename = explode(".",basename($_SERVER['REQUEST_URI']))[0];
        // $pagename = basename($_SERVER['SCRIPT_URI'], ".php");
        $selected_page = Settings::where('setting', 'page')->pluck("value")->first();
        // if (($pagename == "index" || $pagename == $_SERVER['SERVER_NAME'] ) && $selected_page != 0) {
        //     $pagetranslation = PagesTranslations::where("link", $selected_page)->where('status', 1)->first();
        // }else{
        // }
        $pagetranslation = PagesTranslations::where("link", ($_SERVER['REQUEST_URI'] == "/" || $_SERVER['REQUEST_URI'] == "/index.html")?"index":$pagename)->where('status', 1)->first();
        $layout = Layouts::where('id', $pagetranslation->page->layout)->pluck('link')->first();
        $pagecontent = PageContents::where('page_id', $pagetranslation->page_id)->where("page_translation_connect", $pagetranslation->connect_same)->pluck('data')->first();
        return view("front.index", compact("pagetranslation", "pagecontent", "layout"));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactive_index($id,$connect_same)
    {
        $pagetranslation = PagesTranslations::where('page_id',$id)->where('connect_same',$connect_same)->first();
        $layout = Layouts::where('id', $pagetranslation->page->layout)->pluck('link')->first();
        $pagecontent = PageContents::where('page_id', $pagetranslation->page_id)->where("page_translation_connect", $connect_same)->pluck('data')->first();
        return view("front.index", compact("pagetranslation", "pagecontent", "layout"));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function page_notfound()
    {
        return view('front.page-not-found');
    }
}
