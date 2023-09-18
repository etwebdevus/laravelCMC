@extends('layouts.front.app')
@php
    $pagecontents = json_decode(base64_decode($pagecontent));
    
@endphp
@section('title', $pagetranslation->title)
@if ($pagetranslation->meta_description != '')
    @section('meta-data')
        {{-- <meta name="keywords" content="{{ $pagetranslation->meta_keywords }}"> --}}
        <meta name="description" content="{{ $pagetranslation->meta_description }}">
    @endsection
@endif
@section('styles')
    @php
        if ($layout != '') {
            $css_path = asset("themes/$layout") . '/assets/css/';
            if ($css_handle = opendir(public_path("themes/$layout") . '/assets/css/')) {
                while (false !== ($entry = readdir($css_handle))) {
                    if ($entry != '.' && $entry != '..') {
                        echo '<link href="' . $css_path . $entry . '" rel="stylesheet" />';
                    }
                }
                closedir($css_handle);
            }
        }
    @endphp
    @if ($pagecontents != '')
        <style>
            {!! $pagecontents->css !!}
        </style>
    @endif
@endsection
@section('content')
    @if ($pagecontents != '')
        {!! $pagecontents->body !!}
    @endif
@endsection
@section('scripts')

    @php
        if ($layout != '') {
            $js_path = getTemplateUrl("themes/$layout") . '/assets/js/';
            if ($js_handle = opendir(public_path("themes/$layout") . '/assets/js/')) {
                while (false !== ($entry = readdir($js_handle))) {
                    if ($entry != '.' && $entry != '..') {
                        echo '<script src="'.$js_path . $entry.'"></script>';
                    }
                }
                closedir($js_handle);
            }
        }
    @endphp
    @if ($pagecontents != '')
        <script>
            {!! $pagecontents->js !!}
        </script>
    @endif
@endsection
