@extends('layouts.admin.app')
@section('title', 'Update Page')
@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <h2 class="mb-3">Update Page</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('page.update', [$page->id, $connect_same]) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Page Over View Title</label>
                                    <input type="text" class="form-control" name="page_title"
                                        value="{{ $page->title }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Layout</label>
                                    <select class="form-control settings-page" name="page_layout" id="">
                                        <option value="" selected disabled>Select Layout</option>
                                        <option value="0" {{ $page->layout == 0 ? 'selected' : '' }}>Empty Layout
                                        </option>
                                        @foreach ($layouts as $layout)
                                            <option value="{{ $layout->id }}"
                                                {{ $page->layout == $layout->id ? 'selected' : '' }}>{{ $layout->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @foreach ($page_translations as $page_translation)
                                @php
                                    $title_class = 'text-danger';
                                    $title_length = strlen($page_translation['page_translate']['title']);
                                    $title_less_than = ($title_max / 100) * 90;
                                    $title_more_than = ($title_max / 100) * 110;
                                    $description_class = 'text-danger';
                                    $description_length = strlen($page_translation['page_translate']['meta_description']);
                                    $description_less_than = ($meta_max / 100) * 90;
                                    $description_more_than = ($meta_max / 100) * 110;
                                    if ($title_max == $title_length) {
                                        $title_class = 'text-success';
                                    } elseif ($title_length >= $title_less_than && $title_max > $title_length) {
                                        $title_class = 'text-success';
                                    } elseif ($title_length <= $title_more_than && $title_max < $title_length) {
                                        $title_class = 'text-success';
                                    } else {
                                        $title_class == 'text-danger';
                                    }
                                    if ($meta_max == $description_length) {
                                        $description_class = 'text-success';
                                    } elseif ($description_length >= $description_less_than && $meta_max > $description_length) {
                                        $description_class = 'text-success';
                                    } elseif ($description_length <= $description_more_than && $meta_max < $description_length) {
                                        $description_class = 'text-success';
                                    }
                                @endphp
                                <div class="col-md-12">
                                    <h4>{{ $page_translation['language_name'] }}</h4>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="from-group">
                                                <label
                                                    for="">{{ $page_translation['language_name'] != 'English' ? _('Première') : _('Title') }}:
                                                    <span
                                                        id="title-max-{{ $page_translation['page_translate']['id'] }}" class="{{ $title_class }}">{{ strlen($page_translation['page_translate']['title']) }}</span>
                                                    / {{ $title_max }} caradtéres</label>
                                                <input type="text" class="form-control "
                                                    name="title_{{ $page_translation['page_translate']['id'] }}"
                                                    placeholder="Enter Title"
                                                    id="title-{{ $page_translation['page_translate']['id'] }}"
                                                    value="{{ $page_translation['page_translate']['title'] }}"
                                                    data-less="{{ $title_less_than }}" data-more="{{ $title_more_than }}"
                                                    data-max="{{ $title_max }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="from-group">
                                                <label
                                                    for="">{{ $page_translation['language_name'] != 'English' ? _('Premire') : _('Link') }}</label>
                                                <input type="text" class="form-control"
                                                    name="link_{{ $page_translation['page_translate']['id'] }}"
                                                    placeholder="Enter Link"
                                                    id="link-{{ $page_translation['page_translate']['id'] }}"
                                                    value="{{ $page_translation['page_translate']['link'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="from-group">
                                                <label for="">Meta Description: <span
                                                        id="meta-desc-max-{{ $page_translation['page_translate']['id'] }}" class="{{ $description_class }}">{{ strlen($page_translation['page_translate']['meta_description']) }}</span>
                                                    / {{ $meta_max }} caradtéres</label>
                                                <textarea id="meta-desc-{{ $page_translation['page_translate']['id'] }}"
                                                    name="meta_description_{{ $page_translation['page_translate']['id'] }}" class="form-control "
                                                    placeholder="Enter Meta Description" cols="30" rows="3" data-less="{{ $description_less_than }}"
                                                    data-more="{{ $description_more_than }}" data-max="{{ $meta_max }}">{{ $page_translation['page_translate']['meta_description'] }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="from-group">
                                                <label for="">Notes</label>
                                                <textarea name="notes_{{ $page_translation['page_translate']['id'] }}" class="form-control" id=""
                                                    cols="30" rows="3" placeholder="Notes">{{ $page_translation['page_translate']['notes'] }}</textarea>
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
    @foreach ($page_translations as $page_translation)
        <script>
            $("#title-{{ $page_translation['page_translate']['id'] }}").on("input", function() {
                value = $(this).val();
                $("#title-max-{{ $page_translation['page_translate']['id'] }}").html(value.length);
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
                $("#link-{{ $page_translation['page_translate']['id'] }}").val(value);
                var desc_length = value.length;
                var desc_max = $(this).attr("data-max");
                var desc_less = $(this).attr("data-less");
                var desc_more = $(this).attr("data-more");
                var count_div = $("#title-max-{{ $page_translation['page_translate']['id'] }}");
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
            $("#meta-desc-{{ $page_translation['page_translate']['id'] }}").on("input", function() {
                value = $(this).val();
                var desc_length = value.length;
                var desc_max = $(this).attr("data-max");
                var desc_less = $(this).attr("data-less");
                var desc_more = $(this).attr("data-more");
                var count_div = $("#meta-desc-max-{{ $page_translation['page_translate']['id'] }}");
                $("#meta-desc-max-{{ $page_translation['page_translate']['id'] }}").html(value.length);
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
