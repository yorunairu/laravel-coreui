<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            width: 100%;
        }
        .invoice-container {
            /* margin: 20px; */
        }
        .invoice-header, .invoice-footer {
            text-align: center;
        }
        .invoice-header h1, .invoice-footer h5 {
            margin: 0;
        }
        .invoice-details, .billing-details {
            margin-bottom: 20px;
        }
        .kop-surat{
            border-bottom: thick double black;
        }
        .table-garis{
            border-bottom: thick double black;
        }
        .v-align-middle {
            vertical-align: middle;
        }
        .invoice-header{
            text-align: left;
        }
        p{
            font-size: 14px;
        }
        .container {
            margin: 20px;
        }
        .terms-header {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .terms-section {
            margin-bottom: 20px;
        }
        .indent {
            margin-left: 100px;
        }
        .highlight {
            background-color: #e0e0e0;
            padding: 5px;
        }
        li ul li{
            list-style-type: none;
        }
        .li{
            vertical-align: middle;
        }

        header {
            position: fixed;
            top: -30px;
            left: auto;
            right: auto;
            width: 100%;
        }

        body {
            margin-top: 120px;
            page-break-before: auto;
        }

        footer {
            position: fixed;
            bottom: -60px;
        }
        .text-right{
            text-align: right;
        }
        table, th, td {
            line-height: normal !important; /* Ensure no extra line height */
            padding: 5px !important; /* Adjust padding as necessary */
            margin: 0 !important;
        }
    </style>
</head>
{{-- @class(['p-4', 'font-bold' => true]) --}}
<header>
    <table class="kop-surat table">
        <tr>
            <td width="20%" style="text-align: right;">
                <img src="<?=$_SERVER['DOCUMENT_ROOT'].'/images/logo_only.png'?>" alt="" style="width: 100%">
            </td>
            <td width="80%">
                <div style="text-align: left;">
                    <h4 class="fs-6">PT.KENCANA ZAFIRA</h4>
                    <P>Jl. Rawa Bambu Komplek Depkes No. A/6 Pasar Minggu
                        Jakarta Selatan. 12520 - lndonesia <br>
                        Telp (+62-21) 7822486 Fax. (+62-21) 7822485</span><br>
                    Website : www.kencana-zavira.com | Email : k-zavira@cbn.net.id</span></p>
                    {{-- <p>Date Due: Mar 29, 2024</p> --}}
                </div>
            </td>
        </tr>
    </table>
</header>
<body>
    <h5 class="text-center">Quotation</h5>
    <table style="width:100%;" class="mb-3">
        <tr>
            <td style="font-size: 14px;" width="10%">Date</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-left">{{ date('d F Y', strtotime(date('Y-m-d'))) }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;" width="10%">Our Ref</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-left">{{ $sales->tenderRfq->rfq_no }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;" width="10%">Your Ref</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-left">{{ $sales->no_rfq }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;" width="10%">To</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-left">{{ $sales->customer->name }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;" width="10%">Attn</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-left">{{ $sales->customer->pic_name }} - {{ $sales->customer->position }}</td>
        </tr>
    </table>
    <p class="" style="line-height: 20px;">Dear {{ $sales->customer->pic_name }},<br>
        We thank you for your inquiry, we are pleased to quote you as the following belows :</p>

    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead class="table-garis">
                    <tr>
                        <th class="text-center" width="5%">NO</th>
                        <th class="text-left">Description</th>
                        <th class="text-left">Qty</th>
                        <th class="text-left">Unit</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $amount = 0;
                    @endphp
                    @foreach ($sales->tenderMaterial as $key => $item)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td class="text-left">
                                <strong>{{ strtoupper($item->material->material_code) }}</strong><br>
                                {!! $item->description !!}
                            </td>
                            <td class="text-left">{{ $item->qty }}</td>
                            <td class="text-left">{{ $item->uom->name }}</td>
                            <td class="text-right">{{ thousandSeparator($item->price->where('type', 'KZ')->sum('price')) }} {{ $item->price->where('type', 'KZ')->first()->currency->currency }}</td>
                            @php
                            $amount_unit = $item->price->where('type', 'KZ')->sum('price')*$item->qty;
                                $amount += $amount_unit;
                            @endphp
                            <td class="text-right">{{ thousandSeparator($amount_unit) }} {{ $item->price->where('type', 'KZ')->first()->currency->currency }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Total</strong></td>
                        <td class="text-right">{{ thousandSeparator($amount) }} {{ $item->price->where('type', 'KZ')->first()->currency->currency }} </td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="4"><strong>Says : {{ ucwords(priceToWords($amount)) }} {{ $item->price->where('type', 'KZ')->first()->currency->currency }}</strong></td>
                        <td class="text-center"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="">
        <table>
            <div class="">
                <h6>[NOTES]</h6>
            </div>
            <tr>
                <td><strong>{{ strip_tags($pdfDataTerm['note']) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="mt-5">
        <div class="">
            <h6>Terms and Conditions</h6>
        </div>
        <div class="">
            <table class="table w-100" style="width:100%; font-size: 14px;">
                <tr>
                    <td width="20%">Delivery Point</td>
                    <td width="1%">:</td>
                    <td width="79%">
                        <ul class="">
                            <li class="li">{{ implode(' , ' , $delpoint['delivery_point']) }}</li>
                            {{-- @foreach ($sales->tenderRfq->compwith as $item)
                            @endforeach --}}
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Additional Value</td>
                    <td width="1%">:</td>
                    <td width="79%">
                        <ul class="">
                            <li class="li">{{ implode(' , ' , $pdfDataTerm['additional_value']) }}</li>
                            {{-- @foreach ($sales->tenderRfq->compwith as $item)
                            @endforeach --}}
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Price Validity</td>
                    <td width="1%">:</td>
                    <td width="79%">
                        <ul class="">
                            <li class="li">{{ implode(' , ' , $pdfDataTerm['price_validity']) }}</li>
                            {{-- @foreach ($sales->tenderRfq->compwith as $item)
                            @endforeach --}}
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Delivery Time</td>
                    <td width="1%">:</td>
                    <td width="79%">
                        <ul class="">
                            <li class="li">{{ implode(' , ' , $pdfDataTerm['delivery_time']) }}</li>
                            {{-- @foreach ($sales->tenderRfq->compwith as $item)
                            @endforeach --}}
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Payment Terms</td>
                    <td width="1%">:</td>
                    <td width="79%">
                        <ul class="">
                            <li class="li">{{ implode(' , ' , $pdfDataTerm['payment_terms']) }}</li>
                            {{-- @foreach ($sales->tenderRfq->compwith as $item)
                            @endforeach --}}
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Terms</td>
                    <td width="1%">:</td>
                    <td width="79%">
                        <ul class="">
                            @foreach ($pdfDataTerm['terms'] as $key => $item)
                            <li class="li">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    </div>
    <div class="">
        <p class="">
            We look forward to your valued order .
        </p>
    </div>
    {{-- <p>{{date('d F Y', strtotime($sales->deadline)) }}</p> --}}
    <table>
        <tr>
            <td class="text-left">
                <div class="mt-3" style="margin-bottom: 100px;">
                    <p>Best Regards,</p>
                </div>
                <div class="" style="line-height: 0.5px;">
                    <p>Benny Hermawan</p>
                    <p>Director</p>
                </div>
            </td>
            <td width="80%;"></td>
        </tr>
    </table>
</body>
<footer>
    <script type="text/php">
        if (isset($pdf)) {
            $x = 520;
            $y = 810;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = null;
            $size = 9;
            $color = array(0, 0, 0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

            $x = 25;
            $y = 810;
            $text = "* This Document is computer generated, no Officer's signature is required.";
            $font = null;
            $size = 9;
            $color = array(0, 0, 0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</footer>
</html>
