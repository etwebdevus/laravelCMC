@extends('layouts.admin.app')
@section('title', 'Add New Page')
@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <h2 class="mb-3">Add New Page</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('page.store') }}" method="post">
                        @csrf
                        @php
                            $title_less_than = ($title_max / 100) * 90;
                            $title_more_than = ($title_max / 100) * 110;
                            $description_less_than = ($meta_max / 100) * 90;
                            $description_more_than = ($meta_max / 100) * 110;
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Page Over View Title</label>
                                    <input type="text" class="form-control" name="page_title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Layout</label>
                                    <select class="form-control settings-page" name="page_layout" id="">
                                        <option value="" selected disabled>Select Layout</option>
                                        <option value="0">Empty Layout</option>
                                        @foreach ($layouts as $layout)
                                            <option value="{{ $layout->id }}">{{ $layout->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @foreach ($languages as $language)
                                <div class="col-md-12">
                                    <h4>{{ $language->name }}</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="from-group">
                                                <label
                                                    for="">{{ $language->name != 'English' ? _('Première') : _('Title') }}:
                                                    <span id="title-max-{{ $language->id }}">0</span> / {{ $title_max }}
                                                    caradtéres</label>
                                                <input type="text" class="form-control" name="title_{{ $language->id }}"
                                                    placeholder="Enter Title" id="title-{{ $language->id }}"
                                                    data-less="{{ $title_less_than }}" data-more="{{ $title_more_than }}"
                                                    data-max="{{ $title_max }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="from-group">
                                                <label
                                                    for="">{{ $language->name != 'English' ? _('Premire') : _('Link') }}</label>
                                                <input type="text" class="form-control" name="link_{{ $language->id }}"
                                                    placeholder="Enter Link" id="link-{{ $language->id }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="from-group">
                                                <label for="">Meta Description: <span
                                                        id="meta-desc-max-{{ $language->id }}">0</span> /
                                                    {{ $meta_max }} caradtéres</label>
                                                <textarea name="meta_description_{{ $language->id }}" id="meta-desc-{{ $language->id }}" class="form-control"
                                                    placeholder="Enter Meta Description" cols="30" rows="3" data-less="{{ $description_less_than }}"
                                                    data-more="{{ $description_more_than }}" data-max="{{ $meta_max }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="from-group">
                                                <label for="">Notes</label>
                                                <textarea name="notes_{{ $language->id }}" class="form-control" id="" cols="30" rows="3"
                                                    placeholder="Notes"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
    @foreach ($languages as $language)
        <script>
            $("#title-{{ $language->id }}").on("input", function() {
                value = $(this).val();
                var desc_length = value.length;
                var desc_max = $(this).attr("data-max");
                var desc_less = $(this).attr("data-less");
                var desc_more = $(this).attr("data-more");
                var count_div = $("#title-max-{{ $language->id }}");
                $("#title-max-{{ $language->id }}").html(desc_length);
                count_div.removeClass("text-success").addClass("text-danger")
                if (desc_length == desc_max) {
                    count_div.removeClass("text-danger").addClass("text-success");
                }
                if (desc_length >= desc_less && desc_max > desc_length) {
                    count_div.removeClass("text-danger").addClass("text-success");
                }
                if (desc_length <= desc_more && desc_max < desc_length) {
                    count_div.removeClass("text-danger").addClass("text-success");
                }
                value = value.replace(/\s+/g, '-').toLowerCase();
                value = value.toLowerCase();
                value = value.replace(new RegExp(/[àáâãäå ]/g), "a");
                value = value.replace(new RegExp(/æ /g), "ae");
                value = value.replace(new RegExp(/ç /g), "c");
                value = value.replace(new RegExp(/[èéêë ]/g), "e");
                value = value.replace(new RegExp(/[ìíîï ]/g), "i");
                value = value.replace(new RegExp(/ñ /g), "n");
                value = value.replace(new RegExp(/[òóôõö ]/g), "o");
                value = value.replace(new RegExp(/œ /g), "oe");
                value = value.replace(new RegExp(/[ùúûü ]/g), "u");
                value = value.replace(new RegExp(/[ýÿ ]/g), "y");
                value = value.replace(new RegExp(/\W/g), "-");
                value = value.replace(/[^a-zA-Z0-9-]/g, "");
                $("#link-{{ $language->id }}").val(value);
            });
            $("#meta-desc-{{ $language->id }}").on("input", function() {
                value = $(this).val();
                var desc_length = value.length;
                var desc_max = $(this).attr("data-max");
                var desc_less = $(this).attr("data-less");
                var desc_more = $(this).attr("data-more");
                var count_div = $("#meta-desc-max-{{ $language->id }}");
                $("#meta-desc-max-{{ $language->id }}").html(desc_length);
                count_div.removeClass("text-success").addClass("text-danger")
                if (desc_length == desc_max) {
                    count_div.removeClass("text-danger").addClass("text-success");
                }
                if (desc_length >= desc_less && desc_max > desc_length) {
                    count_div.removeClass("text-danger").addClass("text-success");
                }
                if (desc_length <= desc_more && desc_max < desc_length) {
                    count_div.removeClass("text-danger").addClass("text-success");
                }
            });
        </script>
    @endforeach
    <script>
        $(".settings-page").select2({
            placeholder: "Select Page",
            allowClear: true
        });
    </script>
@endsection
