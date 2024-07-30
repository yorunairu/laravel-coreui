@extends('layouts.app')

@section('title')
    Payment Status - PT. Kencana Zavira
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
                    <h4 class="page-title pull-left">Payment Status</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><span>All Payment Status</span></li>
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
                        {{-- <h4>Pra Review</h4> --}}
                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                            {{-- <a class="btn btn-xs btn-primary text-white" href="{{ route('sales.create') }}"><i class="fa fa-plus"></i> New Tender</a> --}}
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table id="table-payment" class="text-center table w-100">
                                    <thead class="bg-light text-capitalize">
                                        <tr class="bg-success text-white">
                                            <th class="text-center v-align-middle">#</th>
                                            <th class="text-left v-align-middle">Tender</th>
                                            <th class="text-left v-align-middle">Principle</th>
                                            <th class="text-left v-align-middle">PO No</th>
                                            <th class="text-left v-align-middle">PO Date</th>
                                            <th class="text-left v-align-middle">Material Name</th>
                                            <th class="text-right v-align-middle">Value</th>
                                            <th class="text-left v-align-middle">Production Period</th>
                                            <th class="text-left v-align-middle">TOP</th>
                                            <th class="text-left v-align-middle">Payment Date</th>
                                            <th class="text-left v-align-middle">Note</th>
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
            $('#table-payment').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('payment.index') }}"
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
                        data: 'principle_name',
                        name: 'principle_name',
                        className: 'text-left'
                    },
                    {
                        data: 'tender_po.principle_po_no',
                        name: 'tender_po.principle_po_no',
                        className: 'text-left'
                    },
                    {
                        data: 'tender_po.principle_po_date',
                        name: 'tender_po.principle_po_date',
                        className: 'text-left'
                    },
                    {
                        data: 'material_name',
                        name: 'material_name',
                        className: 'text-left'
                    },
                    {
                        data: 'value',
                        name: 'value',
                        className: 'text-right'
                    },
                    {
                        data: 'production_period',
                        name: 'production_period'
                    },
                    {
                        data: 'top',
                        name: 'top',
                    },
                    {
                        data: 'payment_date',
                        name: 'payment_date'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                ]
            })
        })
    </script>
@endpush
