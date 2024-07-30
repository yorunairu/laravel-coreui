@extends('layouts.app')

@section('title')
    Tender Create
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-check-label {
            text-transform: capitalize;
        }
    </style>
@endsection

@section('content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center" style="height:50px !important;">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Tender Create</h4>
                    <ul class="breadcrumbs pull-left">
                        {{-- <li><a href="{{ route('dashboard') }}">Dashboard</a></li> --}}
                        <li><a href="{{ route('procurement.index') }}">Procurement</a></li>
                        <li><span>Create RFQ</span></li>
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
                        <h4 class="header-title">Create RFQ</h4>
                        {{-- @include('layouts.includes.messages') --}}

                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label required">RFQ Number <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control required" id="name"
                                                name="name" placeholder="Enter Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tender_name" class="col-sm-3 col-form-label">Tender Name <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" aria-label="Default select example" name="tender"
                                                id="tender">
                                                <option selected>Select Tender</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="customer" class="col-sm-3 col-form-label">Customer <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" placeholder="auto field">
                                        </div>
                                    </div>

                                    {{-- <div class="form-group row">
                                    <label for="file" class="col-sm-3 col-form-label">Document <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="file" name="file" placeholder="Enter Password">
                                    </div>
                                </div> --}}
                                </div>
                                <div class="col-md-6">
                                    {{-- <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                                    </div>
                                </div> --}}

                                    {{-- <div class="form-group row">
                                    <label for="text" class="col-sm-2 col-form-label">Text <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="text" name="text" rows="4" placeholder="Enter text"></textarea>
                                    </div>
                                </div> --}}

                                    <div class="form-group row">
                                        <label for="deadline" class="col-sm-2 col-form-label">Date <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="deadline" name="deadline"
                                                placeholder="Enter Deadline">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status" class="col-sm-2 col-form-label">Text <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="text" name="text">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status" class="col-sm-2 col-form-label">Note <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="text" name="text"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>

                            <button type="submit" class="btn btn-success mt-4 pr-4 pl-4">Save as Draft</button>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- data table end -->
        </div>
    </div>
@endsection

@push('after-scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="module">
    $(document).ready(function() {
        $('.select2').select2();
    })
</script> --}}
@endsection
