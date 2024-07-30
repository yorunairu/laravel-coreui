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
        table, th, td {
            line-height: normal !important; /* Ensure no extra line height */
            padding: 5px !important; /* Adjust padding as necessary */
            margin: 0 !important;
            /* border: 1px solid black; */
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
                    <P>Jl. Rawa Bambu Komplek Depkes No. A/6 Pasar Minggu Jakarta Selatan. 12520 - lndonesia <br>
                        Telp (+62-21) 7822486 Fax. (+62-21) 7822485</span><br>
                        Website : www.kencana-zavira.com | Email : k-zavira@cbn.net.id</span></p>
                    {{-- <p>Date Due: Mar 29, 2024</p> --}}
                </div>
            </td>
        </tr>
    </table>
</header>
<body>
    <h5 class="text-center">Request for Quotation</h5>
    <table style="width:100%;" class="mb-3">
        <tr>
            <td style="font-size: 14px;" width="10%">Date</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-right">{{ date('d F Y', strtotime($rfqp->tenderRfq->date_created)) }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;" width="10%">Ref</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-right">{{ $rfqp->tenderRfq->rfq_no }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;" width="10%">To</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-right">{{ $rfqp->principle->name }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;" width="10%">Attn</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-right">{{ $rfqp->principle->pic_name }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;" width="10%">Fax No</td>
            <td style="font-size: 14px;" width="5%">:</td>
            <td style="font-size: 14px;" class="text-right">{{ $rfqp->principle->phone }}</td>
        </tr>
    </table>
    <p class="" style="line-height: 20px;">Dear {{ $rfqp->principle->pic_name }},<br>
        Would you please advise us the competitive quotation price for parts with specification as mentioned below:</p>

    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead class="table-garis table-sm">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-left">Material Code</th>
                        <th class="text-left">Description</th>
                        <th class="text-left">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rfqp->tenderRfq->tender->tenderMaterial as $key => $item)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td class="text-left">{{ (substr($item->material->material_code, 0, 2) === "MT")?'-':$item->material->material_code }}</td>
                            <td class="text-left">
                                <div style="line-height: 25% !important;">{!! $item->description !!}<div>
                            </td>
                            <td class="text-left">{{ $item->qty }} {{ $item->uom->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="">
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
                            @foreach ($rfqp->tenderRfq->delpoint as $item)
                            <li class="li">{{ $item->mDelpoint->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Complete with</td>
                    <td width="1%">:</td>
                    <td width="79%">
                        <ul class="">
                            @foreach ($rfqp->tenderRfq->term as $item)
                            <li class="li">{{ $item->mCompwith->name }}</li>
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
                We would appreciate your assistance to provide a quote to us before <strong>{{date('d F Y', strtotime($rfqp->tenderRfq->date_deadline)) }}</strong> .

            We thank you for your kind attention and cooperation.
        </p>
    </div>
    {{-- <p>{{date('d F Y', strtotime($rfqp->tenderRfq->tender->deadline)) }}</p> --}}
    <table>
        <tr>
            <td class="text-center">
                <div class="mt-3" style="margin-bottom: 100px;">
                    <p>Best Regards,</p>
                </div>
                <div class="">
                    <p>{{ ucwords($user->name) }}</p>
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
