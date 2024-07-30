@extends('layouts.app')

@section('title')
    Sales Activity - PT. Kencana Zavira
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
                    <h4 class="page-title pull-left">Sales Activity</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('sales.index') }}">Sales</a></li>
                        <li><a
                                href="{{ route('sales.detail-tender', ['id' => encrypt($sales->id)]) }}">{{ $sales->name }}</a>
                        </li>
                        <li><span>Sales Activity</span></li>
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
                        <h4 class="header-title float-left">Sales Activity List : {{ $sales->name }}</h4>
                        <p class="float-end mb-2">
                            {{-- @if (Auth::guard('admin')->user()->can('role.create'))
                        @endif --}}
                            <a class="btn btn-xs btn-primary text-white" onclick="showModal()"><i class="fa fa-plus"></i>
                                New Activity</a>
                            {{-- <button type="submit" class="btn btn-xs btn-primary text-white" onclick="showModal()"><i class="fa fa-plus"></i> New Activity</button> --}}
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table id="tableActivity" class="text-center table w-100 table-hover">
                                    <thead class="bg-light text-capitalize">
                                        <tr class="bg-success text-white">
                                            <th class="text-center v-align-middle">#</th>
                                            <th class="text-left v-align-middle">Date</th>
                                            <th class="text-left v-align-middle">Description</th>
                                            <th class="text-center v-align-middle">By</th>
                                            <th class="text-left v-align-middle">Doc</th>
                                            <th class="text-center v-align-middle">Action</th>
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
    <div class="modal fade" id="activityModal" tabindex="-1" role="dialog" aria-labelledby="newMaterialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add New Activity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="activityForm" class="form-horizontal"
                        action="{{ URL::route('sales.activity-store', ['id' => encrypt($sales->id)]) }}"
                        enctype="multipart/form-data"> <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="tender_id" class="col-sm-4 col-form-label">Tender</label>
                                    <div class="col-sm-8">
                                        {{-- <select class="form-control select2-ajax w-100" data-url="{{ URL::to('select2/get-material') }}" id="materialSelect" data-placeholder="Pilih Material Code" name="material_code_id" style="display: none;"></select> --}}
                                        <input type="text" class="form-control" id="tender_id"
                                            value="{{ $sales->name }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="quantity" class="col-sm-4 col-form-label">Doc Evidence <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="doc_evidence" name="doc_evidence"
                                            accept=".pdf,image/*" required placeholder="Doc Evidence">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label">Date <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="date" name="date"
                                            step="1" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 col-form-label">Description <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea name="description" id="description" class="form-control description-summernote" style="height:150px;"
                                            required></textarea>
                                        <div id="descriptionFeedback" class="invalid-feedback">Description is required
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveNewMaterial">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('tender-logs.index')
@endsection

@push('after-scripts')
    <script type="module">
        $(document).ready(function() {
            $('#activityForm').on('submit', function(event) {
                var description = $('#description').val()
                    .trim(); // Ambil nilai deskripsi dan bersihkan dari whitespace
                var strippedDescription = $('<div>').html(description).text()
                    .trim(); // Bersihkan dari HTML dan whitespace

                if (!strippedDescription) {
                    $('#description').removeClass('is-valid').addClass(
                        'is-invalid'); // Tambahkan kelas is-invalid jika deskripsi kosong
                    $('#descriptionFeedback').show(); // Tampilkan feedback invalid
                    event.preventDefault(); // Mencegah form submission
                } else {
                    $('#description').removeClass('is-invalid').addClass(
                        'is-valid'); // Tambahkan kelas is-valid jika deskripsi valid
                    $('#descriptionFeedback').hide(); // Sembunyikan feedback invalid
                }
            });
        });
        let save_method;
        $(document).ready(function() {
            $('#description').show();
            $('.description-summernote').summernote({
                toolbar: false,
                height: 175,
            });
        })

        function showModal() {
            save_method = 'create';

            $('#modalTitle').text('Create Delevery Point');

            $('#activityForm')[0].reset();
            $('#activityModal').modal('show')
        }
        $('#showTextareaButton').on('click', function() {
            $('#description').show(); // Menampilkan textarea yang awalnya disembunyikan
            $('#description').attr('required', true); // Menambahkan atribut required secara dinamis
        });

        $('#activityForm').on('submit', function(e) {
            e.preventDefault();
            console.log(['form']);

            const formData = new FormData(this);

            let url, method;
            url = "{{ route('sales.activity-store', ['id' => encrypt($sales->id)]) }}";
            method = 'POST';

            if (save_method == 'update') {
                // let id = $('#id').val();


                // let encryptId = encrypt($id);
                url = 'activity/' + $('#id').val();
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
                        title = 'Created activity successfully!';
                    } else if (response.status === 'updated') {
                        title = 'Update successfully';
                    } else {
                        title = 'Informasi';
                    }
                    $('#activityModal').modal('hide');
                    Swal.fire({
                        title: title,
                        text: message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    $('#tableActivity').DataTable().ajax.reload();
                },
                error: function(jqXHR, textStatus, errorthrown) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        const errors = jqXHR.responseJSON.errors;

                        // Reset error messages and styles
                        $('.invalid-feedback').hide();
                        $('.form-control').removeClass('is-invalid');

                        // Iterate through each error field and display the error message
                        $.each(errors, function(field, messages) {
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}_error`).html(messages[0]).show();
                        });
                    } else {
                        console.log(jqXHR.responseText);
                    }
                    // console.log(jqXHR.responseText);
                }
            })
        });
        window.editModal = editModal

        function editModal(e) {
            let id = e.getAttribute('data-id');
            save_method = 'update';

            // alert(id);
            $('#modalTitle').text('Edit Delevery Point');


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "/sales/activity/" + id + "/edit",
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
            $('#delpointModal').modal('show');
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
            $('#tableActivity').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('sales.activity-list', ['id' => encrypt($sales->id)]) }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        className: 'text-left'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        className: 'text-left'
                    },
                    {
                        data: 'user_creator.name',
                        name: 'user_creator.name',
                    },
                    {
                        data: 'doc_evidence',
                        name: 'doc_evidence',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                    }
                ]
            })
        })

        function deleteModal(e) {
            let id = e.getAttribute('data-id');
            Swal.fire({
                title: "Are you sure?",
                text: "you will delete activity!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('sales.activity-destroy', ':id') }}".replace(':id', id),
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
                            $('#tableCurrency').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Deleted!",
                                text: message,
                                showConfirmButton: false,
                                timer: 1000,
                                icon: "success"
                            });
                            $('#tableActivity').DataTable().ajax.reload();

                        }
                    })
                }
            });
        }
    </script>
@endpush
