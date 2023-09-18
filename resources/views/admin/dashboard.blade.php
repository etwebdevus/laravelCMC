@extends('layouts.admin.app')
@section('title', 'Admin Dashboard')
@section('content')
    <div class="row layout-top-spacing justify-content-center">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one_hybrid widget-followers bg-secondary">
                <div class="widget-heading">
                    <div class="w-title">
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-layout">
                                <rect x="3" y="3" width="18" height="18" rx="2"
                                    ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="9" y1="21" x2="9" y2="9"></line>
                            </svg>
                        </div>
                        <div class="">
                            <p class="w-value">Active</p>
                            <h5 class="">These are the active pages, but they are not necessarily visible if no link
                                or menu entry points to it</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing ">
            <div class="widget widget-one_hybrid widget-followers bg-success">
                <div class="widget-heading">
                    <div class="w-title">
                        <div class="w-icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="">
                            <p class="w-value">All Pages </p>
                            <h5 class="">These are all the pages, namely, a page can have several variations but only one can be activated.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing ">
            <div class="widget widget-one_hybrid widget-followers bg-danger">
                <div class="widget-heading">
                    <div class="w-title">
                        <div class="w-icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <rect x="6" y="4" width="4" height="16"></rect>
                                <rect x="14" y="4" width="4" height="16"></rect>
                            </svg>
                        </div>
                        <div class="">
                            <p class="w-value">Settings</p>
                            <h5 class="">- Coming soon: English and French language
                                - Let’s select from the default error page.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
