@extends('layouts.app')

@section('title')
    Unit Of Measure - PT. Kencana Zavira
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
                    <h4 class="page-title pull-left">Unit Of Measure</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><span>Unit Of Measure</span></li>
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
                        <h4 class="header-title float-left">Unit Of Measure List</h4>
                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                            {{-- <a class="btn btn-xs btn-primary text-white" href="{{ route('uom.create') }}"><i class="fa fa-plus"></i> New Unit Of Measure</a> --}}
                            {{-- <button type="submit" class="btn btn-xs btn-primary text-white" onclick="showModal()>New Unit Of Measure</button> --}}
                            {{-- <button type="submit" class="btn btn-xs btn-primary" onclick="showModal()"><i class="fa fa-plus"></i> New Unit Of Measure</button> --}}

                            <button type="submit" class="btn btn-xs btn-primary" onclick="showModal()"><i
                                    class="fa fa-plus"></i> New Unit</button>

                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <table id="tableUom" class="text-center table w-100">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Name Unit</th>
                                        <th width="10%">Action</th>
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
    <div class="modal" id="uomModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Unit Of Measure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" id="formUom">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label required">Unit Of Measure <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-xs btn-primary btnCreate"><i class="fa fa-save"></i>
                                Save</button>
                        </div>
                    </form>
                </div>
            </div>
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
        let save_method
        $(document).ready(function() {
            table();
        })

        function table() {
            $('#tableUom').DataTable({
                processing: true,
                serveSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('uom.index') }}",
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
                        data: 'action',
                        name: 'action',
                    },
                ]
            })
        }

        function showModal() {
            save_method = 'create';
            $('#modalTitle').text('Create Unit of Measure');

            $('#formUom')[0].reset();
            $('#uomModal').modal('show')
        }

        $('#formUom').on('submit', function(e) {
            e.preventDefault();
            console.log(['form']);

            const formData = new FormData(this);

            let url, method;
            url = '{{ route('uom.store') }}';
            method = 'POST';

            if (save_method == 'update') {
                // let id = $('#id').val();


                // let encryptId = encrypt($id);
                url = 'uom/' + $('#id').val();
                formData.append('_method', 'PUT');
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: method,
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let message = response.message;

                    // Menampilkan pesan berdasarkan status aksi (edit atau create)
                    let title = '';

                    if (response.status === 'created') {
                        title = 'Success!';
                    } else if (response.status === 'updated') {
                        title = 'Update successfully';
                    } else {
                        title = 'Informasi';
                    }
                    $('#uomModal').modal('hide');
                    Swal.fire({
                        title: title,
                        text: message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    $('#tableUom').DataTable().ajax.reload();
                },
                error: function(jqXHR, textStatus, errorthrown) {
                    console.log(jqXHR.responseText);
                }
            })
        });
        window.editModal = editModal

        function editModal(e) {
            let id = e.getAttribute('data-id');
            save_method = 'update';

            // alert(id);
            $('#modalTitle').text('Edit Unit Of Measure');


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "uom/" + id + "/edit",
                success: function(response) {
                    let result = response.data;
                    $('#id').val(result.id);
                    $('#name').val(result.name);
                    // alert(result.name);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert(xhr.responseText)
                }
            })
            $('#uomModal').modal('show');
        }

        function deleteModal(e) {
            let id = e.getAttribute('data-id');
            Swal.fire({
                title: "Are your sure?",
                text: "you will delete unit of measure!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "uom/destroy/" + id,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(id);
                            let message = response.message;

                            // Menampilkan pesan berdasarkan status aksi (edit atau create)
                            let title = '';

                            if (response.status === 'deleted') {
                                title = 'Deleted!';
                            }
                            $('#tableUom').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Deleted!",
                                text: message,
                                showConfirmButton: false,
                                timer: 1000,
                                icon: "success"
                            });
                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Failed to save customer.", "error");
                            console.log(xhr.responseText);
                        }
                    })
                }
            });
        }
    </script>
@endpush
