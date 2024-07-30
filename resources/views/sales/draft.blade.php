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
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    {{-- <h4 class="page-title pull-left">Draft</h4> --}}
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><span>All List Tender</span></li>
                    </ul>
                </div>
            </div>
            {{-- <div class="col-sm-6 clearfix">
            @include('layouts.partials.logout')
        </div> --}}
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title float-left">List Tender</h4>
                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                            {{-- <a class="btn btn-xs btn-primary text-white" href="{{ route('sales.create') }}"><i class="fa fa-plus"></i> New Tender</a> --}}
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">

                                <table id="tableDraft" class="text-center table w-100">
                                    <thead class="bg-light text-capitalize">
                                        <tr>
                                            <th class="text-center v-align-middle">#</th>
                                            <th class="text-center v-align-middle">RFQ No</th>
                                            <th class="text-center v-align-middle">Doc Date</th>
                                            <th class="text-center v-align-middle">Tender Name</th>
                                            <th class="text-center v-align-middle">Customer</th>
                                            <th class="text-center v-align-middle">Value</th>
                                            <th class="text-center v-align-middle">Deadline</th>
                                            <th class="text-center v-align-middle">R-Days</th>
                                            {{-- <th class="text-center v-align-middle">Phone</th> --}}
                                            {{-- <th class="text-center v-align-middle">PIC Name</th> --}}
                                            {{-- <th class="text-center v-align-middle">Email PIC</th> --}}
                                            {{-- <th class="text-center v-align-middle">Position</th> --}}
                                            <th class="text-center v-align-middle">Status</th>
                                            {{-- <th class="text-center v-align-middle">Status Journey</th> --}}
                                            <th class="text-center v-align-middle">Win Lost</th>
                                            <th class="text-center v-align-middle">Remarks Win/Lost</th>
                                            <th class="text-center v-align-middle">Created</th>
                                            <th class="text-center v-align-middle">By</th>
                                            {{-- <th class="text-center v-align-middle">Notes</th> --}}
                                            {{-- <th class="text-center v-align-middle">Action</th> --}}
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
@endsection


@push('after-scripts')
    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

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
            $('#tableDraft').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('draft.index') }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'no_rfq',
                        name: 'no_rfq'
                    },
                    {
                        data: 'tanggal_keluar',
                        name: 'tanggal_keluar'
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'customer.name',
                        name: 'customer.name'
                    },
                    {
                        data: 'currency',
                        name: 'currency'
                    },
                    {
                        data: 'deadline',
                        name: 'deadline'
                    },
                    {
                        data: 'r-Days',
                        name: 'r-Days'
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
                        data: 'status_win_lost',
                        name: 'status_win_lost'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                ]
            })
        })
    </script>
@endpush
