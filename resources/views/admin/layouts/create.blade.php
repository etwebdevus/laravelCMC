@extends('layouts.admin.app')
@section('title', 'Add New Layout')
@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <h2 class="mb-3">Add New Layout</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('layout.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="from-group">
                                    <label for="">Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Enter Title"
                                        id="title">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="from-group">
                                    <label for="">Link</label>
                                    <input type="text" class="form-control" readonly name="link"
                                        placeholder="Enter Link" id="link">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="from-group">
                                    <label for="">Layout Zip</label>
                                    <div class="custom-file mb-4">
                                        <input type="file" name="zip" class="custom-file-input" accept=".zip" id="customFile">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
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
        $("#title").on("input", function() {
            value = $(this).val();
            value = value.replace(/\s+/g, '-').toLowerCase();
            value = value.replace(/[^a-zA-Z0-9-]/g, "");
            value = value.toLowerCase();
            $("#link").val(value);
        });
    </script>
    <script>
        $(".settings-page").select2({
            placeholder: "Select Page",
            allowClear: true
        });
    </script>
@endsection
