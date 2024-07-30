@extends('layouts.app')

@section('title')
    Tender Margin - PT. Kencana Zavira
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('content')
    <style>
        .v-align-middle {
            vertical-align: middle !important;
        }
    </style>
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center" style="height:50px !important;">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Tender Margin</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><span>All Tender Margin</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
                {{-- @include('layouts.partials.logout') --}}
            </div>
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h4>Pra Review</h4>
                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                            {{-- <a class="btn btn-xs btn-primary text-white" href="{{ route('sales.create') }}"><i class="fa fa-plus"></i> New Tender</a> --}}
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table id="table-margin-pra" class="text-center table w-100">
                                    <thead class="bg-light text-capitalize">
                                        <tr class="bg-success text-white">
                                            <th class="text-center v-align-middle">#</th>
                                            <th class="text-left v-align-middle">Tender Name</th>
                                            <th class="text-left v-align-middle">RFQ No</th>
                                            <th class="text-left v-align-middle">Price Principle</th>
                                            <th class="text-left v-align-middle">Price KZ</th>
                                            <th class="text-right v-align-middle">Total Amount</th>
                                            <th class="text-center v-align-middle">Percentage</th>
                                            <th class="text-center v-align-middle">Review Status</th>
                                            <th class="text-center v-align-middle">Review By</th>
                                            <th class="text-center v-align-middle">Review Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- data table end -->
        </div>
    </div>
    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h4>Post Review</h4>
                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                            {{-- <a class="btn btn-xs btn-primary text-white" href="{{ route('sales.create') }}"><i class="fa fa-plus"></i> New Tender</a> --}}
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table id="table-margin-post" class="text-center table w-100">
                                    <thead class="bg-light text-capitalize">
                                        <tr class="bg-success text-white">
                                            <th class="text-center v-align-middle">#</th>
                                            <th class="text-left v-align-middle">Tender Name</th>
                                            <th class="text-left v-align-middle">RFQ No</th>
                                            <th class="text-left v-align-middle">Price Principle</th>
                                            <th class="text-left v-align-middle">Price KZ</th>
                                            <th class="text-right v-align-middle">Price Margin</th>
                                            <th class="text-center v-align-middle">Margin %</th>
                                            <th class="text-center v-align-middle">Review Status</th>
                                            <th class="text-center v-align-middle">Review By</th>
                                            <th class="text-center v-align-middle">Review Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- data table end -->
        </div>
    </div>
    @include('tender-logs.index')
@endsection


@push('after-scripts')
    <script type="module">
        $(document).ready(function() {});

        function confirmDelete(deleteUrl) {
            console.log(deleteUrl)
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
                    $.ajax({
                        type: "DELETE",
                        url: deleteUrl,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#table-procurement').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Deleted!",
                                text: "The tender data has been deleted.",
                                showConfirmButton: false,
                                timer: 1000,
                                icon: "success"
                            });
                        }
                    })
                }
            });
        }

        function confirmFinal(finalUrl) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, transfer to procurement!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: finalUrl,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#table-procurement').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Transfered!",
                                text: "The tender data has been transfered to procurement team!.",
                                showConfirmButton: false,
                                timer: 1000,
                                icon: "success"
                            });
                        }
                    })
                }
            });
        }
    </script>

    <!-- Start datatable js -->
    <script type="module">
        /*================================
                                    datatable active
                                    ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true
            });
        }

        $(document).ready(function() {
            $('#table-margin-post').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('tender-margin-post.index') }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-left'
                    },
                    {
                        data: 'no_rfq',
                        name: 'no_rfq',
                        className: 'text-left'
                    },
                    {
                        data: 'price_principle',
                        name: 'price_principle',
                        className: 'text-left'
                    },
                    {
                        data: 'price_kz',
                        name: 'price_kz',
                        className: 'text-left'
                    },
                    {
                        data: 'margin',
                        name: 'margin',
                        className: 'text-right'
                    },
                    {
                        data: 'percentage',
                        name: 'percentage',
                        className: 'text-right'
                    },
                    {
                        data: 'review_status',
                        name: 'review_status'
                    },
                    {
                        data: 'review.name',
                        name: 'review.name'
                    },
                    {
                        data: 'review_date',
                        name: 'review_date'
                    },
                ]
            })
        })
        $(document).ready(function() {
            $('#table-margin-pra').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('tender-margin-pra.index') }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-left'
                    },
                    {
                        data: 'no_rfq',
                        name: 'no_rfq',
                        className: 'text-left'
                    },
                    {
                        data: 'price_principle',
                        name: 'price_principle',
                        className: 'text-left'
                    },
                    {
                        data: 'price_kz',
                        name: 'price_kz',
                        className: 'text-left'
                    },
                    {
                        data: 'margin',
                        name: 'margin',
                        className: 'text-right'
                    },
                    {
                        data: 'percentage',
                        name: 'percentage',
                        className: 'text-right'
                    },
                    {
                        data: 'review_status',
                        name: 'review_status'
                    },
                    {
                        data: 'reviewed_by',
                        name: 'reviewed_by'
                    },
                    {
                        data: 'review_date',
                        name: 'review_date'
                    },
                ]
            })
        })
    </script>
@endpush
