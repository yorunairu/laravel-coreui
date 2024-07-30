<!DOCTYPE html>
<html>
<head>
    <title>Sales Data Transferred</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background-color: #ffffff;
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #dddddd;
        }
        .header h1 {
            margin: 0;
            color: #333333;
        }
        .content {
            padding: 20px 0;
        }
        .content p {
            line-height: 1.6;
            color: #555555;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #dddddd;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Sales Data Transferred</h1>
        </div>
        <div class="content">
            <p>Dear Procurement Team,</p>
            <p>We wanted to inform you that the sales data with the following details has been transferred to your department:</p>
            <ul>
                <li><strong>No RFQ:</strong> {{ $sales->no_rfq }}</li>
                <li><strong>Customer Name:</strong> {{ $sales->customer->name }}</li>
                <li><strong>Tender Name:</strong> {{ $sales->name }}</li>
                <li><strong>Tender Value:</strong> {{ thousandSeparator($sales->total_price_tender) }} {{ $sales->currency->currency }}</li>
                <li><strong>Sales Name:</strong> {{ $sales->userCreator->name }}</li>
                <li><strong>Transfer Date:</strong> {{ $sales->updated_at->format('d F Y') }}</li>
            </ul>
            <p>Please review the transferred data at your earliest convenience.</p>
            <p>Best regards,</p>
            <p>The Sales Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} PT. Kencana Zavira. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
