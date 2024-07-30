@extends('layouts.app')

@section('title')
    List Tender - PT. Kencana Zavira
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

        @keyframes blink {
            0% {
                color: #DC3545;
            }

            50% {
                color: white;
            }

            100% {
                color: #DC3545;
            }
        }

        .blink {
            animation: blink 1s infinite;
        }
    </style>
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center" style="height:50px !important;">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">List Tender</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><span>List Tender</span></li>
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
                        <h5>Pra Quotation</h5>
                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table id="tableSales" class="text-center table w-100 table-hover">
                                    <thead class="bg-light text-capitalize">
                                        <tr class="bg-success text-white">
                                            <th class="text-center v-align-middle">#</th>
                                            <th class="text-left v-align-middle">Tender Name</th>
                                            <th class="text-left v-align-middle">RFQ No</th>
                                            <th class="text-left v-align-middle">Customer</th>
                                            <th class="text-right v-align-middle">Value</th>
                                            <th class="text-center v-align-middle">Release</th>
                                            <th class="text-center v-align-middle">Deadline</th>
                                            <th class="text-center v-align-middle">R-Days</th>
                                            <th class="text-center v-align-middle">Status</th>
                                            <th class="text-center v-align-middle">Win Lost</th>
                                            <th class="text-center v-align-middle">By</th>
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
                        <h5>Post Quotation</h5>

                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table id="tablePosQuation" class="text-center table w-100 table-hover">
                                    <thead class="bg-light text-capitalize">
                                        <tr class="bg-success text-white">
                                            <th class="text-center v-align-middle">#</th>
                                            <th class="text-left v-align-middle">Tender Name</th>
                                            <th class="text-left v-align-middle">RFQ No</th>
                                            <th class="text-left v-align-middle">Customer</th>
                                            <th class="text-right v-align-middle">Value</th>
                                            <th class="text-center v-align-middle">Release</th>
                                            <th class="text-center v-align-middle">Deadline</th>
                                            {{-- <th class="text-center v-align-middle">R-Days</th> --}}
                                            <th class="text-center v-align-middle">Status</th>
                                            <th class="text-center v-align-middle">Win Lost</th>
                                            <th class="text-center v-align-middle">By</th>
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
            $('#tableSales').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('list-tender.index', ['category' => 'pra-quotation']) }}"
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
                        data: 'customer.name',
                        name: 'customer.name',
                        className: 'text-left'
                    },
                    {
                        data: 'currency',
                        name: 'currency',
                        className: 'text-right'
                    },
                    {
                        data: 'tanggal_keluar',
                        name: 'tanggal_keluar'
                    },
                    {
                        data: 'deadline',
                        name: 'deadline'
                    },
                    {
                        data: 'r-days',
                        name: 'r-days'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'win_lost',
                        name: 'win_lost'
                    },
                    {
                        data: 'user_creator.name',
                        name: 'user_creator.name'
                    },
                ]
            })
        })
        $(document).ready(function() {
            $('#tablePosQuation').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('list-tender.index', ['category' => 'post-quotation']) }}"
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
                        data: 'customer.name',
                        name: 'customer.name',
                        className: 'text-left'
                    },
                    {
                        data: 'currency',
                        name: 'currency',
                        className: 'text-right'
                    },
                    {
                        data: 'tanggal_keluar',
                        name: 'tanggal_keluar'
                    },
                    {
                        data: 'deadline',
                        name: 'deadline'
                    },
                    // {
                    //     data: 'r-days',
                    //     name: 'r-days'
                    // },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'win_lost',
                        name: 'win_lost'
                    },
                    {
                        data: 'user_creator.name',
                        name: 'user_creator.name'
                    },
                ]
            })
        })
    </script>
@endpush
