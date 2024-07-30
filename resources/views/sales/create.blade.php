@extends('layouts.app')

@section('title')
    Tender Create - PT Kencana Zavira
@endsection

@push('after-styles')
    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0;
            /* <-- Apparently some margin are still there even though it's hidden */
        }

        input[type=number] {
            -moz-appearance: textfield;
            /* Firefox */
        }
    </style>
@endpush

@section('breadcrumbs')
<li class="breadcrumb-item">
    Sales
</li>
<li class="breadcrumb-item active">Create Tender</li>
@endsection

@section('content')
<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <form id="tender-form" action="{{ URL::route('sales.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 text-left">
                                <h5>Tender Create</h5>
                                <hr size="8" noshade color="black" style="padding: 0.5px; background-color: #4D7050;" />
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row align-items-center">
                                    <div class="col-sm-3">
                                        <label class="form-label">Tender Type <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="tender_type_non_direct" name="tender_type" value="non_direct" required checked>
                                            <label class="form-check-label" for="tender_type_non_direct">Non Direct</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="tender_type_direct" name="tender_type" value="direct" disabled>
                                            <label class="form-check-label" for="tender_type_direct">Direct</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="tender_type_non_tender" name="tender_type" value="non_tender" disabled>
                                            <label class="form-check-label" for="tender_type_non_tender">Pra Quotation</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_rfq" class="col-sm-3 col-form-label">RFQ <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="no_rfq" name="no_rfq" placeholder="Enter No RFQ" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="name" class="col-sm-3 col-form-label">Tender Name <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Input Tender" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="currency" class="col-sm-3 col-form-label">Tender Budget <span class="text-danger">*</span></label>
                                    <div class="col-sm-3">
                                        <select class="form-select" data-url="{{ URL::to('select2/get-currency') }}" data-type="currency" name="currency_id" id="currency_id" data-placeholder="Select Currency" required>
                                            @foreach ($currencies as $key => $item)
                                                <option value="{{ $item->id }}" {{ $key == 0 ? 'selected' : '' }}>{{ $item->currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="total_price_tender" name="total_price_tender" placeholder="Tender Value" value="0" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="doc_rfq_from_customer" class="col-sm-3 col-form-label">Document <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="doc_rfq_from_customer" accept=".pdf" name="doc_rfq_from_customer" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="tanggal_keluar" class="col-sm-3 col-form-label">Doc Date <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" placeholder="Enter Tanggal Keluar" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="customer_id" class="col-sm-3 col-form-label">Customer <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-select select2-ajax" data-url="{{ URL::to('select2/cusprin/customer') }}" data-type="customer" name="customer_id" id="customer_id" data-placeholder="Select Customer" required></select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="email" class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" readonly>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="note" class="col-sm-3 col-form-label">Note</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="note" name="note" rows="4" placeholder="Enter Note"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="deadline" class="col-sm-3 col-form-label">Deadline <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="datetime-local" class="form-control" id="deadline" name="deadline" placeholder="Enter Deadline" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="status_win_lost" class="col-sm-3 col-form-label">Anggaran/Spek</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="status_win_lost" id="status_win_lost">
                                            <option value="" selected>Yes</option>
                                            <option value="">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-left">
                                <h5>Bank Guarantee</h5>
                                <hr size="8" noshade color="black" style="padding: 0.5px; background-color: #4D7050;" />
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="is_bb_bond" class="col-sm-3 col-form-label">Is Bid Bond</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="is_bb_bond" id="is_bb_bond">
                                            <option value="">To Be Decided</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Additional fields for is_bb_bond -->
                                <div id="bid_bond_fields" style="display: none;">
                                    <div class="mb-3 row">
                                        <label for="bb_no_guarantee" class="col-sm-3 col-form-label">BB No Guarantee <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" value="" id="bb_no_guarantee" name="bb_no_guarantee" placeholder="Enter No Guarantee">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="bb_price" class="col-sm-3 col-form-label">BB Price <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control price-thousand prevent-zero" value="0" value="" id="bb_price" name="bb_price" placeholder="Enter Price">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="bb_note" class="col-sm-3 col-form-label">BB Note <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="bb_note" name="bb_note" rows="3" placeholder="Enter Note"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="bb_time_periode" class="col-sm-3 col-form-label">BB Time Periode <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" id="bb_time_periode" name="bb_time_periode" placeholder="Enter BB Time Periode">
                                        </div>
                                    </div>
                                </div>
                                <!-- End additional fields for is_bb_bond -->
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="is_pb_bond" class="col-sm-3 col-form-label">Is Performance Bond</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="is_pb_bond" id="is_pb_bond">
                                            <option value="">To Be Decided</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Additional fields for is_pb_bond -->
                                <div id="pb_bond_fields" style="display: none;">
                                    <div class="mb-3 row">
                                        <label for="pb_no_guarantee" class="col-sm-3 col-form-label">PB No Guarantee <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" value="" id="pb_no_guarantee" name="pb_no_guarantee" placeholder="Enter No Guarantee">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="pb_price" class="col-sm-3 col-form-label">PB Price <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control price-thousand prevent-zero" value="0" value="" id="pb_price" name="pb_price" placeholder="Enter Price">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="pb_note" class="col-sm-3 col-form-label">PB Note <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="pb_note" name="pb_note" rows="3" placeholder="Enter Note"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="pb_time_periode" class="col-sm-3 col-form-label">PB Time Periode <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" id="pb_time_periode" name="pb_time_periode" placeholder="Enter PB Time Periode">
                                        </div>
                                    </div>
                                </div>
                                <!-- End additional fields for is_pb_bond -->
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 row">
                                    <div class="col-sm-12 text-end">
                                        <a href="{{ URL::route('sales.index') }}" class="btn btn-danger text-light"><i class="fa fa-arrow-left"></i> Cancel</a>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Create Tender</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
</div>
@endsection

@push('after-scripts')
    <script type="module">
        $(document).ready(function() {
            function toggleFields() {
                var is_bb_bond = $('#is_bb_bond').val() === 'true';
                var is_pb_bond = $('#is_pb_bond').val() === 'true';

                // Toggle Bid Bond fields
                if (is_bb_bond) {
                    $('#bid_bond_fields').show();
                    $('#bid_bond_fields').find('input:not([type="file"]), select, textarea').prop('required', true);
                } else {
                    $('#bid_bond_fields').hide();
                    $('#bid_bond_fields').find('input:not([type="file"]), select, textarea').prop('required',
                        false);
                }

                // Toggle Perf Bond fields
                if (is_pb_bond) {
                    $('#pb_bond_fields').show();
                    $('#pb_bond_fields').find('input:not([type="file"]), select, textarea').prop('required', true);
                } else {
                    $('#pb_bond_fields').hide();
                    $('#pb_bond_fields').find('input:not([type="file"]), select, textarea').prop('required', false);
                }
            }

            // Initial check on page load
            toggleFields();

            $('#is_bb_bond').change(function() {
                toggleFields();
            });

            $('#is_pb_bond').change(function() {
                toggleFields();
            });

            $('#total_price_tender').on('keyup', function() {
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
            $('.select2-ajax').each(function(index, element) {
                var url = $(element).data('url');
                var placeholder = $(element).data('placeholder');

                $(element).select2({
                    minimumInputLength: 0,
                    placeholder: placeholder,
                    allowClear: true,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // Search term
                                _type: 'query' // Additional parameters if required by your backend
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.text,
                                        id: item.id,
                                        email: item.email
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    var data = e.params.data;
                    console.log(data);
                    $('#email').val(data.email);
                }).on('select2:clear', function(e) {
                    $('#email').val('');
                });
            });


            $('#draft-button').click(function(event) {
                event.preventDefault();
                showConfirmationDialog('draft');
            });

            $('#submit-button').click(function(event) {
                event.preventDefault();
                showConfirmationDialog('submit');
            });

            function showConfirmationDialog(action) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to proceed with this action?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm(action);
                    }
                });
            }


            function submitForm(action) {
                let valid = true;
                $('#tender-form .form-control[required], #tender-form .form-check-input[required]').each(
                    function() {
                        if (!$(this).val() || ($(this).attr('type') === 'radio' && !$('input[name="' + $(this)
                                .attr('name') + '"]:checked').val())) {
                            valid = false;
                            $(this).addClass('is-invalid');
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                    });

                if (!valid) {
                    Swal.fire({
                        title: "Error",
                        text: "Please fill out all required fields.",
                        icon: "error",
                    });
                    return;
                }

                var formData = new FormData($('#tender-form')[0]);
                formData.append('action', action);
                var url = "{{ URL::route('sales.store') }}";

                $.ajax({
                    url: url, // Replace with your form action URL
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var response = JSON.parse(response);
                        if (response.status) {
                            Swal.fire({
                                title: "Success",
                                text: "Form submitted successfully!",
                                icon: "success",
                            }).then((willRedirect) => {
                                if (willRedirect) {
                                    window.location.href =
                                        "{{ route('sales.detail-tender', ['id' => ':id']) }}"
                                        .replace(':id', response
                                            .id);; // Replace with your redirect URL
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "There was an error submitting the form.",
                                icon: "error",
                            });
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            title: "Error",
                            text: "There was an error submitting the form.",
                            icon: "error",
                        });
                    }
                });
            }
        });
    </script>
@endpush
