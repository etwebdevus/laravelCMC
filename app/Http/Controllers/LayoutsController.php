<?php

namespace App\Http\Controllers;

use App\Models\Layouts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class LayoutsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layouts = Layouts::all();
        return  view('admin.layouts.index', compact('layouts'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.layouts.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zip = new ZipArchive();
        $status = $zip->open($request->file("zip")->getRealPath());
        if ($status !== true) {
            throw new \Exception($status);
        } else {
            $storageDestinationPath = public_path("themes/$request->link/");
            if (!File::exists($storageDestinationPath)) {
                File::makeDirectory($storageDestinationPath, 0755, true);
            }
            $zip->extractTo($storageDestinationPath);
            $zip->close();
        }
        Layouts::create([
            'title' => $request->title,
            'link' => $request->link
        ]);
        return redirect()->route('layout.all')->with('success_message', 'Layout Add Successfully!');
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
    public function destroy($id)
    {
        $layout_link = public_path("themes/").Layouts::where('id', $id)->pluck('link')->first();

        if (is_dir($layout_link)) {
            $files = array_diff(scandir($layout_link), ['.', '..']);
         
            foreach ($files as $file) {
                (is_dir("$layout_link/$file")) ? File::deleteDirectory("$layout_link/$file") : unlink("$layout_link/$file");
            }
            rmdir($layout_link);
        }
        Layouts::destroy($id);

        return redirect()->route('layout.all')->with('success_message', 'Layout Delete Successfully!');
    }
}
