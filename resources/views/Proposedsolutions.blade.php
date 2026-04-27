@extends('layouts.home-master')
@section('page-title', 'Proposed Solutions')
<link rel="stylesheet" href="{{ asset('css/Proprsed.css') }}">
@section('content')
<div class="ps-page">
    <div class="ps-container">

        @if ($order)


            {{-- Hero --}}
            <div class="ps-hero">
                <div class="ps-eyebrow">
                    <i class="fas fa-shield-alt"></i> Consumer Rights Notice
                </div>
                <h1>Compensation Policy for: <em>{{ $order->product->name }}</em></h1>
                <div class="ps-meta">
                    <span class="badge-status">Status: Repair Failed — Parts Unavailable</span>

                </div>
            </div>

            {{-- Section label --}}
            <div class="ps-section-label">Available Compensation Options</div>

            {{-- Option 1 & 2 --}}
            <div class="ps-grid">

                <div class="sol-card">
                    <span class="sol-number blue">Option 01</span>
                    <div class="sol-icon blue"><i class="fas fa-sync-alt"></i></div>
                    <h4>Identical Replacement</h4>
                    <p>You are entitled to receive a <strong>brand-new device of the exact same model and version</strong> as your defective unit, at <strong>no additional cost</strong>.</p>
                </div>

                <div class="sol-card">
                    <span class="sol-number green">Option 02</span>
                    <div class="sol-icon green"><i class="fas fa-exchange-alt"></i></div>
                    <h4>Choose a Different Product</h4>
                    <p>Select any other product from our store. If the chosen item's price exceeds <strong></strong>, you only pay <strong>the price difference</strong>.</p>
                </div>

            </div>

            {{-- Option 3 --}}
            <div class="sol-card-wide">
                <div class="sol-icon amber" style="flex-shrink:0; margin:0;">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <div class="sol-number amber" style="position:static; display:inline-block; margin-bottom:10px; border-radius:6px;">Option 03</div>
                    <h5>Multiple Products Bundle</h5>
                    <small>
                        You may select a combination of products whose <strong>total price equals the value of your original device</strong>.
                        <br><br>
                        <strong>Note:</strong> If the combined total exceeds <strong></strong>, you will be responsible for covering the remaining balance.
                    </small>
                </div>
            </div>

            {{-- Notice --}}
            <div class="ps-notice">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Next Steps:</strong> Please visit your nearest branch or contact our customer support team to select your preferred option and initiate the handover process.
                </div>
            </div>

            {{-- Back button --}}
            <div class="ps-footer">
                <a href="{{ route('home') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>

        @else

            <div class="ps-empty">
                <i class="fas fa-folder-open"></i>
                <h3>No Order Found</h3>
                <p>We couldn't find an associated order. Please contact support if you believe this is an error.</p>
            </div>

        @endif

    </div>
</div>

@endsection
