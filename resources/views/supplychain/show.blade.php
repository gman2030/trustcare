@extends('layouts.Supplychain-master')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="page-wrapper">
        <!-- Header -->
        <header class="page-header">
            <div class="header-left">
                <button class="back-btn" onclick="history.back()">
                    <span class="material-symbols-outlined">arrow_back</span>
                </button>
                <div>
                    <p class="header-label">HVAC Inventory</p>
                    <h1 class="header-title">{{ $product->name }}</h1>
                </div>
            </div>
        </header>

        <!-- Product Hero Section -->
        <div class="product-hero">
            <div class="product-hero-inner">
                <!-- Image -->
                <div class="product-image-box">
                    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                </div>
                <!-- Details -->
                <div class="product-meta">
                    <div>
                        <h2 class="product-title">{{ $product->name }}</h2>
                        <p class="product-serial">
                            <span class="material-symbols-outlined"
                                style="font-size:15px; vertical-align:middle;">barcode_scanner</span>
                            Serial Number: <strong>{{ $product->serial_number }}</strong>
                        </p>
                    </div>
                    <div class="product-badges">
                        <div class="badge-box">
                            <p class="badge-label">Division</p>
                            <p class="badge-value">Spare Parts Division</p>
                        </div>
                        <div class="badge-box">
                            <p class="badge-label">Last Checked</p>
                            <p class="badge-value">2 hours ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spare Parts Section -->
        <form action="{{ route('worker.spare.confirm', $product->id) }}" method="POST" id="mainPartsForm">
            @csrf

            <div class="section-header">
                <h3 class="section-title">
                    Spare Parts
                    <span class="section-count">{{ $spareParts->count() }} Items</span>
                </h3>
                <div class="filter-btn">
                    <span class="material-symbols-outlined">filter_list</span>
                    Filter
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table class="parts-table">
                    <thead>
                        <tr>
                            <th style="width:70px;">Image</th>
                            <th>Part Name</th>
                            <th class="text-center">Quantity</th>
                            <th>Status</th>
                            <th class="text-right">Unit Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spareParts as $part)
                            <tr data-part-id="{{ $part->id }}">
                                <!-- Image -->
                                <td>
                                    <div class="row-img"
                                        style="background-image: url('{{ asset('uploads/parts/' . $part->image) }}')"></div>
                                </td>
                                <!-- Name -->
                                <td>
                                    <span class="part-name">{{ $part->name }}</span>
                                    <p class="part-sku">SKU: {{ $part->sku ?? 'N/A' }}</p>
                                </td>
                                <!-- Quantity -->
                                <td class="text-center">
                                    @if ($part->quantity > 0)
                                        <div class="qty-wrapper" style="display:none;">
                                            <input type="number" name="quantities[{{ $part->id }}]" value="1"
                                                min="1" max="{{ $part->quantity }}" class="qty-input"
                                                onclick="event.stopPropagation();">
                                        </div>
                                        <span class="qty-badge show-when-unchecked">{{ $part->quantity }} pcs</span>
                                    @else
                                        <span class="qty-badge qty-zero">0 pcs</span>
                                    @endif
                                </td>
                                <!-- Status -->
                                <td>
                                    @if ($part->quantity > 6)
                                        <span class="status-badge status-available">
                                            <span class="status-dot"></span> Available
                                        </span>
                                    @elseif($part->quantity > 4)
                                        <span class="status-badge status-limited">
                                            <span class="status-dot"></span> Low Stock
                                        </span>
                                    @elseif($part->quantity >= 1)
                                        <span class="status-badge status-limitede">
                                            <span class="status-dot"></span> Limited
                                        </span>
                                    @else
                                        <span class="status-badge status-unavailable">
                                            <span class="status-dot"></span> Unavailable
                                        </span>
                                    @endif
                                </td>
                                <!-- Price -->
                                <td class="text-right">
                                    <span class="part-price">{{ number_format($part->price, 2) }} DZ</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:50px; color:#64748b;">
                                    No parts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>




        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.part-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    const checkbox = this.querySelector('.part-checkbox');
                    if (checkbox && !checkbox.disabled) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            document.querySelectorAll('.part-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const row = this.closest('.part-row');
                    const qtyWrapper = row.querySelector('.qty-wrapper');
                    const qtyBadge = row.querySelector('.show-when-unchecked');
                    const qtyInput = row.querySelector('.qty-input');
                    const rowCheck = row.querySelector('.row-check');

                    if (this.checked) {
                        row.classList.add('is-selected');
                        if (qtyWrapper) {
                            qtyWrapper.style.display = 'block';
                        }
                        if (qtyBadge) {
                            qtyBadge.style.display = 'none';
                        }
                        if (qtyInput) qtyInput.disabled = false;
                    } else {
                        row.classList.remove('is-selected');
                        if (qtyWrapper) {
                            qtyWrapper.style.display = 'none';
                        }
                        if (qtyBadge) {
                            qtyBadge.style.display = 'inline-flex';
                        }
                        if (qtyInput) qtyInput.disabled = true;
                    }
                    updateFooter();
                });
            });

            function updateFooter() {
                const count = document.querySelectorAll('.part-checkbox:checked').length;
                const status = document.getElementById('selection-status');
                const btn = document.getElementById('submitBtn');

                status.innerHTML = count > 0 ?
                    `<b>${count}</b> part types selected` :
                    'Please select parts.';

                btn.disabled = count === 0;
            }
        });
    </script>
@endsection
