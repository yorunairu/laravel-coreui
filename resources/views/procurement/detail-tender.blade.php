@extends('layouts.app')

@section('title')
    Detail Tender - PT Kencana Zavira
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('custom/tender-detail.css') }}">
    <style>
    </style>
@endsection

@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    {{-- @php
    dd($sales)
@endphp --}}
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center" style="height:50px !important;">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Detail Tender</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('procurement.index') }}">Procurement</a></li>
                        <li><span>{{ $sales->name }}</span></li>
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
            <div class="col-md-12">
                <div class="progress-indicator-container">
                    <div class="clearfix"></div>
                    <ul class="progress-indicator custom-complex">
                        @foreach ($status_journey as $key => $item)
                            <li class="{{ $sales->status_journey > $item->order ? 'completed' : '' }}">
                                <span
                                    class="bubble {{ $sales->status_journey == $item->order ? 'progress-bar-striped progress-bar-animated bg-primary' : '' }}"></span>
                                <h6>
                                    @if ($sales->status_journey > $item->order)
                                        <i class="fa fa-check-circle"></i>
                                    @endif
                                    {{ $item->name }}
                                </h6>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-md-9 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 text-left">
                                        <h5>Tender Detail</h5>
                                        <hr size="8" noshade color="black"
                                            style="padding: 0.5px; background-color: #4D7050;" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <div class="col-sm-3">
                                        <label class="form-label">Tender Type <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="tender_type_non_direct" name="tender_type"
                                                class="custom-control-input" value="non_direct" required disabled
                                                {{ $sales->tender_type == 'non_direct' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="tender_type_non_direct">Non
                                                Direct</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="tender_type_direct" name="tender_type"
                                                class="custom-control-input" value="direct" required disabled
                                                {{ $sales->tender_type == 'direct' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="tender_type_direct">Direct</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="tender_type_non_tender" name="tender_type"
                                                class="custom-control-input" value="non_tender" required disabled
                                                {{ $sales->tender_type == 'non_tender' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="tender_type_non_tender">Non
                                                Tender</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no_rfq" class="col-sm-3 col-form-label required disabled">RFQ <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" value="{{ $sales->no_rfq }}" class="form-control"
                                            id="no_rfq" name="no_rfq" placeholder="Enter No RFQ" required disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Tender Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" value="{{ $sales->name }}" class="form-control"
                                            id="name" name="name" placeholder="Input Tender" required disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="currency" class="col-sm-3 col-form-label">Tender Value <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-3">
                                        <select class="form-control" data-url="{{ URL::to('select2/get-currency') }}"
                                            data-type="currency" name="currency_id" id="currency_id"
                                            data-placeholder="Select Currency" required disabled>
                                            @foreach ($currencies as $key => $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $sales->currency_id ? 'selected' : '' }}>
                                                    {{ $item->currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" value="{{ thousandSeparator($sales->total_price_tender) }}"
                                            class="form-control" id="total_price_tender" name="total_price_tender"
                                            placeholder="Tender Value" required disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="doc_rfq_from_customer" class="col-sm-3 col-form-label">Document <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        @if ($sales->doc_rfq_from_customer)
                                            <p>
                                                <a class="document-link"
                                                    href="{{ asset('storage/' . $sales->doc_rfq_from_customer) }}"
                                                    target="_blank">
                                                    <i class="fa fa-file document-icon"></i>
                                                    {{ $sales->doc_rfq_from_customer }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal_keluar" class="col-sm-3 col-form-label">Doc Date<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="tanggal_keluar"
                                            name="tanggal_keluar" placeholder="Enter Tanggal Keluar"
                                            value="{{ date('Y-m-d', strtotime($sales->tanggal_keluar)) }}" required
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="customer_id" class="col-sm-3 col-form-label">Customer <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2-ajax w-100"
                                            data-url="{{ URL::to('select2/cusprin/customer') }}" data-type="customer"
                                            name="customer_id" id="customer_id" data-placeholder="Select Customer"
                                            required disabled>
                                            <option value="{{ $sales->customer->id }}">{{ $sales->customer->name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $sales->email }}" placeholder="Enter Email" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="note" class="col-sm-3 col-form-label">Note <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="note" name="note" rows="4" placeholder="Enter Note" required
                                            disabled>{{ $sales->notes }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="deadline" class="col-sm-3 col-form-label">Deadline <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="datetime-local" class="form-control" id="deadline" name="deadline"
                                            placeholder="Enter Deadline" value="{{ $sales->deadline }}" required
                                            disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status_win_lost" class="col-sm-3 col-form-label">Anggaran/Spek</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="status_win_lost" id="status_win_lost"
                                            disabled>
                                            <option value=""
                                                {{ $sales->status_win_lost == null ? 'selected' : '' }}>
                                                Yes</option>
                                            <option value="lost"
                                                {{ $sales->status_win_lost == 'lost' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($sales->status_journey >= 4)
                                    <div class="form-group row align-items-center">
                                        <div class="col-sm-3">
                                            <label class="form-label">Win / lost <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="win_lost_null" name="win_lost"
                                                    class="custom-control-input" value="" required
                                                    {{ $sales->win_lost == '' ? 'checked' : '' }} disabled>
                                                <label class="custom-control-label" for="win_lost_null">TBD</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="win_lost_win" name="win_lost"
                                                    class="custom-control-input" value="win" required
                                                    {{ $sales->win_lost == 'win' ? 'checked' : '' }} disabled>
                                                <label class="custom-control-label" for="win_lost_win">Win</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="win_lost_lost" name="win_lost"
                                                    class="custom-control-input" value="lost" required
                                                    {{ $sales->win_lost == 'lost' ? 'checked' : '' }} disabled>
                                                <label class="custom-control-label" for="win_lost_lost">Lost</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 text-left">
                                        <h5>Bank Guarantee</h5>
                                        <hr size="8" noshade color="black"
                                            style="padding: 0.5px; background-color: #4D7050;" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="is_bb_bond" class="col-sm-3 col-form-label">Is Bid Bond</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="is_bb_bond" id="is_bb_bond" disabled>
                                            <option value="">To Be Decided</option>
                                            <option value="true" {{ $sales->is_bb_bond === 'true' ? 'selected' : '' }}>
                                                Yes</option>
                                            <option value="false" {{ $sales->is_bb_bond === 'false' ? 'selected' : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Additional fields for is_bb_bond -->
                                <div id="bid_bond_fields"
                                    {{ !empty($sales->is_bb_bond) ? '' : 'style="display: none;"' }}>
                                    <div class="form-group row">
                                        <label for="bb_no_guarantee" class="col-sm-3 col-form-label">BB No Guarantee <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input disabled type="text" class="form-control"
                                                value="{{ @$sales->bgGuarantee->where('type', 'bb')->first()->no_guarantee }}"
                                                id="bb_no_guarantee" name="bb_no_guarantee"
                                                placeholder="Enter No Guarantee">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bb_price" class="col-sm-3 col-form-label">BB Price <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input disabled type="text" class="form-control price-thousand"
                                                value="{{ @$sales->bgGuarantee->where('type', 'bb')->first()->price }}"
                                                id="bb_price" name="bb_price" placeholder="Enter Price">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bb_note" class="col-sm-3 col-form-label">BB Note <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea disabled class="form-control" id="bb_note" name="bb_note" rows="3" placeholder="Enter Note">{{ @$sales->bgGuarantee->where('type', 'bb')->first()->note }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bb_time_periode" class="col-sm-3 col-form-label">BB Periode to <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input disabled type="date" class="form-control"
                                                value="{{ !empty($bid_bond->time_period) ? date('Y-m-d', strtotime(@$sales->bgGuarantee->where('type', 'bb')->first()->time_period)) : date('Y-m-d') }}"
                                                id="bb_time_periode" name="bb_time_periode"
                                                placeholder="Enter Time Periode">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bb_doc_bg" class="col-sm-3 col-form-label">Document Bid Bond <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input disabled type="file" class="form-control" id="bb_doc_bg"
                                                name="bb_doc_bg">
                                            @if (@$sales->bgGuarantee->where('type', 'bb')->first()->doc_bg)
                                                <p>
                                                    <a class="document-link"
                                                        href="{{ asset('storage/' . @$sales->bgGuarantee->where('type', 'bb')->first()->doc_bg) }}"
                                                        target="_blank">
                                                        <i class="fa fa-file document-icon"></i>
                                                        {{ @$sales->bgGuarantee->where('type', 'bb')->first()->doc_bg }}
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- is_pb_bond field -->
                                <div class="form-group row">
                                    <label for="is_pb_bond" class="col-sm-3 col-form-label">Is Perf Bond</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="is_pb_bond" id="is_pb_bond" disabled>
                                            <option value="">To Be Decided</option>
                                            <option value="true" {{ $sales->is_pb_bond === 'true' ? 'selected' : '' }}>
                                                Yes</option>
                                            <option value="false" {{ $sales->is_pb_bond === 'false' ? 'selected' : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Additional fields for is_pb_bond -->
                                <div id="pb_bond_fields" {{ !empty($sales->is_pb_bond) ? '' : 'style="display: none;"' }}>
                                    <div class="form-group row">
                                        <label for="pb_no_guarantee" class="col-sm-3 col-form-label">No Guarantee <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input disabled type="text" class="form-control"
                                                value="{{ @$sales->bgGuarantee->where('type', 'pb')->first()->no_guarantee }}"
                                                id="pb_no_guarantee" name="pb_no_guarantee"
                                                placeholder="Enter No Guarantee">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pb_price" class="col-sm-3 col-form-label">Price <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input disabled type="text" class="form-control price-thousand"
                                                value="{{ @$sales->bgGuarantee->where('type', 'pb')->first()->price }}"
                                                id="pb_price" name="pb_price" placeholder="Enter Price">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pb_note" class="col-sm-3 col-form-label">Note <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea disabled class="form-control" id="pb_note" name="pb_note" rows="3" placeholder="Enter Note">{{ @$sales->bgGuarantee->where('type', 'pb')->first()->note }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pb_time_periode" class="col-sm-3 col-form-label">Time Periode <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input disabled type="date" class="form-control"
                                                value="{{ !empty($pb_bond->time_period) ? date('Y-m-d', strtotime(@$sales->bgGuarantee->where('type', 'pb')->first()->time_period)) : date('Y-m-d') }}"
                                                id="pb_time_periode" name="pb_time_periode"
                                                placeholder="Enter Time Periode">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pb_doc_bg" class="col-sm-3 col-form-label">Document Performa Bond
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input disabled type="file" class="form-control" id="pb_doc_bg"
                                                name="pb_doc_bg">
                                            @if (@$sales->bgGuarantee->where('type', 'pb')->first()->doc_bg)
                                                <p>
                                                    <a class="document-link"
                                                        href="{{ asset('storage/' . @$sales->bgGuarantee->where('type', 'pb')->first()->doc_bg) }}"
                                                        target="_blank">
                                                        <i class="fa fa-file document-icon"></i>
                                                        {{ @$sales->bgGuarantee->where('type', 'pb')->first()->doc_bg }}
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-left">
                            <div class="row">
                                <div class="col-md-12 text-left">
                                    <h5>Action List</h5>
                                    <hr size="8" noshade color="black"
                                        style="padding: 0.5px; background-color: #a0a0a0;" />
                                </div>
                            </div>
                            @if (in_array($sales->statusJourney->order, [0, 1, 4]))
                                <a href="{{ route('procurement.edit', ['id' => encrypt($sales->id), 'type' => 'tender']) }}?is_detail_tender=true"
                                    title="Edit Tender" class="btn btn-xs btn-warning m-1" target="_blank"><i
                                        class="fa fa-pencil"></i> Edit Tender</a><br>
                            @endif
                            @if (in_array($sales->statusJourney->order, [1, 3, 4]))
                                <a href="{{ route('procurement.edit', ['id' => encrypt($sales->id), 'type' => 'material']) }}"
                                    title="Edit Material" class="btn btn-xs btn-warning m-1" target="_blank"><i
                                        class="fa fa-shopping-cart"></i> Edit Material</a><br>
                            @endif
                            @if (in_array($sales->statusJourney->order, [0, 1]) && !empty($sales->tenderMaterial->count()))
                                <button type="button" title="Submit to Procurement"
                                    class="btn btn-xs text-white m-1 btn-primary"
                                    onclick="confirmFinal('{{ route('procurement.final', encrypt($sales->id)) }}')"><i
                                        class="fa fa-send-o"></i> Submit to Procurement</button><br>
                            @endif
                            @if (in_array($sales->statusJourney->order, [2, 3, 4]))
                                <a href="{{ route('procurement.detail', ['id' => encrypt($sales->id)]) }}"
                                    class="btn btn-xs btn-warning m-1"><i class="fa fa-building-o"></i> Edit RFQ to
                                    Principle</a><br>
                            @endif

                            @if ($check_price && in_array($sales->status_journey, ['3']))
                                <a href="{{ route('procurement.edit', ['id' => encrypt($sales->id), 'type' => 'material']) }}"
                                    title="Review" class="btn btn-xs btn-success m-1" target="_blank"><i
                                        class="fa fa-star"></i> Review</a><br>
                            @endif
                            @if ($check_price && in_array($sales->status_journey, ['4']))
                                <button title="Quotation to Customer" type="button"
                                    class="btn btn-xs btn-danger m-1 generate-quotation-customer {{ $is_null_bid ? 'bid-warning' : '' }}"
                                    data-id="{{ encrypt($sales->id) }}" data-name="{{ $sales->name }}"
                                    id="generate-quotation-customer"><i class="fa fa-file-pdf-o"></i> Quotation to
                                    Customer</button><br>
                            @endif
                            {{-- PROCESS TO PP --}}
                            @if (in_array($sales->statusJourney->order, ['4']))
                                <button title="procced to po" type="button"
                                    class="btn btn-xs btn-primary m-1 procced-to-po {{ $is_null_bid ? 'bid-warning' : '' }}"
                                    data-id="{{ encrypt($sales->id) }}" data-name="{{ $sales->name }}"
                                    id="procced-to-po">
                                    <i class="fa fa-spinner"></i> Procced to PO
                                </button>
                            @endif
                            {{-- END PROCESS TO PO --}}

                            {{-- PROCESS PO TO PRINCIPLE --}}
                            @if (in_array($sales->statusJourney->order, ['5', '6']))
                                <button title="po to principle" type="button"
                                    class="btn btn-xs btn-danger m-1 po-to-principle" data-id="{{ encrypt($sales->id) }}"
                                    data-name="{{ $sales->name }}" id="po-to-principle"><i
                                        class="fa fa-file-pdf-o"></i> PO To Principle</button><br>
                            @endif
                            {{-- END PROCESS PO TO PRINCIPLE --}}
                            @if (in_array($sales->statusJourney->order, [0, 1]))
                                <button type="button" title="Delete" class="btn btn-xs m-1 text-white btn-danger"
                                    onclick="confirmDelete('{{ route('procurement.destroy', encrypt($sales->id)) }}')"><i
                                        class="fa fa-trash"></i> Delete Tender</button><br>
                            @endif
                            <a id="tender-log-modal"
                                data-url="{{ URL::route('fetch.get-tender-log', ['id' => encrypt($sales->id)]) }}"
                                href="#" title="History Tender" class="btn btn-xs text-white btn-info m-1"
                                data-toggle="modal" data-target="#tenderLogModal"><i class="fa fa-history"></i> History
                                Tender</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5>PO Customer</h5>
                        <hr size="8" noshade color="black" style="padding: 0.5px; background-color: #a0a0a0;" />
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table class="table w-100 table-striped table-hover table-bordered"
                                    id="table-customer-po">
                                    <thead class="bg-success text-white">
                                        <th class="text-center v-align-middle">No</th>
                                        <th class="text-left v-align-middle">Tender Name</th>
                                        <th class="text-center v-align-middle">No PO</th>
                                        <th class="text-left v-align-middle">Date</th>
                                        <th class="text-left v-align-middle">By</th>
                                        <th class="text-right v-align-middle">Notes</th>
                                        <th class="text-left v-align-middle">Doc</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Principle List</h5>
                        <hr size="8" noshade color="black" style="padding: 0.5px; background-color: #a0a0a0;" />
                        <div class="data-tables">
                            @include('layouts.includes.messages')
                            <div class="table-responsive">
                                <table class="table w-100 table-striped table-hover table-bordered" id="table-rfq">
                                    <thead class="bg-success text-white">
                                        <th class="text-center v-align-middle">No</th>
                                        <th class="text-left v-align-middle">Principle</th>
                                        <th class="text-center v-align-middle">Status</th>
                                        <th class="text-left v-align-middle">Reviewed by</th>
                                        <th class="text-left v-align-middle">Reviewed at</th>
                                        <th class="text-left v-align-middle">Date Delivery</th>
                                        <th class="text-right v-align-middle">Price</th>
                                        <th class="text-right v-align-middle">Doc</th>
                                        <th class="text-right v-align-middle">Doc PO</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <h5>{{ !empty(@$sales->tenderRfq->rfqPrinciple) ? @$sales->tenderRfq->rfqPrinciple->firstWhere('status', 'win')->principle->name . ' | ' : '' }}Material
                            List </h5>
                        <hr size="8" noshade color="black" style="padding: 0.5px; background-color: #a0a0a0;" />
                        <div class="data-tables">
                            <div class="float-end mb-2">
                            </div>
                            <div class="clearfix"></div>
                            <div class="data-tables">
                                @include('layouts.includes.messages')
                                <div class="table-responsive">
                                    <table class="table w-100 table-bordered" id="table-material">
                                        <thead class="text-white">
                                            <tr>
                                                <th rowspan="2" class="text-center v-align-middle bg-secondary">No</th>
                                                <th colspan="4" class="text-center v-align-middle bg-primary">Material
                                                </th>
                                                <th colspan="2" class="text-center v-align-middle bg-warning">Price
                                                    Unit</th>
                                                <th colspan="2" class="text-center v-align-middle bg-success">Price
                                                    Total</th>
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
                                                <td colspan="7" class="text-center v-align-middle font-weight-bold">
                                                    TOTAL</td>
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
    </div>
    @include('tender-logs.index')

    <!-- Modal HTML -->
    <div class="modal fade" id="updateReviewModal" tabindex="-1" role="dialog" aria-labelledby="updateReviewLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateReviewLabel">Review Price</span> : <span
                            class="updateReview-principle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateReviewForm" class="form-horizontal" action="#">
                        <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="currency" class="col-sm-3 col-form-label">Total Price From
                                        Principle</span> <span class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <select class="form-control" data-url="{{ URL::to('select2/get-currency') }}"
                                            data-type="currency" name="currency_id" id="currency_id"
                                            data-placeholder="Select Currency" readonly>
                                            <option value="{{ @$price['id'] }}" selected>{{ @$price['text'] }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control updatePriceType-input price-thousand"
                                            id="price" name="price" placeholder="Price from Principle" readonly
                                            value="{{ thousandSeparator(@$priceSum['Principle']) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="currency" class="col-sm-3 col-form-label">Total Price From KZ</span> <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <select class="form-control" data-url="{{ URL::to('select2/get-currency') }}"
                                            data-type="currency" name="currency_id" id="currency_id"
                                            data-placeholder="Select Currency" readonly>
                                            <option value="{{ @$price['id'] }}" selected>{{ @$price['text'] }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control updatePriceType-input price-thousand"
                                            id="price" name="price" placeholder="Price from KZ" readonly
                                            value="{{ thousandSeparator(@$priceSum['KZ']) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="currency" class="col-sm-3 col-form-label">Margin & Percentage</span> <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <select class="form-control" data-url="{{ URL::to('select2/get-currency') }}"
                                            data-type="currency" name="currency_id" id="currency_id"
                                            data-placeholder="Select Currency" readonly>
                                            <option value="{{ @$price['id'] }}" selected>{{ @$price['text'] }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control updatePriceType-input price-thousand"
                                            id="price" name="price" placeholder="Price from KZ" readonly
                                            value="{{ thousandSeparator($margin) . ' (' . $percentageMargin . '%)' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveupdateReview">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL QUOTATION TO CUSTOMER -->
    <div class="modal fade" id="quotationPDFModal" tabindex="-1" role="dialog"
        aria-labelledby="quotationPDFModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quotationPDFModalLabel">RFQ : {{ $sales->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="quoPDF" class="form-horizontal"
                        action="{{ route('sales.quotation-to-customer-pdf', ['id' => encrypt($sales->id)]) }}"
                        target="_blank"> <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="delivery_point" class="col-sm-4 col-form-label">Delivery Point <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="delivery_point[]" id="delivery_point"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getDeliveryPoint') }}"
                                            data-placeholder="Select delivery point" multiple="multiple" required>
                                            @foreach ($termsType1 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="additional_value" class="col-sm-4 col-form-label">Additional Value <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="additional_value[]" id="additional_value"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'additional_value', 'category' => 'quo']) }}"
                                            data-placeholder="Additional Value" multiple="multiple" required>
                                            @foreach ($termsType2 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="price_validity" class="col-sm-4 col-form-label">Price Validity <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="price_validity[]" id="price_validity"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'price_validity', 'category' => 'quo']) }}"
                                            data-placeholder="Price Validity" multiple="multiple" required>
                                            @foreach ($termsType3 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="delivery_time" class="col-sm-4 col-form-label">Delivery Time <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="delivery_time[]" id="delivery_time"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'delivery_time', 'category' => 'quo']) }}"
                                            data-placeholder="Delivery Time" multiple="multiple" required>
                                            @foreach ($termsType4 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="payment_terms" class="col-sm-4 col-form-label">Payment Terms <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="payment_terms[]" id="payment_terms"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'payment_terms', 'category' => 'quo']) }}"
                                            data-placeholder="Payment Terms" multiple="multiple" required>
                                            @foreach ($termsType5 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="payment_terms" class="col-sm-4 col-form-label">Terms <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="terms[]" id="terms" class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'term', 'category' => 'quo']) }}"
                                            data-placeholder="Payment Terms" multiple="multiple">
                                            @foreach ($terms as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="note" class="col-sm-4 col-form-label">Note<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea name="note" id="note" class="form-control description-summernote" style="height:150px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close</button>
                        <button type="submit" class="btn btn-xs btn-primary" id="saveNewRFQ"><i
                                class="fa fa-file-pdf-o"></i> Print to PDF</button>
                        <button title="Mail Quotation to Sales" type="button"
                            class="btn btn-xs btn-success m-1 {{ $is_null_bid ? 'bid-warning' : '' }}"
                            onclick="confirmAndSendQuo('{{ route('procurement.send-quo', ['id' => encrypt($sales->id), 'role' => 'sales']) }}')"><i
                                class="fa fa-envelope"></i> Mail to Sales</button>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PO TO PRINCIPLE -->
    <div class="modal fade" id="poToPrinciple" tabindex="-1" role="dialog" aria-labelledby="newPoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPoModalLabel">PDF PO To Principle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="tender-po-principle"
                        action="{{ URL::route('procurement.po-to-principle-pdf', ['id' => encrypt($sales->id)]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="principle_po_no" class="col-sm-3 col-form-label">No PO <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text"class="form-control" id="principle_po_no"
                                            value="{{ $poNumber }}" name="principle_po_no" readonly required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="principle" class="col-sm-3 col-form-label required">Principle <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="principle" name="principle"
                                            value="{{ $data->first()->principle->name ?? 'N/A' }}" readonly required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no_rfq" class="col-sm-3 col-form-label">No RfQ <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="no_rfq" name="no_rfq"
                                            value="{{ $data->first()->tenderRfq->rfq_no ?? 'N/A' }}"
                                            placeholder="Input no PO" readonly required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="delivery_point" class="col-sm-3 col-form-label">Delivery Point <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="delivery_point[]" id="delivery_point"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getDeliveryPoint') }}"
                                            data-placeholder="Select delivery point" multiple="multiple" required>
                                            @foreach ($termsType1 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="additional_value" class="col-sm-3 col-form-label">Additional Value <span
                                            class="text-danger"></span></label>
                                    <div class="col-sm-9">
                                        <select name="additional_value[]" id="additional_value"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'additional_value', 'category' => 'quo']) }}"
                                            data-placeholder="Additional Value" multiple="multiple" required>
                                            @foreach ($termsType2 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="complete_with" class="col-sm-3 col-form-label">Complete with <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="complete_with[]" id="complete_with"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'complete_with', 'category' => 'quo']) }}"
                                            data-placeholder="complete with" multiple="multiple" required>
                                            @foreach ($termsType6 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="delivery_time" class="col-sm-3 col-form-label">Delivery Time <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="delivery_time[]" id="delivery_time"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'delivery_time', 'category' => 'quo']) }}"
                                            data-placeholder="Delivery Time" multiple="multiple" required>
                                            @foreach ($termsType4 as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="payment_terms" class="col-sm-3 col-form-label">Payment Terms <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="payment_terms[]" id="payment_terms"
                                            class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'payment_terms', 'category' => 'quo']) }}"
                                            data-placeholder="Payment Terms" multiple="multiple" required>
                                            @foreach ($termsType5 as $term)
                                                <option value="{{ $term->term_id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="payment_terms" class="col-sm-3 col-form-label">Terms <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="terms[]" id="terms" class="form-control select2-ajax"
                                            data-url="{{ route('select2.getTermComp', ['type' => 'term', 'category' => 'quo']) }}"
                                            data-placeholder="Payment Terms" multiple="multiple">
                                            @foreach ($terms as $term)
                                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="savePoPrinciple"><i class="fa fa-file-pdf-o"></i>
                        Print to PDF</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL PO TO PRINCIPLE -->

    {{-- MODAL PROCCED PO --}}
    <div class="modal fade" id="proccedPo" tabindex="-1" role="dialog" aria-labelledby="newPoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Add modal-lg class for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPoModalLabel">Input PO Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="tender-po-customer"
                        action="{{ URL::route('procurement.po-customer', ['id' => encrypt($sales->id)]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="trx_tender_id" class="col-sm-3 col-form-label required">Tender Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="trx_tender_id"
                                            name="trx_tender_id" value="{{ $sales->name }}" readonly required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="customer_po_no" class="col-sm-3 col-form-label">No PO <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="customer_po_no"
                                            name="customer_po_no" placeholder="Input no PO" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="customer_po_date" class="col-sm-3 col-form-label">Date PO <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="date"class="form-control" id="customer_po_date"
                                            name="customer_po_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="customer_po_doc" class="col-sm-3 col-form-label">Document <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="file"class="form-control" id="customer_po_doc"
                                            name="customer_po_doc" placeholder="Upload doc" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="customer_po_note" class="col-sm-3 col-form-label">Note <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="customer_po_note" name="customer_po_note" rows="4"
                                            placeholder="Input Note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="savePoCustomer"><i class="fa fa-save"></i>
                        Submit</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END PROCCED PO --}}

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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePricePrincipleKzLabel">Update Price From <span
                            class="updatePriceType"></span> : <span class="updatePricePrincipleKz-principle"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updatePricePrincipleKzForm" class="form-horizontal" action="#">
                        <!-- Add form-horizontal class for better layout -->
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="currency" class="col-sm-3 col-form-label">Price From <span
                                            class="updatePriceType"></span> <span class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <select class="form-control" data-url="{{ URL::to('select2/get-currency') }}"
                                            data-type="currency" name="currency_id" id="currency_id"
                                            data-placeholder="Select Currency" readonly required>
                                            <option value="{{ @$price['id'] }}" selected>{{ @$price['text'] }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control updatePriceType-input price-thousand"
                                            id="price" name="price" placeholder="Price from Principle" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveupdatePricePrincipleKz">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="module">
        // PROCESS CREATE PO CUSTOMER

        $(document).ready(function() {
            $('#savePoCustomer').click(function(e) {
                e.preventDefault();
                var form = $('#tender-po-customer')[0];
                var formData = new FormData(form);

                $.ajax({
                    url: $('#tender-po-customer').attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'PO Principle has been saved.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.href =
                                '{{ route('procurement.detail-tender', ['id' => encrypt($sales->id)]) }}';
                            // window.open(response.pdf_url, '_blank');
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error saving the PO Principle.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
        $(document).ready(function() {
            $('#savePoPrinciple').click(function(e) {
                e.preventDefault();
                var form = $('#tender-po-principle')[0];
                var formData = new FormData(form);

                $.ajax({
                    url: $('#tender-po-principle').attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'PO Principle has been saved.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // window.location.href = '{{ route('procurement.po-to-principle-pdf', ['id' => encrypt($sales->id)]) }}';
                            window.open(response.pdf_url, '_blank');
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error saving the PO Principle.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
        // END PROCESS CREATE PO CUSTOMER

        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                dropdownAutoWidth: true,
                allowClear: true,
                width: '100%',
            });
            $('#procced-to-po').on('click', function() {
                $('#proccedPo').modal('show');
                if ($(this).hasClass('bid-warning')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning !',
                        html: 'Bid Bond should be filled in <a target="_blank" href="{{ route('sales.edit', ['id' => encrypt($sales->id), 'type' => 'tender']) }}" class="btn btn-xs btn-warning text-white"><i class="fa fa-pencil"></i> Edit Tender</a> first.'
                    });
                } else {}
            });
            $(document).ready(function() {
                $('#savePoCustomer').click(function(e) {
                    e.preventDefault();
                    var form = $('#tender-po-customer')[0];
                    var formData = new FormData(form);

                    $.ajax({
                        url: $('#tender-po-customer').attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'PO Customer has been saved.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                window.location.href =
                                    '{{ route('procurement.detail-tender', ['id' => encrypt($sales->id)]) }}';
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error saving the PO Customer.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            });
            // Fungsi untuk mengisi nilai default pada select elements
            function setDefaultValues() {
                $.ajax({
                    url: '{{ route('default.values') }}', // Ganti dengan route yang sesuai
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('default value: ', data);
                        // Mengisi default value untuk setiap select element dengan nilai pertama
                        $('#delivery_point').val(data.delivery_point).trigger('change');
                        $('#additional_value').val(data.additional_value).trigger('change');
                        $('#price_validity').val(data.price_validity).trigger('change');
                        $('#delivery_time').val(data.delivery_time).trigger('change');
                        $('#payment_terms').val(data.payment_terms).trigger('change');
                        $('#terms').val(data.terms).trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching default values:', error);
                    }
                });
            }

            // Panggil fungsi untuk mengisi nilai default setelah halaman dimuat
            setDefaultValues();
        });
        $('.select2').select2({
            width: '100%',
            dropdownAutoWidth: true,
            allowClear: true,
            width: '100%',
        });

        // $('#getTermDeliveryPoint').select2({
        //     dropdownParent: $('#quotationPDFModal'),
        //     ajax: {
        //         url: "{{ route('select2.getTermDeliveryPoint') }}", // Adjust this URL to match your route
        //         dataType: 'json',
        //         delay: 250,
        //         minimumInputLength: 0,
        //         placeholder: $(this).data('placeholder'),
        //         allowClear: true,
        //         width: '100%',
        //         processResults: function (data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //         cache: true
        //     }
        // });
        // $('#term_comp_ids').select2({
        //     dropdownParent: $('#quotationPDFModal'),
        //     ajax: {
        //         url: "{{ route('select2.getTermComp') }}", // Adjust this URL to match your route
        //         dataType: 'json',
        //         delay: 250,
        //         minimumInputLength: 0,
        //         placeholder: $(this).data('placeholder'),
        //         allowClear: true,
        //         width: '100%',
        //         processResults: function (data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //         cache: true
        //     }
        // });
        $('.select2-container').css('width', '100%');
        $(document).ready(function() {
            function toggleFields(selectId, fieldsId) {
                var select = $('#' + selectId);
                var fields = $('#' + fieldsId);

                if (select.val() === 'true') {
                    fields.show();
                    fields.find('input:not([type="file"]), select, textarea').prop('required', true);
                } else {
                    fields.hide();
                    fields.find('input:not([type="file"]), select, textarea').prop('required', false);
                }
            }

            $('#is_bb_bond').change(function() {
                toggleFields('is_bb_bond', 'bid_bond_fields');
            });

            $('#is_pb_bond').change(function() {
                toggleFields('is_pb_bond', 'pb_bond_fields');
            });

            // Initial check
            toggleFields('is_bb_bond', 'bid_bond_fields');
            toggleFields('is_pb_bond', 'pb_bond_fields');
            $('#generate-quotation-customer').on('click', function() {
                if ($(this).hasClass('bid-warning')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning !',
                        html: 'Bid Bond should be filled in <a target="_blank" href="{{ route('procurement.edit', ['id' => encrypt($sales->id), 'type' => 'tender', 'is_detail_tender' => true]) }}" class="btn btn-xs btn-warning text-white"><i class="fa fa-pencil"></i> Edit Tender</a> first.'
                    });
                } else {
                    $('#quotationPDFModal').modal('show');
                }
            });
            $('#po-to-principle').on('click', function() {
                $('#poToPrinciple').modal('show');
                if ($(this).hasClass('bid-warning')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning !',
                        html: 'Bid Bond should be filled in <a target="_blank" href="{{ route('procurement.edit', ['id' => encrypt($sales->id), 'type' => 'tender']) }}" class="btn btn-xs btn-warning text-white"><i class="fa fa-pencil"></i> Edit Tender</a> first.'
                    });
                } else {}
            });
            // Scroll to step 10 (adjust index for zero-based counting)
            var targetStep = $('#scroll-target');
            if (targetStep.length > 0) {
                var container = $('.progress-indicator-container');
                var scrollLeft = targetStep.offset().left - container.offset().left + container.scrollLeft();
                container.animate({
                    scrollLeft: scrollLeft
                }, 'slow');
            }
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
                })
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

                // Set the form action to include the id
                var updateUrl =
                    "{{ route('sales.update-price-principle-kz', ['id' => ':id', 'type' => ':type']) }}"
                    .replace(':id', materialId).replace(':type', type);
                $('#updatePricePrincipleKzForm').attr('action', updateUrl);
                $('.updatePriceType').html(type);
                $('.updatePriceType-input').attr('placeholder', 'Update Price from ' + type);
                $('.updatePricePrincipleKz-principle').html($(this).data('name'));

                // Show the modal
                $('#updatePricePrincipleKzModal').modal('show');
            });
            $('#updatePricePrincipleKzForm').submit(function(e) {
                e.preventDefault();
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $(document).on('click', '.proc-update-review', function() {
                // Get the data-id attribute of the clicked button
                var salesId = $(this).data('id');
                var type = $(this).data('type');

                // Set the form action to include the id
                var updateUrl = "{{ route('sales.update-review', ['id' => ':id', 'type' => ':type']) }}"
                    .replace(':id', salesId);
                $('#updateReviewForm').attr('action', updateUrl);
                $('.updateReview-principle').html($(this).data('name'));

                // Show the modal
                $('#updateReviewModal').modal('show');
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
            $(document).on('click', '#saveupdatePricePrincipleKz', function() {
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
                    }
                });
            });

            // Handle the form submission
            $(document).on('click', '#saveupdateReview', function() {
                Swal.fire({
                    text: "Are you sure to continue this process! This Value (Price KZ )will be generate as Quotation for Customer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = $('#updateReviewForm').serialize();
                        var formActionUrl = $('#updateReviewForm').attr(
                            'action'); // Get the form action URL

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
                                        window.location.reload();
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
                    url: "{{ route('sales.material-list', ['id' => encrypt($sales->id), 'is_detail_tender' => true]) }}",
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
            $('#table-customer-po').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('procurement.po-customer-list', ['id' => encrypt($sales->id)]) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'tender.name',
                        name: 'tender.name',
                    },
                    {
                        data: 'customer_po_no',
                        name: 'customer_po_no',
                        className: 'text-center',
                    },
                    {
                        data: 'customer_po_date',
                        name: 'customer_po_date',
                        className: 'text-center',
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
                        className: 'text-center',
                    },
                    {
                        data: 'customer_po_note',
                        name: 'customer_po_note',
                        className: 'text-center'
                    },
                    {
                        data: 'customer_po_doc',
                        name: 'customer_po_doc',
                        className: 'text-center',
                    },

                ]
            })
            $('#table-rfq').DataTable({
                processing: true,
                serverSide: false,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                ajax: {
                    url: "{{ route('procurement.rfq-list', ['id' => encrypt($sales->id), 'detail_tender' => true]) }}"
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
                        data: 'reviewed_by',
                        name: 'reviewed_by',
                        className: 'text-center',
                    },
                    {
                        data: 'reviewed_at',
                        name: 'reviewed_at',
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
                        data: 'doc_po_principle',
                        name: 'doc_po_principle',
                        className: 'text-center',
                    },
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
                            Swal.fire({
                                title: "Deleted!",
                                text: "The material data has been deleted.",
                                showConfirmButton: false,
                                timer: 1000,
                                icon: "success"
                            }).then(() => {
                                window.location.href =
                                    "{{ route('procurement.index') }}"; // Redirect to specified URL after SweetAlert
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

        function confirmFinal(finalUrl) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, transfer to procurement!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.blockUI();
                    $.ajax({
                        type: "POST",
                        url: finalUrl,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#tableSales').DataTable().ajax.reload();
                            $.unblockUI();
                            Swal.fire({
                                title: "Transfered!",
                                text: "The tender data has been transfered to procurement team!.",
                                showConfirmButton: false,
                                timer: 1000,
                                icon: "success"
                            }).then(() => {
                                window.location
                                    .reload(); // Redirect to specified URL after SweetAlert
                            });
                        }
                    })
                }
            });
        }

        function confirmAndSendQuo(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to send this Quotation to Procurement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.blockUI();
                    $('#quotationPDFModal').modal('hide');
                    var formData = $('#quoPDF').serialize();

                    $.ajax({
                        url: "{{ route('sales.quotation-to-customer-pdf-post', ['id' => encrypt($sales->id), 'role' => 'procurement']) }}",
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            $.unblockUI();
                            if (response.status) {
                                Swal.fire(
                                    'Sent!',
                                    'Quotation has been sent successfully.',
                                    'success'
                                );
                            } else {
                                console.log(response);
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while sending the Quotation.',
                                    'error'
                                );
                            }
                        },
                        error: function(response) {
                            $.unblockUI();
                            console.log(response);
                            Swal.fire(
                                'Error!',
                                'An error occurred while sending the Quotation.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endpush
