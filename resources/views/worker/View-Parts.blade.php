@extends('layouts.worker-master')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/view.css') }}">

    <div class="container">
        <div class="header-info">
            <div class="product-img-box">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </div>
            <div class="product-details">
                <h1>{{ $product->name }}</h1>
                <div class="meta-data">
                    <span>
                        <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">barcode_scanner</span>
                        S/N: <b>{{ $product->serial_number }}</b>
                    </span>
                </div>
            </div>
        </div>

        <h2 style="margin-bottom: 25px; font-weight: 800;">Pièces de rechange</h2>

        <form action="{{ route('worker.spare.confirm', $product->id) }}" method="POST" id="mainPartsForm">
            @csrf
            <div class="parts-grid">
                @forelse($spareParts as $part)
                    <div class="part-card {{ $part->quantity <= 0 ? 'disabled-card' : '' }}">
                        <input type="checkbox" name="selected_parts[]" value="{{ $part->id }}"
                               class="part-checkbox sr-only" {{ $part->quantity <= 0 ? 'disabled' : '' }}>

                        <div class="check-mark">
                            <span class="material-symbols-outlined"></span>
                        </div>

                        <div class="part-img-wrapper">
                            <img src="{{ $part->image_url }}" alt="{{ $part->name }}">
                        </div>

                        <div class="part-info">
                            <span class="stock-badge {{ $part->quantity > 0 ? 'in-stock' : 'out-stock' }}">
                                {{ $part->quantity > 0 ? 'Available: ' . $part->quantity : 'Not available' }}
                            </span>

                            <div class="part-name">{{ $part->name }}</div>
                            <div class="part-price">{{ number_format($part->price, 2) }} DZ</div>

                            <div class="quantity-selector" style="display: none; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ddd;">
                                <label style="font-size: 12px; font-weight: bold; display: block; margin-bottom: 5px;">Quantité:</label>
                                <input type="number" name="quantities[{{ $part->id }}]" value="max" min="1"
                                       max="{{ $part->quantity}}" class="qty-input"
                                       onclick="event.stopPropagation();"> </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 50px; color: var(--text-light);">
                        Aucune pièce trouvée.
                    </div>
                @endforelse
            </div>

            <div class="footer-actions">
                <div id="selection-status" style="color: var(--primary-color); font-size: 14px; font-weight: 600;">
                    Veuillez sélectionner les pièces.
                </div>
                <button type="submit" class="btn-confirm" id="submitBtn" disabled>Confirme</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // التعامل مع ضغطة البطاقة
            document.querySelectorAll('.part-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    const checkbox = this.querySelector('.part-checkbox');
                    if (checkbox && !checkbox.disabled) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            // التعامل مع تغيير حالة الـ Checkbox
            document.querySelectorAll('.part-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const card = this.closest('.part-card');
                    const qtySection = card.querySelector('.quantity-selector');
                    const qtyInput = card.querySelector('.qty-input');

                    if (this.checked) {
                        card.classList.add('is-selected');
                        if (qtySection) qtySection.style.display = 'block';
                        if (qtyInput) qtyInput.disabled = false; // تفعيل مدخل الكمية عند الاختيار
                    } else {
                        card.classList.remove('is-selected');
                        if (qtySection) qtySection.style.display = 'none';
                        if (qtyInput) qtyInput.disabled = true; // تعطيله لعدم إرسال بيانات غير مختارة
                    }
                    updateFooterCount();
                });
            });

            function updateFooterCount() {
                const checkedBoxes = document.querySelectorAll('.part-checkbox:checked');
                const count = checkedBoxes.length;
                const footerText = document.querySelector('#selection-status');
                const submitBtn = document.querySelector('#submitBtn');

                if (footerText) {
                    footerText.innerHTML = count > 0 ?
                        `<b>${count}</b> types de pièces sélectionnés` :
                        "Veuillez sélectionner les pièces.";
                }

                // تفعيل أو تعطيل زر التأكيد
                if (submitBtn) {
                    submitBtn.disabled = count === 0;
                    submitBtn.style.opacity = count === 0 ? "0.5" : "1";
                    submitBtn.style.cursor = count === 0 ? "not-allowed" : "pointer";
                }
            }
        });
    </script>
@endsection
