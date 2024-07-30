<div class="modal-header">
    <h5 class="modal-title" id="updatePricePrincipleKzLabel">Update Price From <span class="updatePriceType">{{ $request->type }}</span> : <span class="updatePricePrincipleKz-principle"></span></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="updatePricePrincipleKzForm" class="form-horizontal" action="{{ route('sales.update-price-principle-kz', ['id' => $request->material_id, 'type' => $request->type]) }}">
        @csrf
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Currency</th>
                            <th>Rate to IDR (Date Rate)</th>
                            <th>IDR Convert</th>
                        </tr>
                    </thead>
                    <tbody id="currencyTableBody">
                        <!-- Table body will be updated dynamically -->
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="currency" class="col-sm-3 col-form-label">Price From <span class="updatePriceType">{{ $request->type }}</span> <span class="text-danger">*</span></label>
                    <div class="col-sm-2">
                        <select class="form-control" name="currency_id" id="update_currency_id" data-placeholder="Select Currency" readonly required>
                            @foreach ($currency as $key => $item)
                                <option data-date-rate="{{ $item->date_rate }}"
                                    @if (!empty($price))
                                    @if ($price->m_currency_id == $item->id)
                                    selected
                                    @endif
                                    @endif
                                    data-rate="{{ $item->price_rate }}" value="{{ $item->id }}">{{ $item->currency }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" class="form-control updatePriceType-input price-thousand prevent-zero" id="update_price" name="price" placeholder="Price from {{ $request->type }}" required value="{{ !empty($price)?thousandSeparator($price->price):0 }}">
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

<script type="module">
    // Function to update the table body
    function updateTable() {
        // Get the selected currency option
        var selectedCurrency = $('#update_currency_id').find(":selected");
        var dateRate = selectedCurrency.data('date-rate');
        var priceRate = selectedCurrency.data('rate');

        var inputPrice = parseFloat($('#update_price').val().replace(/\./g, ''));

        // Check if priceRate is a valid number
        if (isNaN(priceRate) || isNaN(inputPrice)) {
            priceRate = 0;
            inputPrice = 0;
        }

        // Calculate IDR Convert
        var idrConvert = inputPrice * priceRate;

        // Update the table body
        var tableRow = '<tr>' +
                        '<td>' + selectedCurrency.text() + '</td>' +
                        '<td>' + (dateRate ? priceRate.toLocaleString('id-ID') +' <small class="text-muted">('+ dateRate+')</small>' : 'N/A') + '</td>' +
                        '<td>' + idrConvert.toLocaleString('id-ID') + '</td>' +
                    '</tr>';
        $('#currencyTableBody').html(tableRow);
    }

    // Event listener for price input change
    $(document).on('input', '#update_price', function() {
        updateTable();
    });

    // Event listener for currency selection change
    $(document).on('change', '#update_currency_id', function() {
        updateTable();
    });

    // Initial table update
    updateTable();

    $('.prevent-zero').on('input', function(event) {
        let value = $(this).val().trim();

        // Remove leading zeros (optional step)
        if (value.startsWith('0')) {
            value = value.replace(/^0+/, '');
        }

        // Check for negative sign and remove if present
        if (value.startsWith('-')) {
            value = value.replace('-', '');
        }

        // Update the input value with the sanitized version
        $(this).val(value);
    });

    $('.price-thousand').on('keyup', function() {
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
</script>
