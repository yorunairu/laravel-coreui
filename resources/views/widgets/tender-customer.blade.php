<div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
          <h5>Detail Tender | {{ $sales->name }}</h5>
        </button>
      </h2>
      <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 row align-items-center">
                            <div class="col-sm-3">
                                <label class="form-label">Tender Type <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-9">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="tender_type_non_direct" name="tender_type" class="custom-control-input" value="non_direct" required disabled {{ $sales->tender_type == 'non_direct'?'checked':'' }}>
                                    <label class="custom-control-label" for="tender_type_non_direct">Non Direct</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="tender_type_direct" name="tender_type" class="custom-control-input" value="direct" required disabled {{ $sales->tender_type == 'direct'?'checked':'' }}>
                                    <label class="custom-control-label" for="tender_type_direct">Direct</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="tender_type_non_tender" name="tender_type" class="custom-control-input" value="non_tender" required disabled {{ $sales->tender_type == 'non_tender'?'checked':'' }}>
                                    <label class="custom-control-label" for="tender_type_non_tender">Non Tender</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="no_rfq" class="col-sm-3 col-form-label required disabled">RFQ <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $sales->no_rfq }}" class="form-control" id="no_rfq" name="no_rfq" placeholder="Enter No RFQ" required disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-3 col-form-label">Tender Name <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $sales->name }}" class="form-control" id="name" name="name" placeholder="Input Tender" required disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="currency" class="col-sm-3 col-form-label">Tender Value <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control" data-url="{{ URL::to('select2/get-currency') }}" data-type="currency" name="currency_id" id="currency_id" data-placeholder="Select Currency" required disabled>
                                    @foreach ($currencies as $key => $item)
                                    <option value="{{ $item->id }}" {{ $item->id==$sales->currency_id?'selected':'' }}>{{ $item->currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" value="{{ thousandSeparator($sales->total_price_tender) }}" class="form-control" id="total_price_tender" name="total_price_tender" placeholder="Tender Value" required disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="doc_rfq_from_customer" class="col-sm-3 col-form-label">Document <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                @if($sales->doc_rfq_from_customer)
                                    <p>
                                        <a class="document-link" href="{{ asset('storage/' . $sales->doc_rfq_from_customer) }}" target="_blank">
                                            <i class="fa fa-file document-icon"></i>
                                            {{ $sales->doc_rfq_from_customer }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggal_keluar" class="col-sm-3 col-form-label">Doc Date<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" placeholder="Enter Tanggal Keluar" value="{{ date('Y-m-d', strtotime($sales->tanggal_keluar)) }}" required disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="is_bb_bond" class="col-sm-3 col-form-label">Is Bid Bond</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="is_bb_bond" id="is_bb_bond" disabled>
                                    <option value="">To Be Decided</option>
                                    <option value="true" {{ $sales->is_bb_bond === 'true' ? 'selected' : '' }}>Yes</option>
                                    <option value="false" {{ $sales->is_bb_bond === 'false' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Additional fields for is_bb_bond -->
                        <div id="bid_bond_fields" {{ !empty($sales->is_bb_bond)?'':'style="display: none;"' }}>
                            <div class="mb-3 row">
                                <label for="bb_no_guarantee" class="col-sm-3 col-form-label">BB No Guarantee <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" value="{{ @$sales->bgGuarantee->where('type', 'bb')->first()->no_guarantee }}" id="bb_no_guarantee" name="bb_no_guarantee" placeholder="Enter No Guarantee">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="bb_price" class="col-sm-3 col-form-label">BB Price <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control price-thousand" value="{{ @$sales->bgGuarantee->where('type', 'bb')->first()->price }}" id="bb_price" name="bb_price" placeholder="Enter Price">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="bb_note" class="col-sm-3 col-form-label">BB Note <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <textarea disabled class="form-control" id="bb_note" name="bb_note" rows="3" placeholder="Enter Note">{{ @$sales->bgGuarantee->where('type', 'bb')->first()->note }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="bb_time_periode" class="col-sm-3 col-form-label">BB Time Periode <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input disabled type="date" class="form-control" value="{{ !empty($bid_bond->time_period)?date('Y-m-d', strtotime(@$sales->bgGuarantee->where('type', 'bb')->first()->time_period)):date('Y-m-d') }}" id="bb_time_periode" name="bb_time_periode" placeholder="Enter Time Periode">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="bb_doc_bg" class="col-sm-3 col-form-label">Document Bid Bond <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input disabled type="file" class="form-control" id="bb_doc_bg" name="bb_doc_bg">
                                    @if(@$sales->bgGuarantee->where('type', 'bb')->first()->doc_bg)
                                        <p>
                                            <a class="document-link" href="{{ asset('storage/' . @$sales->bgGuarantee->where('type', 'bb')->first()->doc_bg) }}" target="_blank">
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
                        <div class="mb-3 row">
                            <label for="customer_id" class="col-sm-3 col-form-label">Customer <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control select2-ajax w-100" data-url="{{ URL::to('select2/cusprin/customer') }}" data-type="customer" name="customer_id" id="customer_id" data-placeholder="Select Customer" required disabled>
                                    <option value="{{ $sales->customer->id }}">{{ $sales->customer->name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" name="email" value="{{ $sales->email }}" placeholder="Enter Email" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="note" class="col-sm-3 col-form-label">Note <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="note" name="note" rows="4" placeholder="Enter Note" required disabled>{{ $sales->notes }}</textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="deadline" class="col-sm-3 col-form-label">Deadline <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="datetime-local" class="form-control" id="deadline" name="deadline" placeholder="Enter Deadline" value="{{ $sales->deadline }}" required disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="status_win_lost" class="col-sm-3 col-form-label">Anggaran/Spek</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_win_lost" id="status_win_lost" disabled>
                                    <option value="" {{ $sales->status_win_lost == null ? 'selected':'' }}>Yes</option>
                                    <option value="lost" {{ $sales->status_win_lost == 'lost' ? 'selected':'' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <!-- is_pb_bond field -->
                        <div class="mb-3 row">
                            <label for="is_pb_bond" class="col-sm-3 col-form-label">Is Perf Bond</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="is_pb_bond" id="is_pb_bond" disabled>
                                    <option value="">To Be Decided</option>
                                    <option value="true" {{ $sales->is_pb_bond === 'true' ? 'selected' : '' }}>Yes</option>
                                    <option value="false" {{ $sales->is_pb_bond === 'false' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Additional fields for is_pb_bond -->
                        <div id="pb_bond_fields"  {{ !empty($sales->is_pb_bond)?'':'style="display: none;"' }}>
                            <div class="mb-3 row">
                                <label for="pb_no_guarantee" class="col-sm-3 col-form-label">No Guarantee <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" value="{{ @$sales->bgGuarantee->where('type', 'pb')->first()->no_guarantee }}" id="pb_no_guarantee" name="pb_no_guarantee" placeholder="Enter No Guarantee">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="pb_price" class="col-sm-3 col-form-label">Price <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control price-thousand" value="{{ @$sales->bgGuarantee->where('type', 'pb')->first()->price }}" id="pb_price" name="pb_price" placeholder="Enter Price">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="pb_note" class="col-sm-3 col-form-label">Note <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <textarea disabled class="form-control" id="pb_note" name="pb_note" rows="3" placeholder="Enter Note">{{ @$sales->bgGuarantee->where('type', 'pb')->first()->note }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="pb_time_periode" class="col-sm-3 col-form-label">Periode to <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input disabled type="date" class="form-control" value="{{ !empty($pb_bond->time_period)?date('Y-m-d', strtotime(@$sales->bgGuarantee->where('type', 'pb')->first()->time_period)):date('Y-m-d') }}" id="pb_time_periode" name="pb_time_periode" placeholder="Enter Time Periode">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="pb_doc_bg" class="col-sm-3 col-form-label">Document Performa Bond <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input disabled type="file" class="form-control" id="pb_doc_bg" name="pb_doc_bg">
                                    @if(@$sales->bgGuarantee->where('type', 'pb')->first()->doc_bg)
                                        <p>
                                            <a class="document-link" href="{{ asset('storage/' . @$sales->bgGuarantee->where('type', 'pb')->first()->doc_bg) }}" target="_blank">
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
    </div>
</div>

@push('after-scripts')
<script type="module">
    $(document).ready(function () {
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
    });
</script>
@endpush
