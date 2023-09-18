@extends('layouts.admin.app')
@section('title', 'Profile Update')
@section('content')
<div class="row layout-top-spacing">
    <div class="col-lg-12">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <h2 class="mb-3">Profile Update</h2>
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
                <form action="{{ route('admin.profile-update') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="from-group">
                                <label for="">Full Name</label>
                                <input type="text" value="{{ $user->name }}" name="full_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="from-group">
                                <label for="">Email Address</label>
                                <input type="text" value="{{ $user->email }}" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="from-group">
                                <label for="">Password</label>
                                <input type="text" name="password" class="form-control">
                                <small>If You Want To change Then Type Or Else Leave It Empty</small>
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
@endsection
