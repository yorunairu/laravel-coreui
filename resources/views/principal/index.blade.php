@extends('layouts.app')

@section('title')
    Principle - PT. Kencana Zavira
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
        <div class="row align-items-center" style="height:50px !important;">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Principal</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><span>Principal</span></li>
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
                        <h4 class="header-title float-left">Principal List</h4>
                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                            <a class="btn btn-xs btn-primary text-white" href="{{ route('principle.create') }}"><i
                                    class="fa fa-plus"></i> New Principle</a>

                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <table id="tablePrinciple" class="text-center table w-100">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Principle Name</th>
                                        <th width="10%">Address</th>
                                        <th width="10%">Email</th>
                                        <th width="10%">Telephone</th>
                                        <th width="10%">PIC</th>
                                        <th width="10%">Position</th>
                                        <th width="10%">Email PIC</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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
        // if ($('#dataTable').length) {
        //     $('#dataTable').DataTable({
        //         responsive: true
        //     });
        // }

        $(document).ready(function() {
            $('#tablePrinciple').DataTable({
                processing: true,
                serveSide: false,
                ajax: {
                    url: "{{ route('principle.index') }}",
                    // dataSrc: ''
                    data: function(data) {
                        console.log(data);
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'address',
                        name: 'address',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                    },
                    {
                        data: 'pic_name',
                        name: 'pic_name',
                    },
                    {
                        data: 'position',
                        name: 'position',
                    },
                    {
                        data: 'email_pic',
                        name: 'email_pic',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ]
            })
        })

        function editModal(e) {
            let id = e.getAttribute('data-id');
            // alert(id)
            window.location.href = "{{ url('principle') }}/" + id + "/edit"
        }

        function deleteModal(e) {
            let id = e.getAttribute('data-id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "principle/destroy/" + id,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // console.log(id);
                            let message = response.message;
                            $('#tablePrinciple').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Deleted!",
                                text: message,
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
@endpush
