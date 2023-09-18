@extends('layouts.admin.app')
@section('title', 'Settings')
@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <h2 class="mb-3">Settings</h2>
                    <a href="{{ route('setting.code-website') }}" class="btn btn-primary btn-sm">Download Website Zip</a>
                    <a href="{{ route('setting.clear-website') }}" class="btn btn-primary btn-sm">Clear the site</a>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success_message'))
                        <div class="alert alert-success mb-3">
                            {{ session('success_message') }}
                        </div>
                    @endif
                    <form action="{{ route('setting.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12  mb-3">
                                <div class="from-group">
                                    <label for="">Website Language</label>
                                    @php
                                        $language_select_array = explode(',', $selected_language);
                                    @endphp
                                    <select class="form-control settings-language" name="languages[]" id=""
                                        multiple>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->id }}"
                                                {{ in_array($language->id, $language_select_array) ? 'selected' : '' }}>
                                                {{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6  mb-3">
                                <div class="from-group">
                                    <label for="">Default 404 Page</label>
                                    <select class="form-control settings-page" name="page" id="">
                                        <option value="0" {{ $selected_page == 0 ? 'selected' : '' }}>Select Page
                                        </option>
                                        @foreach ($pages as $page)
                                            <option value="{{ $page->id }}"
                                                {{ $selected_page == $page->id ? 'selected' : '' }}>{{ $page->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6  mb-3">
                                <div class="from-group">
                                    <label for="">Select Extension</label>
                                    <select class="form-control settings-language" name="extension" id="">
                                        <option value="" {{ $selected_page == "" ? 'selected' : '' }}>Select Extension
                                        </option>
                                        <option value=".html"
                                            {{ $selected_page_extension == '.html' ? 'selected="true"' : '' }}>.html</option>
                                        <option value=".htm"
                                            {{ $selected_page_extension == '.htm' ? 'selected="true"' : '' }}>.htm</option>
                                        <option value=".php"
                                            {{ $selected_page_extension == '.php' ? 'selected="true"' : '' }}>.php</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6  mb-3">
                                <div class="from-group">
                                    <label for="">Taille Title</label>
                                    <input type="number" min="0" name="title_max" id="title_max"
                                        value="{{ $selected_title_max }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6  mb-3">
                                <div class="from-group">
                                    <label for="">Taille Méta Description</label>
                                    <input type="number" min="0" name="meta_desc_max"
                                        value="{{ $selected_meta_max }}" id="meta_desc_max" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="from-group">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(".settings-language").select2({
            placeholder: "Select Language",
            allowClear: true
        });
        $(".settings-page").select2({
            placeholder: "Select Page",
            allowClear: true
        });
    </script>
@endsection
