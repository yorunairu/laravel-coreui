@extends('layouts.app')

@section('title')
    Material - PT Kencana Zavira
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .v-align-middle {
            vertical-align: middle !important;
        }

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
                    <h4 class="page-title pull-left">Tender Edit Material</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('procurement.index') }}">Procurement</a></li>
                        <li><a href="{{ route('procurement.detail-tender', ['id' => encrypt($sales->id)]) }}">Detail
                                Tender</a></li>
                        <li><span>Edit Material</span></li>
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
                        <h4 class="header-title float-left">
                            {{ !empty(@$sales->tenderRfq->rfqPrinciple) ? @$sales->tenderRfq->rfqPrinciple->firstWhere('status', 'win')->principle->name . ' | ' : '' }}Material
                            List </h4>
                        <div class="float-end mb-2">
                            @if (in_array($sales->status_journey, ['1', '2']))
                                <a class="btn btn-xs btn-primary text-white pull-right mx-1" href="#"
                                    data-toggle="modal" data-target="#newMaterialModal"><i class="fa fa-plus"></i> New
                                    Material</a>
                            @endif
                            @if ($check_price && in_array($sales->status_journey, [3]))
                                <button title="Review Price Tender" type="button"
                                    class="btn btn-xs btn-success mx-1 btn-sm proc-update-review"
                                    data-id="{{ encrypt($sales->id) }}" data-name="{{ $sales->name }}" id="updateReview"><i
                                        class="fa fa-star"></i> Review</button>
                            @endif
                            <a id="tender-log-modal"
                                data-url="{{ URL::route('fetch.get-tender-log', ['id' => encrypt($sales->id)]) }}"
                                href="#" title="History Tender" class="btn btn-xs btn-info mx-1" data-toggle="modal"
                                data-target="#tenderLogModal"><i class="fa fa-history"></i> History Tender</a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table class="table w-100 table-bordered" id="table-material">
                                    <thead class="text-white">
                                        <tr>
                                            <th rowspan="2" class="text-center v-align-middle bg-secondary">No</th>
                                            <th colspan="4" class="text-center v-align-middle bg-primary">Material</th>
                                            <th colspan="2" class="text-center v-align-middle bg-warning">Price Unit</th>
                                            <th colspan="2" class="text-center v-align-middle bg-success">Price Total
                                            </th>
                                            <th rowspan="2" class="text-right v-align-middle bg-danger">Margin</th>
                                        </tr>
                                        <tr>
                                            <th class="text-left v-align-middle bg-primary">Code</th>
                                            <th class="text-left v-align-middle bg-primary">Description</th>
                                            <th class="text-left v-align-middle bg-primary">Qty</th>
                                            <th class="text-left v-align-middle bg-primary">Uom</th>
                                            <th class="text-right v-align-middle bg-warning">Principle</th>
                                            <th class="text-right v-align-middle bg-warning">KZ</th>
                                            <th class="text-right v-align-middle bg-success">Principle</th>
                                            <th class="text-right v-align-middle bg-success">KZ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-secondary text-white">
                                        <tr>
                                            <td colspan="7" class="text-center v-align-middle font-weight-bold">TOTAL
                                            </td>
                                            <td class="text-center v-align-middle font-weight-bold bg-success"></td>
                                            <td class="text-center v-align-middle font-weight-bold bg-success"></td>
                                            <td class="text-center v-align-middle font-weight-bold bg-danger"></td>
                                        </tr>
                                    </tfoot>
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
    <div class="modal fade" id="newMaterialModal" tabindex="-1" role="dialog" aria-labelledby="newMaterialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newMaterialModalLabel">Add New Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="newMaterialForm" class="form-horizontal"
                        action="{{ URL::route('sales.store-material', ['id' => encrypt($sales->id)]) }}">
                        <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <div class="col-sm-5">
                                        <label class="form-label">Take material from master data? <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="material_is_exist_yes" name="material_is_exist"
                                                class="custom-control-input" value="yes" required checked>
                                            <label class="custom-control-label" for="material_is_exist_yes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="material_is_exist_no" name="material_is_exist"
                                                class="custom-control-input" value="no" required>
                                            <label class="custom-control-label" for="material_is_exist_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="materialCode" class="col-sm-4 col-form-label">Material Code</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2-ajax w-100"
                                            data-url="{{ URL::to('select2/get-material') }}" id="materialSelect"
                                            data-placeholder="Pilih Material Code" name="material_code_id"
                                            style="display: none;"></select>
                                        <input type="text" class="form-control" id="materialInput"
                                            name="material_code">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="quantity" class="col-sm-4 col-form-label">Quantity <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control prevent-zero" id="quantity"
                                            name="quantity" value="1" step="1" required
                                            placeholder="Quantity">
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="uom" id="uom" class="form-control"
                                            data-url="{{ URL::to('select2/get-uom') }}" data-placeholder="Satuan">
                                            @foreach ($uom as $key => $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 col-form-label">Description <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea name="description" id="description" class="form-control description-summernote" required
                                            style="height:150px;" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveNewMaterial">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="updatePricePrincipleKzModal" tabindex="-1" role="dialog"
        aria-labelledby="updatePricePrincipleKzLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content updatePricePrincipleKzModal-content">
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

            // Show or hide elements based on the selected radio button
            $('input[name="material_is_exist"]').change(function() {
                if ($(this).val() === 'yes') {
                    $('#materialInput').hide();
                    $('#materialSelect').show().next(".select2-container").show();
                    $('#materialInput').val('');
                    $('#description').prop("readonly", true); // Use prop instead of attr for readonly

                    // Clear and show the Select2 dropdown
                    $('#materialSelect').val(null).trigger('change');
                } else {
                    $('#materialSelect').hide().next(".select2-container").hide();
                    $('#materialInput').show();
                    $('#description').prop("readonly", false); // Enable input for description

                    // Clear and hide the Select2 dropdown
                    $('#materialSelect').val(null).trigger('change');
                }
            });

            // Trigger change event on page load to set the correct initial state
            $('input[name="material_is_exist"]:checked').trigger('change');
            $('#quantity').change(function(e) {
                calculateTotalAmountInput();
            });
            $('#unitPriceKZ').change(function(e) {
                calculateTotalAmountInput();
            });

            function calculateTotalAmountInput() {
                $('#totalAmount').val(($('#quantity').val() * $('#unitPriceKZ').val()))
            }
            $('#collapseTwo').on('shown.bs.collapse', function() {
                $('#headingTwo .toggle-accordion').removeClass('fa-plus').addClass('fa-minus');
            });

            $('#collapseTwo').on('hidden.bs.collapse', function() {
                $('#headingTwo .toggle-accordion').removeClass('fa-minus').addClass('fa-plus');
            });
            // Initialize Select2 for the modal if needed
            $('.select2').select2();

            // Handle the "Add More" button click
            $('#add-form').on('click', function() {
                $('#newMaterialModal').modal('show');
            });

            $('.select2-ajax').each(function(index, element) {
                var url = $(element).data('url');
                var placeholder = $(element).data('placeholder');

                $(element).select2({
                    minimumInputLength: 0,
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $(this).parent(),
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
                                        description: item.description,
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    var data = e.params.data;
                    if (data.description) {
                        $('#description').val(data.description);
                    }

                    // Trigger change in Summernote value
                    var $summernote = $('.description-summernote');
                    $summernote.summernote('focus'); // Focus on Summernote
                    var descriptionValue = $('#description').val(); // Get current value
                    $summernote.summernote('code', descriptionValue); // Set Summernote value
                }).on('select2:clear', function(e) {
                    $('#description').val(''); // Clear description field
                    $(this).val(null).trigger('change'); // Clear Select2 input

                    // Trigger change in Summernote value
                    var $summernote = $('.description-summernote');
                    $summernote.summernote('focus'); // Focus on Summernote
                    var descriptionValue = $('#description').val(''); // Get current value
                    $summernote.summernote('code', descriptionValue); // Set Summernote value
                });
            });

            // Handle the form submission
            $('#saveNewMaterial').on('click', function() {
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
                        var formData = $('#newMaterialForm').serialize();

                        $.ajax({
                            url: "{{ URL::route('sales.store-material', ['id' => encrypt($sales->id)]) }}", // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                var response = JSON.parse(response);
                                if (response.status) {
                                    Swal.fire(
                                        'Saved!',
                                        'Your material has been saved.',
                                        'success'
                                    );

                                    // Clear the form
                                    $('#newMaterialForm')[0].reset();

                                    // Hide the modal
                                    $('#newMaterialModal').modal('hide');

                                    $('#table-material').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem saving your material.',
                                        'error'
                                    );
                                }
                            },
                            error: function(error) {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem saving your material.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.proc-update-price-principle-kz', function() {
                // Get the data-id attribute of the clicked button
                var materialId = $(this).data('id');
                var type = $(this).data('type');
                var url = $(this).data('url');
                $.getJSON(url, {
                        material_id: materialId,
                        type: type
                    },
                    function(data, textStatus, jqXHR) {
                        $('.updatePricePrincipleKzModal-content').html(data.html);

                        $('#updatePricePrincipleKzModal').modal('show');
                    }
                );
                // Show the modal
            });

            // Handle the form submission
            $(document).on('click', '#saveupdatePricePrincipleKz', function() {
                var formData = $('#updatePricePrincipleKzForm').serialize();
                var formActionUrl = $('#updatePricePrincipleKzForm').attr(
                    'action'); // Get the form action URL

                $.ajax({
                    url: formActionUrl, // Use the form action URL
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire(
                                'Saved!',
                                'Your Price Update has been saved.',
                                'success'
                            );

                            // Clear the form
                            $('#updatePricePrincipleKzForm')[0].reset();

                            // Hide the modal
                            $('#updatePricePrincipleKzModal').modal('hide');
                            if (response.reload) {
                                window.location.reload();
                            } else {
                                $('#table-material').DataTable().ajax.reload();
                            }
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was a problem saving your Price Update .',
                                'error'
                            );
                        }
                    },
                    error: function(error) {
                        Swal.fire(
                            'Error!',
                            'There was a problem saving your Price Update .',
                            'error'
                        );
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
            $('#table-material').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                lengthChange: false,
                autoWidth: false,
                ajax: {
                    url: "{{ route('sales.material-list', ['id' => encrypt($sales->id)]) }}",
                    dataSrc: function(json) {
                        // Save totals to use in footerCallback
                        window.totalData = json;
                        console.log('Total Data:', window
                            .totalData); // Debug: Check what data is being received
                        return json.data;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'material_code',
                        name: 'material_code'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'uom.name',
                        name: 'uom.name'
                    },
                    {
                        data: 'unit_price_principle',
                        name: 'unit_price_principle',
                        className: 'text-right'
                    },
                    {
                        data: 'unit_price_kz',
                        name: 'unit_price_kz',
                        className: 'text-right'
                    },
                    {
                        data: 'total_price_principle',
                        name: 'total_price_principle',
                        className: 'text-right'
                    },
                    {
                        data: 'total_price_kz',
                        name: 'total_price_kz',
                        className: 'text-right'
                    },
                    {
                        data: 'total_price_margin',
                        name: 'total_price_margin',
                        className: 'text-right'
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Debugging: Log total values
                    console.log('Total Principle:', window.totalData.totalPrinciple);
                    console.log('Total Principle Other:', window.totalData.totalPrincipleOther);
                    console.log('Total KZ:', window.totalData.totalKz);
                    console.log('Total Margin:', window.totalData.totalMargin);
                    console.log('Total Margin Percentage:', window.totalData.totalMarginPercentage);
                    console.log('Currency:', window.totalData.currency);
                    console.log('Currency Rate:', window.totalData.currencyRate);

                    // Total calculations
                    var totalPrinciple = parseFloat(window.totalData.totalPrinciple) || 0;
                    var totalPrincipleOther = parseFloat(window.totalData.totalPrincipleOther) || 0;
                    var totalKz = parseFloat(window.totalData.totalKz) || 0;
                    var totalMargin = parseFloat(window.totalData.totalMargin) || 0;
                    var totalMarginPercentage = parseFloat(window.totalData.totalMarginPercentage) || 0;
                    var principleCurrency = window.totalData.currency || 'IDR';
                    var principleRate = parseFloat(window.totalData.currencyRate) ||
                        1; // Assuming currency rate is provided

                    function formatCurrency(amount, currency, percentage = null) {
                        let formattedAmount = thousandSeparator(amount) + ' IDR';

                        if (percentage !== null) {
                            // Format percentage with two decimal places
                            let formattedPercentage = parseFloat(percentage).toFixed(2);
                            formattedAmount += ' (' + formattedPercentage + '%)';
                        }

                        return '<span class="badge font-weight-bold">' + formattedAmount + '</span>';
                    }

                    function formatCurrencyWithIDR(amount, currency, amount2, currency) {
                        return currency !== 'IDR' ?
                            '<span class="badge font-weight-bold">' + thousandSeparator(amount2) + ' ' +
                            currency + '<br>' + thousandSeparator(amount) + ' IDR</span>' :
                            '<span class="badge font-weight-bold">' + thousandSeparator(amount) +
                            ' IDR</span>';
                    }

                    // Format Principle with conditional check
                    if (totalPrincipleOther > 0) {
                        $(api.column(7).footer()).html(formatCurrencyWithIDR(totalPrinciple,
                            principleCurrency, totalPrincipleOther, principleCurrency));
                    } else {
                        $(api.column(7).footer()).html(formatCurrency(totalPrinciple,
                            principleCurrency));
                    }

                    // Use formatCurrency for KZ and Margin
                    $(api.column(8).footer()).html(formatCurrency(totalKz, principleCurrency));
                    $(api.column(9).footer()).html(formatCurrency(totalMargin, principleCurrency,
                        totalMarginPercentage));
                }
            });
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
                            $('#table-material').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Deleted!",
                                text: "The material data has been deleted.",
                                showConfirmButton: false,
                                timer: 1000,
                                icon: "success"
                            });
                        }
                    })
                }
            });
        }

        function confirmReview(reviewUrl) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Review this!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: reviewUrl,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#table-material').DataTable().ajax.reload(null,
                                false); // Reload DataTable without resetting page
                            Swal.fire({
                                title: "Change to review!",
                                text: "The material data has been set to review.",
                                showConfirmButton: true,
                                icon: "success"
                            }).then(() => {
                                window.location.href =
                                    '/specified-url'; // Redirect to specified URL after SweetAlert
                            });
                        }
                    });
                }
            });

        }
        $(document).on('click', '.proc-update-review', function() {
            // Get the data-id attribute of the clicked button
            var salesId = $(this).data('id');
            var salesName = $(this).data('name');

            // Show SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                html: `<p>Do you want to proceed with the review for <strong>${salesName}</strong>?</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Construct the update URL
                    var updateUrl = "{{ route('sales.update-review', ['id' => ':id']) }}".replace(':id',
                        salesId);

                    // Perform AJAX request to update review
                    $.ajax({
                        url: updateUrl,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            // Add any additional data you need to send with the request
                        },
                        success: function(response) {
                            Swal.fire(
                                'Reviewed!',
                                '<p>The review has been processed successfully.</p>',
                                'success'
                            ).then(() => {
                                // Redirect to the specified URL
                                window.location.href = response.redirect;
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                '<p>There was an error processing your request.</p>',
                                'error'
                            );
                        }
                    });
                }
            });
        });


        $('#updateReviewForm').submit(function(e) {
            e.preventDefault();
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });

        // Handle the form submission
        $(document).on('click', '#saveupdateReview', function() {
            Swal.fire({
                // title: 'Are you sure?',
                text: "Are you sure to continue this process! This Value (Price KZ )will be generate as Quotation for Customer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = $('#updateReviewForm').serialize();
                    var formActionUrl = $('#updateReviewForm').attr('action'); // Get the form action URL

                    $.ajax({
                        url: formActionUrl, // Use the form action URL
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.status) {
                                Swal.fire(
                                    'Saved!',
                                    'Your Review has been saved.',
                                    'success'
                                );

                                // Clear the form
                                $('#updateReviewForm')[0].reset();

                                // Hide the modal
                                $('#updateReviewModal').modal('hide');
                                if (response.reload) {
                                    window.location.href =
                                        "{{ route('procurement.detail-tender', ['id' => encrypt($sales->id)]) }}";
                                } else {
                                    window.location.href = response.redirect;
                                    $('#table-material').DataTable().ajax.reload();
                                }
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem saving your Review .',
                                    'error'
                                );
                            }
                        },
                        error: function(error) {
                            Swal.fire(
                                'Error!',
                                'There was a problem saving your Review .',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
