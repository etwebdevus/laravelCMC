@extends('layouts.admin.app')
@section('title', 'Add New Menu')
@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <h2 class="mb-3">Add New Menu</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('menu.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Menu Title</label>
                                    <input type="text" class="form-control" name="menu_title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="menu_type" id="">
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="1">Vertical</option>
                                        <option value="2">Horizontal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="from-group">
                                    <label for="">Menu Item</label>
                                    <input type="number" class="form-control" name="number_item"
                                        placeholder="Enter Number">
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
        $(".settings-page").select2({
            placeholder: "Select Page",
            allowClear: true
        });
    </script>
@endsection
