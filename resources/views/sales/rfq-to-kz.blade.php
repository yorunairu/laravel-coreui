@extends('layouts.app')

@section('title')
    Detail Procurement - PT Kencana Zavira
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        .document-icon {
            font-size: 12px;
            /* Adjust the size as needed */
            margin-right: 10px;
            color: #ff5722;
            /* Change the color if needed */
        }

        .document-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #007bff;
            /* Change the color if needed */
        }

        .document-link:hover {
            text-decoration: underline;
        }

        .document-link i {
            margin-right: 5px;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection

@section('content')
    {{-- @php
    dd($sales)
@endphp --}}
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center" style="height:50px !important;">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Detail Procurement</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('procurement.index') }}">Procurement</a></li>
                        <li><a href="{{ route('procurement.detail-tender', ['id' => encrypt($sales->id)]) }}">Detail
                                Procurement</a></li>
                        <li><span>RFQ to Principle</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
            </div>
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner" id="bottom-form-section">
        <div class="row">
            @include('widgets.tender-customer')
            <div class="col-12 mt-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title float-left">RFQ : {{ $sales->name }} <span
                                id="rfq-no-title">{{ @$sales->tenderRfq->rfq_no ? '- ' . $sales->tenderRfq->rfq_no : '' }}</span>
                        </h4>
                        <div class="float-end mb-2">
                            <a id="addRfqModal"
                                class="btn btn-xs btn-primary text-white pull-right mx-1 {{ !in_array($sales->status_journey, [1, 2]) ? 'disabled' : '' }}"
                                href="#" data-toggle="modal" data-target="#newRFQModal"><i class="fa fa-pencil"></i>
                                RFQ</a>
                            <a id="tender-log-modal"
                                data-url="{{ URL::route('fetch.get-tender-log', ['id' => encrypt($sales->id)]) }}"
                                href="#" title="History Tender" class="btn btn-xs btn-info mx-1" data-toggle="modal"
                                data-target="#tenderLogModal"><i class="fa fa-history"></i> History Tender</a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table class="table w-100 table-striped table-hover table-bordered" id="table-rfq">
                                    <thead class="bg-success text-white">
                                        <th class="text-center v-align-middle">No</th>
                                        <th class="text-left v-align-middle">Principle</th>
                                        <th class="text-center v-align-middle">Status</th>
                                        <th class="text-left v-align-middle">Date Delivery</th>
                                        <th class="text-right v-align-middle">Price</th>
                                        <th class="text-right v-align-middle">Doc</th>
                                        <th class="text-center v-align-middle">Action</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('tender-logs.index')

    <!-- Modal HTML -->
    <div class="modal fade" id="newRFQModal" tabindex="-1" role="dialog" aria-labelledby="newRFQModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newRFQModalLabel">RFQ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="newRFQForm" class="form-horizontal"
                        action="{{ URL::route('procurement.store-rfq', ['id' => encrypt($sales->id)]) }}">
                        <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="rfq_no" class="col-sm-4 col-form-label">No RFQ<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="rfq_no" id="rfq_no" class="form-control"
                                            value="{{ !empty($sales->tenderRfq->rfq_no) ? $sales->tenderRfq->rfq_no : rfqNoKzToPrinciple() }}"
                                            required readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="date_deadline" class="col-sm-4 col-form-label">Date Deadline RFQ<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_deadline" id="date_deadline" class="form-control"
                                            value="{{ !empty($sales->tenderRfq->date_deadline) ? date('Y-m-d', strtotime($sales->tenderRfq->date_deadline)) : date('Y-m-d', strtotime('+1 week')) }}"
                                            2 required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="principle_ids" class="col-sm-4 col-form-label">Principles <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="principle_ids[]" id="principle_ids" class="form-control select2"
                                            data-placeholder="Select Principle" multiple="multiple" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="delivery_point_ids" class="col-sm-4 col-form-label">Delivery Point <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="delivery_point_ids[]" id="delivery_point_ids"
                                            class="form-control select2" data-placeholder="Select Delivery Point"
                                            multiple="multiple" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="term_comp_ids" class="col-sm-4 col-form-label">Term Comp <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="term_comp_ids[]" id="term_comp_ids" class="form-control select2"
                                            data-placeholder="Select Term Comp" multiple="multiple" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveNewRFQ">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="updateDateModal" tabindex="-1" role="dialog" aria-labelledby="updateDateLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDateLabel">Update Delivery Date From Principle : <span
                            class="updateDate-principle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateDateForm" class="form-horizontal" action="#">
                        <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="date_delivery" class="col-sm-4 col-form-label">Delivery Date<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_delivery" id="date_delivery"
                                            class="form-control"
                                            value="{{ !empty($sales->delivery_date) ? $sales->delivery_date : date('Y-m-d') }}"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveupdateDate">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="updatePriceFromPrincipleModal" tabindex="-1" role="dialog"
        aria-labelledby="updatePriceFromPrincipleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePriceFromPrincipleLabel">Update Price From Principle : <span
                            class="updatePriceFromPrinciple-principle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updatePriceFromPrincipleForm" class="form-horizontal" action="#">
                        <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="currency" class="col-sm-3 col-form-label">Price From Principle <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <select class="form-control" data-url="{{ URL::to('select2/get-currency') }}"
                                            data-type="currency" name="currency_id" id="currency_id"
                                            data-placeholder="Select Currency" required>
                                            @foreach ($currencies as $key => $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $sales->currency_id ? 'selected' : '' }}>
                                                    {{ $item->currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="price" name="price"
                                            placeholder="Price from Principle" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveupdatePriceFromPrinciple">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="updateDocQuoFromPrincipleModal" tabindex="-1" role="dialog"
        aria-labelledby="updateDocQuoFromPrincipleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDocQuoFromPrincipleLabel">Update Price From Principle : <span
                            class="updateDocQuoFromPrinciple-principle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateDocQuoFromPrincipleForm" class="form-horizontal" action="#"
                        enctype="multipart/form-data"> <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="doc_quo_from_principle" class="col-sm-3 col-form-label">Document <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="doc_quo_from_principle"
                                            name="doc_quo_from_principle" accept=".pdf">
                                        @if ($sales->doc_quo_from_principle)
                                            <p>
                                                <a class="document-link"
                                                    href="{{ asset('storage/' . $sales->doc_quo_from_principle) }}"
                                                    target="_blank">
                                                    <i class="fa fa-file document-icon"></i>
                                                    {{ $sales->doc_quo_from_principle }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveupdateDocQuoFromPrinciple">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="module">
        $(document).ready(function() {
            $('.description-summernote').summernote({
                toolbar: false,
                height: 175,
            });

            $('#collapseTwo').on('hidden.bs.collapse', function() {
                $('#headingTwo .toggle-accordion').removeClass('fa-minus').addClass('fa-plus');
            });
            // Initialize Select2 for the modal if needed
            $('.select2').select2();

            // Handle the "Add More" button click
            $('#addRfqModal').on('click', function() {
                $('#newRFQForm')[0].reset();
                // Clear any validation errors
                // Reset Select2 fields
                $('#newRFQForm').find('select').each(function() {
                    $(this).val(null).trigger('change');
                });
                $('.form-group').removeClass('has-error');
                $('.help-block').text('');
                $('#newRFQModal').modal('show');
            });

            $('#price').on('keyup', function() {
                var value = $(this).val();

                // Remove any characters that are not digits or dots
                value = value.replace(/[^0-9]/g, '');

                if (value.length > 0) {
                    var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    $(this).val(formattedValue);
                } else {
                    $(this).val('');
                }
            });

            $('#principle_ids').select2({
                dropdownParent: $('#newRFQModal'),
                ajax: {
                    url: "{{ route('select2.getCusPrin', ['type' => 'principle']) }}", // Adjust this URL to match your route
                    dataType: 'json',
                    delay: 250,
                    minimumInputLength: 0,
                    placeholder: $(this).data('placeholder'),
                    allowClear: true,
                    width: '100%',
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            $('#delivery_point_ids').select2({
                dropdownParent: $('#newRFQModal'),
                ajax: {
                    url: "{{ route('select2.getDeliveryPoint') }}", // Adjust this URL to match your route
                    dataType: 'json',
                    delay: 250,
                    minimumInputLength: 0,
                    placeholder: $(this).data('placeholder'),
                    allowClear: true,
                    width: '100%',
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            $('#term_comp_ids').select2({
                dropdownParent: $('#newRFQModal'),
                ajax: {
                    url: "{{ route('select2.getTermComp') }}", // Adjust this URL to match your route
                    dataType: 'json',
                    delay: 250,
                    minimumInputLength: 0,
                    placeholder: $(this).data('placeholder'),
                    allowClear: true,
                    width: '100%',
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
            $('.select2-container').css('width', '100%');

            // Handle the form submission
            $('#saveNewRFQ').on('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = $('#newRFQForm').serialize();

                        $.ajax({
                            url: "{{ URL::route('procurement.store-rfq', ['id' => encrypt($sales->id), 'rfq_id' => $rfq_id]) }}", // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                // var response = JSON.parse(response);
                                if (response.status) {
                                    Swal.fire(
                                        'Saved!',
                                        'Your RFQ has been saved.',
                                        'success'
                                    );
                                    $('#rfq-no-title').html('- ' + response.data.rfq_no)

                                    // Clear the form
                                    $('#newRFQForm')[0].reset();

                                    // Hide the modal
                                    $('#newRFQModal').modal('hide');

                                    $('#table-rfq').DataTable().ajax.reload();
                                    window.location.reload();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem saving your RFQ.',
                                        'error'
                                    );
                                }
                            },
                            error: function(error) {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem saving your RFQ.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.proc-update-date', function() {
                // Get the data-id attribute of the clicked button
                var rfqPrincipleId = $(this).data('id');

                // Set the form action to include the id
                var updateUrl = "{{ route('procurement.update-date-delivery', ['id' => ':id']) }}".replace(
                    ':id', rfqPrincipleId);
                $('#updateDateForm').attr('action', updateUrl);
                $('.updateDate-principle').html($(this).data('name'));

                // Show the modal
                $('#updateDateModal').modal('show');
            });

            $(document).on('click', '.proc-update-price-from-principle', function() {
                // Get the data-id attribute of the clicked button
                var rfqPrincipleId = $(this).data('id');

                // Set the form action to include the id
                var updateUrl = "{{ route('procurement.update-price-from-principle', ['id' => ':id']) }}"
                    .replace(':id', rfqPrincipleId);
                $('#updatePriceFromPrincipleForm').attr('action', updateUrl);
                $('.updatePriceFromPrinciple-principle').html($(this).data('name'));

                // Show the modal
                $('#updatePriceFromPrincipleModal').modal('show');
            });

            $(document).on('click', '.proc-update-doc-quo-from-principle', function() {
                // Get the data-id attribute of the clicked button
                var rfqPrincipleId = $(this).data('id');

                // Set the form action to include the id
                var updateUrl = "{{ route('procurement.update-doc-quo-from-principle', ['id' => ':id']) }}"
                    .replace(':id', rfqPrincipleId);
                $('#updateDocQuoFromPrincipleForm').attr('action', updateUrl);
                $('.updateDocQuoFromPrinciple-principle').html($(this).data('name'));

                // Show the modal
                $('#updateDocQuoFromPrincipleModal').modal('show');
            });

            // Handle the form submission
            $(document).on('click', '#saveupdateDate', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = $('#updateDateForm').serialize();
                        var formActionUrl = $('#updateDateForm').attr(
                            'action'); // Get the form action URL

                        $.ajax({
                            url: formActionUrl, // Use the form action URL
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire(
                                        'Saved!',
                                        'Your RFQ Date Delivery has been saved.',
                                        'success'
                                    );

                                    // Clear the form
                                    $('#updateDateForm')[0].reset();

                                    // Hide the modal
                                    $('#updateDateModal').modal('hide');

                                    $('#table-rfq').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem saving your RFQ Date Delivery .',
                                        'error'
                                    );
                                }
                            },
                            error: function(error) {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem saving your RFQ Date Delivery .',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Handle the form submission
            $(document).on('click', '#saveupdatePriceFromPrinciple', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = $('#updatePriceFromPrincipleForm').serialize();
                        var formActionUrl = $('#updatePriceFromPrincipleForm').attr(
                            'action'); // Get the form action URL

                        $.ajax({
                            url: formActionUrl, // Use the form action URL
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire(
                                        'Saved!',
                                        'Your RFQ Date Delivery has been saved.',
                                        'success'
                                    );

                                    // Clear the form
                                    $('#updatePriceFromPrincipleForm')[0].reset();

                                    // Hide the modal
                                    $('#updatePriceFromPrincipleModal').modal('hide');

                                    $('#table-rfq').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem saving your RFQ Date Delivery .',
                                        'error'
                                    );
                                }
                            },
                            error: function(error) {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem saving your RFQ Date Delivery .',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }); // Handle the form submission

            $(document).on('click', '#saveupdateDocQuoFromPrinciple', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData($('#updateDocQuoFromPrincipleForm')[0]);
                        var formActionUrl = $('#updateDocQuoFromPrincipleForm').attr(
                            'action'); // Get the form action URL

                        $.ajax({
                            url: formActionUrl, // Use the form action URL
                            type: 'POST',
                            data: formData,
                            processData: false, // Important: prevent jQuery from automatically processing the data
                            contentType: false, // Important: tell jQuery not to set contentType
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire(
                                        'Saved!',
                                        'Your RFQ Update Doc From Principle has been saved.',
                                        'success'
                                    );

                                    // Clear the form
                                    $('#updateDocQuoFromPrincipleForm')[0].reset();

                                    // Hide the modal
                                    $('#updateDocQuoFromPrincipleModal').modal('hide');

                                    $('#table-rfq').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem saving your RFQ Update Doc From Principle .',
                                        'error'
                                    );
                                }
                            },
                            error: function(error) {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem saving your RFQ Update Doc From Principle .',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script type="module">
        /*================================
                               datatable active
                               ==================================*/
        $(document).ready(function() {
            $('#table-rfq').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('procurement.rfq-list', ['id' => encrypt($sales->id)]) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'principle.name',
                        name: 'principle.name',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                    },
                    {
                        data: 'date_delivery',
                        name: 'date_delivery',
                    },
                    {
                        data: 'price',
                        name: 'price',
                        className: 'text-right',
                    },
                    {
                        data: 'doc_quo_from_principle',
                        name: 'doc_quo_from_principle',
                        className: 'text-center',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            })
        })

        function confirmDelete(deleteUrl) {
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
                            $('#table-rfq').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Deleted!",
                                text: "The principle data has been deleted.",
                                showConfirmButton: false,
                                timer: 1000,
                                icon: "success"
                            });
                        }
                    })
                }
            });
        }

        function confirmWinner(deleteUrl) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, make it winner!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: deleteUrl,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#table-rfq').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Winner updated!",
                                text: "The principle winner data has been updated.",
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
