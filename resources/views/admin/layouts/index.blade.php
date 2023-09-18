@extends('layouts.admin.app')
@section('title', 'All Layouts')
@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h2 class="mb-3">All Layouts</h2>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('layout.create') }}"
                                class="btn btn-primary float-right mb-3">{{ _('Add New Layouts') }}</a>
                        </div>
                    </div>
                    @if (session('success_message'))
                        <div class="alert alert-success mb-3">
                            {{ session('success_message') }}
                        </div>
                    @endif
                    <table id="style-3" class="table style-3  table-hover">
                        <thead>
                            <tr>
                                <th class="checkbox-column text-center"> Record Id </th>
                                <th>Title</th>
                                <th>Link</th>
                                <th class="text-center dt-no-sorting">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($layouts as $layout)
                                <tr>
                                    <td class="checkbox-column text-center"> {{ $count++ }} </td>
                                    <td>{{ $layout->title }}</td>
                                    <td>{{ $layout->link }}</td>
                                    <td class="text-center">
                                        <ul class="table-controls">
                                            <li><a href="{{ route('layout.delete', $layout->id) }}"
                                                    class="bs-tooltip" data-toggle="tooltip" data-placement="top"
                                                    title="" data-original-title="Delete"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-trash p-1 br-6 mb-1">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                    </svg></a></li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
