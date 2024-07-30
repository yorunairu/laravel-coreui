@extends('layouts.app')

@section('title')
    Tender Create - PT Kencana Zavira
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

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
@endsection

@section('content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center" style="height:50px !important;">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Customer Edit</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('customer.index') }}">Customer</a></li>
                        <li><span>Edit Customer</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
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
                        <h4 class="header-title">Edit Customer</h4>
                        <form id="customer-form" action="{{ URL::route('customer.update', ['id' => $customer->id]) }}">
                            @method('PUT')
                            @csrf
                            {{-- <input type="hidden" name="id" id="id" value="{{ $customer->id }}"> --}}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label required">Customer Name <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            @if ($customer)
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ $customer->name }}">
                                                <!-- Tambahkan input lainnya -->
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="address" class="col-sm-3 col-form-label">Address <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="address"
                                                value="{{ $customer->address }}" name="address" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-3 col-form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ $customer->email }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-3 col-form-label">Phone<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="phone" name="phone"
                                                value="{{ $customer->phone }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="pic_name" class="col-sm-3 col-form-label">PIC Name <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="pic_name" name="pic_name"
                                                value="{{ $customer->pic_name }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email_pic" class="col-sm-3 col-form-label">Email PIC <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="email_pic" class="form-control" id="email_pic" name="email_pic"
                                                value="{{ $customer->email_pic }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="position" class="col-sm-3 col-form-label">Position <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="position" name="position"
                                                value="{{ $customer->position }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button id="back-button" type="submit" class="btn btn-success"><i
                                    class="ti-angle-double-left"></i> Back</button>
                            <button id="submit-button" type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                Save</button>
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
            $('#back-button').on('click', function() {
                window.location.href = "{{ route('customer.index') }}"
            })
            $('#customer-form').on('submit', function(e) {
                let valid = true;
                $('#customer-form .form-control[required], #customer-form .form-check-input[required]')
                    .each(function() {
                        if (!$(this).val() || ($(this).attr('type') === 'radio' && !$('input[name="' +
                                $(this).attr('name') + '"]:checked').val())) {
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
                e.preventDefault();
                var url = "{{ URL::route('customer.update', ['id' => encrypt($customer->id)]) }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        let message = response.message
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
                                Swal.fire({
                                    title: "Success",
                                    text: message,
                                    icon: "success",
                                }).then((willRedirect) => {
                                    if (willRedirect) {
                                        window.location.href =
                                            "{{ route('customer.index') }}";
                                    }
                                });
                            }
                        });
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            alert(response.responseJSON.errors);
                        } else {
                            console.log(response.responseJSON.errors);
                            alert('An error occurred.');
                        }
                    }
                });
            });
        })
    </script>
@endpush
