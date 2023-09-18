@extends('layouts.admin.app')
@section('title', 'SEO Pages')
@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h2 class="mb-3">SEO Pages</h2>
                        </div>
                    </div>
                    @if (session('success_message'))
                        <div class="alert alert-success mb-3">
                            {{ session('success_message') }}
                        </div>
                    @endif
                    <form action="{{ route('page.seo-store') }}" method="post">
                        @csrf
                        <table id="style-3" class="table style-3  table-hover">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center"> Record Id </th>
                                    <th>Title</th>
                                    <th>{{ substr('Size meta-title (150 optimum) more or less than 10% in red | Size méta description more or less than 10% in red', 0, 96) . '...' }}
                                    </th>
                                    <th>Number of words on the page</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = 1;
                                @endphp
                                @foreach ($allpages as $allpage)
                                    <tr>
                                        <td class="checkbox-column text-center"> {{ $count++ }} </td>
                                        <td>{{ $allpage->page->title }}</td>
                                        <td>
                                            <div class="input-group  mb-3">
                                                <div class="input-group-prepend">
                                                    @php
                                                        $title_class = 'text-danger';
                                                        $title_length = strlen($allpage->title);
                                                        $title_less_than = ($title_max / 100) * 90;
                                                        $title_more_than = ($title_max / 100) * 110;
                                                        $description_class = 'text-danger';
                                                        $description_length = strlen($allpage->meta_description);
                                                        $description_less_than = ($meta_max / 100) * 90;
                                                        $description_more_than = ($meta_max / 100) * 110;
                                                        if ($title_max == $title_length) {
                                                            $title_class = 'text-success';
                                                        } elseif ($title_length >= $title_less_than && $title_max > $title_length ) {
                                                            $title_class = 'text-success';
                                                        } elseif ($title_length <= $title_more_than && $title_max < $title_length) {
                                                            $title_class = 'text-success';
                                                        }else{
                                                            $title_class == "text-danger";
                                                        }
                                                        if ($meta_max == $description_length) {
                                                            $description_class = 'text-success';
                                                        } elseif ($description_length >= $description_less_than && $meta_max > $description_length) {
                                                            $description_class = 'text-success';
                                                        } elseif ($description_length <= $description_more_than && $meta_max < $description_length) {
                                                            $description_class = 'text-success';
                                                        }
                                                        
                                                       $content = DB::select('SELECT * FROM `page_contents` where page_id=? and page_translation_connect = ?', [$allpage->page_id,$allpage->connect_same]);
                                                       
                                                       $data = json_decode(base64_decode($content[0]->data));
                                                        if (isset($data->body)) {
                                                            $replace_tags =strip_tags($data->body);
                                                        } 
                                                        else 
                                                        {
                                                            continue;
                                                        }
                                                       
                                                    @endphp
                                                    <span
                                                        class="input-group-text {{ $title_class }}" id="meta-title-length-{{ $allpage->id }}">{{ strlen($allpage->title) }}</span>
                                                </div>
                                                <input class="form-control meta-title" type="text" placeholder="Meta Title"
                                                    value="{{ $allpage->title }}" name="title_{{ $allpage->id }}"  data-id="{{ $allpage->id }}"  data-less="{{ $title_less_than }}" data-more="{{ $title_more_than }}" data-max="{{ $title_max }}">
                                            </div>
                                            <div class="input-group ">
                                                <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text {{ $description_class }}" id="meta-description-length-{{ $allpage->id }}">{{ strlen($allpage->meta_description) }}</span>
                                                </div>
                                                <input class="form-control meta-description" type="text"
                                                    name="meta_description_{{ $allpage->id }}"
                                                    placeholder="Meta Description"
                                                    value="{{ $allpage->meta_description }}" data-id="{{ $allpage->id }}" data-less="{{ $description_less_than }}" data-more="{{ $description_more_than }}" data-max="{{ $meta_max }}">
                                            </div>
                                        </td>
                                        <td>{{ number_format(str_word_count($replace_tags)) }} Words</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function deleted(thiss) {
            var href = $(thiss).attr("data-href");
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(href, "_self");
                    // Swal.fire(
                    //     'Deleted!',
                    //     'Your file has been deleted.',
                    //     'success'
                    // )
                }
            })
        }
        $(document).on("input",".meta-description",function(){
            var desc_length = $(this).val().length;
            var desc_max = $(this).attr("data-max");
            var desc_less = $(this).attr("data-less");
            var desc_more = $(this).attr("data-more");
            var desc_id = $(this).attr("data-id");
            var count_div = $(`#meta-description-length-${desc_id}`);
            count_div.html(desc_length);
            count_div.removeClass("text-success").addClass("text-danger")
            if(desc_length == desc_max){
                count_div.removeClass("text-danger").addClass("text-success");
            }
            if(desc_length >= desc_less && desc_max > desc_length){
                count_div.removeClass("text-danger").addClass("text-success");
            }
            if(desc_length <= desc_more && desc_max < desc_length){
                count_div.removeClass("text-danger").addClass("text-success");
            }

        });
        $(document).on("input",".meta-title",function(){
            var desc_length = $(this).val().length;
            var desc_max = $(this).attr("data-max");
            var desc_less = $(this).attr("data-less");
            var desc_more = $(this).attr("data-more");
            var desc_id = $(this).attr("data-id");
            var count_div = $(`#meta-title-length-${desc_id}`);
            count_div.html(desc_length);
            count_div.removeClass("text-success").addClass("text-danger")
            if(desc_length == desc_max){
                count_div.removeClass("text-danger").addClass("text-success");
            }
            if(desc_length >= desc_less && desc_max > desc_length){
                count_div.removeClass("text-danger").addClass("text-success");
            }
            if(desc_length <= desc_more && desc_max < desc_length){
                count_div.removeClass("text-danger").addClass("text-success");
            }

        });
    </script>
@endsection
