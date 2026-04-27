<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Exit Voucher #{{ $order->id }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .voucher-box {
            border: 2px solid #1b2d95;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }

        .stamp {
            color: #10b981;
            border: 2px solid #10b981;
            display: inline-block;
            padding: 5px;
            transform: rotate(-10deg);
            font-weight: bold;
            margin-top: 10px;
        }

        .info-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .parts-title {
            background: #f8fafc;
            padding: 10px;
            margin-top: 20px;
            font-weight: bold;
            color: #1b2d95;
        }

        .parts-list {
            width: 100%;
            border-collapse: collapse;
        }

        .parts-list td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="voucher-box">
        <div class="header">
            <h2>Spare Parts Exit Voucher</h2>
            <div class="stamp">APPROVED</div>
            <p>Serial Number: #{{ $order->product->serial_number ?? $order->product->sn }}</p>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Technician:</strong></td>
                <td>{{ auth()->user()->name }}</td>
            </tr>
            <tr>
                <td><strong>Order Number:</strong></td>
                <td>Job #{{ $order->id }}</td>
            </tr>
            <tr>
                <td><strong>Issue Date:</strong></td>
                <td>{{ $order->updated_at->format('Y-m-d H:i') }}</td>
            </tr>
        </table>

        <div class="parts-title">Authorized Spare Parts:</div>
        <table class="parts-list">
            <thead>
                <tr style="background: #f1f5f9;">
                    <th align="left" style="padding: 10px;">Item Description</th>
                    <th align="right" style="padding: 10px;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;">
                            • {{ $item['name'] ?? 'N/A' }}
                        </td>
                        <td align="right" style="padding: 10px; border-bottom: 1px solid #eee;">
                            <span
                                style="background: #1b2d95; color: white; padding: 2px 8px; border-radius: 4px; font-weight: bold;">
                                x{{ $item['quantity'] ?? 1 }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</body>

</html>
