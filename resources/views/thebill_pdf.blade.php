<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/thebillpdf.css') }}">
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.5;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }


        .header-table {
            width: 100%;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
        }

        .brand-title {
            font-size: 28px;
            color: #3498db;
            font-weight: bold;
        }

        .inv-details {
            text-align: right;
        }

        .info-table {
            width: 100%;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .info-box {
            width: 50%;
            vertical-align: top;
        }

        .label {
            color: #777;
            font-size: 12px;
            text-transform: uppercase;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table th {
            background: #f8f9fa;
            border-bottom: 2px solid #eee;
            padding: 10px;
            text-align: left;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }


        .totals-table {
            width: 100%;
            margin-top: 30px;
        }

        .total-row td {
            padding: 5px 0;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #3498db;
            border-top: 2px solid #3498db;
        }
    </style>
</head>

<body>

    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td class="brand-title">TrustCare</td>
                <td class="inv-details">
                    <strong>Invoice #{{ $order->id }}</strong><br>
                    Date: {{ $order->updated_at->format('d/m/Y') }}
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td class="info-box">
                    <div class="label">Bill To:</div>
                    <strong>{{ auth()->user()->name }}</strong><br>
                    {{ auth()->user()->email }}
                </td>
                <td class="info-box">
                    <div class="label">Product Details:</div>
                    <strong>{{ $order->product->name ?? 'N/A' }}</strong><br>
                    S/N: {{ $order->product->serial_number ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th class="text-right">Price</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-right">{{ number_format($item['price'], 2) }} DA</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ number_format($item['price'] * $item['quantity'], 2) }} DA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td style="width: 60%;"></td>
                <td>
                    <table style="width: 100%;">
                        <tr class="total-row">
                            <td>Subtotal (HT):</td>
                            <td class="text-right">{{ number_format($order->subtotal, 2) }} DA</td>
                        </tr>
                        <tr class="total-row">
                            <td>VAT ({{ $order->vat_rate }}%):</td>
                            <td class="text-right">{{ number_format($order->total_ttc - $order->subtotal, 2) }} DA</td>
                        </tr>
                        <tr class="total-row grand-total">
                            <td>Total (TTC):</td>
                            <td class="text-right">{{ number_format($order->total_ttc, 2) }} DA</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
