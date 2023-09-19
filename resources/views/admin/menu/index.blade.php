@extends('layouts.admin.app')
@section('title', 'Menu')
@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h2 class="mb-3">Menu</h2>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('menu.create') }}"
                                class="btn btn-primary float-right mb-3">{{ _('Add New Menu') }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="from-group">
                                <label for="">Select Menu Style </label>
                                <select name="" id="get-page-data" class="form-control settings-page">
                                    <option value="0" selected disabled>-- Select Type --</option>
                                    <option value="1">Vertival</option>
                                    <option value="2">Horizontal</option>
                                </select>
                            </div>
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
                                <th>Type</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">View</th>
                                <th class="text-center dt-no-sorting">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($allmenus as $menu)
                                <tr>
                                    <td class="checkbox-column text-center"> {{ $count++ }} </td>
                                    <td>{{ $menu->title }}</td>
                                    <td>{{ $menu->type }}</td>
                                    <td class="text-center"><span
                                            class="shadow-none badge badge-{{ $menu->status != 0 ? 'success' : 'danger' }}">{{ $menu->status != 0 ? 'Active' : 'No Active' }}</span>
                                    </td>
                                    <td class="text-center"><a
                                            href="{{url("menupreview/$menu->id")}}"
                                            target="_blank"
                                            class="btn btn-dark btn-sm">{{ $menu->status == 0 ? _('Preview') : _('View') }}</a>
                                    </td>
                                    <td class="text-center">
                                        <ul class="table-controls">
                                            @if ($menu->status == 0)
                                                <li><a href="{{ route('menu.status', [$menu->id]) }}"
                                                        class="bs-tooltip" data-toggle="tooltip" data-placement="top"
                                                        title="" data-original-title="Active"><svg viewBox="0 0 24 24"
                                                            width="24" height="24" stroke="currentColor"
                                                            stroke-width="2" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round" class="css-i6dzq1  p-1 br-6 mb-1">
                                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                        </svg></a></li>
                                            @endif
                                           
                                            <li><a href="{{ route('menu.edit', [$menu->id]) }}"
                                                    class="bs-tooltip" data-toggle="tooltip" data-placement="top"
                                                    title="" data-original-title="edit"><svg viewBox="0 0 24 24"
                                                        width="24" height="24" stroke="currentColor"
                                                        stroke-width="2" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round" class="css-i6dzq1 p-1 br-6 mb-1">
                                                        <path
                                                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                                                        </path>
                                                    </svg></a></li>
                                            <li><a href="javascript:(0)" onclick="deleted(this)"
                                                    data-href="{{ route('menu.delete', [$menu->id]) }}"
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
                        window.open(href,"_self");
                        // Swal.fire(
                        //     'Deleted!',
                        //     'Your file has been deleted.',
                        //     'success'
                        // )
                    }
                })
        }
    </script>
@endsection
