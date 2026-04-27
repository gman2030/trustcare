@extends('layouts.worker-master')

@section('page-title', 'Exit Voucher')
<link rel="stylesheet" href="{{ asset('css/exit.css') }}">
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">

    <div class="exit-pass">
        <div class="approved-stamp">Approved</div>

        <div class="pass-header">
            <h4 style="font-weight: 700; margin-bottom: 4px; font-family: 'Cairo', sans-serif;">Spare Parts Exit Voucher</h4>
            <small style="color: #94a3b8;">Device Serial Number: #{{ $order->product->serial_number }}</small>
        </div>

        <div class="pass-body">
            <div class="info-group">
                <span class="label">Technician Name:</span>
                <span class="value">{{ Auth::user()->name }}</span>
            </div>
            <div class="info-group">
                <span class="label">Order Number:</span>
                <span class="value">Job #{{ $order->id }}</span>
            </div>
            <div class="info-group">
                <span class="label">Issue Date:</span>
                <span class="value">{{ $order->updated_at->format('Y-m-d H:i') }}</span>
            </div>

            <div class="parts-box">
                <h6>Authorized Parts for Device ({{ $order->product->name }}):</h6>
                <ul style="list-style: none; padding: 0;">
                    @php
                        $items = is_array($order->items) ? $order->items : json_decode($order->items);
                    @endphp

                    @foreach ($items as $item)
                        <li
                            style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; background: #f8fafc; padding: 5px; border-radius: 5px;">
                            <img src="{{ asset('uploads/parts/' . ($item->image ?? $item['image'])) }}"
                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">

                            <span>• {{ $item->name ?? $item['name'] }}</span>
                            <span style="margin-left: auto; font-weight: bold; color: #1b2d95;">
                                x{{ $item->quantity ?? $item['quantity'] }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <form action="{{ route('worker.confirm.exit') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <button type="submit" class="btn-claim" style="width: 100%; cursor: pointer;">


                <div style="margin-bottom: 15px;">
                    <a href="{{ route('worker.exit.download', $order->id) }}" class="btn-claim"
                        style="display: block; text-align: center; background: #2563eb; text-decoration: none;">
                        <i class="fas fa-file-pdf"></i> Download PDF Voucher
                    </a>
                </div>
            </button>
        </form>
    </div>
    <p class="pass-note">* Please show this page to the warehouse manager upon collection.</p>

@endsection
