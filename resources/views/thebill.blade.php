@extends('layouts.home-master')

@section('content')
<link rel="stylesheet" href="{{ asset('css/thebill.css') }}">
    

    <div class="inv-page">
        <div class="inv-wrap">
            <div class="inv-paper">

                {{-- HEADER --}}
                <div class="inv-header">
                    <div class="inv-header-inner">
                        <div class="inv-brand">
                            <div class="inv-brand-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="inv-brand-text">
                                <strong>Invoice</strong>
                                <span>Official Tax Document</span>
                            </div>
                        </div>
                        <div class="inv-meta">
                            <span class="inv-number">#{{ $order->id }}</span>
                            <span class="inv-date"><i class="far fa-calendar-alt"></i>
                                &nbsp;{{ $order->updated_at->format('d / m / Y') }}</span>
                        </div>
                    </div>
                    <div class="inv-status">
                        <span class="inv-status-pill">
                            <i class="fas fa-check-circle"></i> Payment Confirmed
                        </span>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="inv-body">

                    {{-- Customer + Order Info --}}
                    <div class="inv-info-row">
                        <div class="inv-info-card">
                            <div class="inv-info-card-label"><i class="fas fa-user"></i> Bill To</div>
                            <strong>{{ auth()->user()->name }}</strong>
                            <span>{{ auth()->user()->email }}</span>
                        </div>
                        {{-- Product Info (الإضافة هنا) --}}
                        <div class="inv-info-card">
                            <div class="inv-info-card-label"><i class="fas fa-microchip"></i> Product Details</div>
                            {{-- عرض اسم المنتج --}}
                            <strong>{{ $order->product->name ?? 'N/A' }}</strong>
                            {{-- عرض الرقم التسلسلي --}}
                            <span>S/N: <code
                                    style="color: #e83e8c;">{{ $order->product->serial_number ?? 'N/A' }}</code></span>
                        </div>
                        <div class="inv-info-card">
                            <div class="inv-info-card-label"><i class="fas fa-info-circle"></i> Order Info</div>
                            <strong>Order #{{ $order->id }}</strong>
                            <span>Issued: {{ $order->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <div class="inv-table-wrap">
                        <table class="inv-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="right">Unit Cost</th>
                                    <th class="center">Qty</th>
                                    <th class="right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="item-name">{{ $item['name'] }}</td>
                                        <td class="right">{{ number_format($item['price'], 2) }} DA</td>
                                        <td class="center"><span class="qty-chip">{{ $item['quantity'] }}</span></td>
                                        <td class="row-total">{{ number_format($item['price'] * $item['quantity'], 2) }} DA
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Totals --}}
                    <div class="inv-totals">
                        <div class="inv-totals-box">
                            <div class="inv-total-row">
                                <span>Subtotal (HT)</span>
                                <span>{{ number_format($order->subtotal, 2) }} DA</span>
                            </div>
                            <div class="inv-total-row">
                                <span>VAT ({{ $order->vat_rate }}%)</span>
                                <span>{{ number_format($order->total_ttc - $order->subtotal, 2) }} DA</span>
                            </div>
                            <div class="inv-total-row grand">
                                <span>Total (TTC)</span>
                                <span>{{ number_format($order->total_ttc, 2) }} DA</span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="inv-footer">
                    {{-- استبدل زر الطباعة القديم بهذا الرابط --}}
                    <a href="{{ route('invoice.download', $order->id) }}" class="btn-print"
                        style="text-decoration: none; display: inline-block;">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                    <a href="{{ route('home') }}" class="btn-home">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
