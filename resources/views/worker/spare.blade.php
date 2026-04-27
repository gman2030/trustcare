@extends('layouts.worker-master')
<link rel="stylesheet" href="{{ asset('css/spare.css') }}">
@section('content')
<div class="search-container">
    <h2 style="color: #1e293b; font-weight: 800; margin-bottom: 10px;">Asset Identification</h2>
    <p style="color: #64748b; margin-bottom: 30px;">Scan or enter the Serial Number to retrieve product details</p>

    <div class="search-box">
        <i class="fas fa-barcode" style="align-self: center; margin-left: 15px; color: #94a3b8;"></i>
        <input type="text" id="sn-input" placeholder="Scan Serial Number (e.g. SN-1022)...">
        <button class="search-btn" onclick="searchParts()">
            <i class="fas fa-search me-1"></i> Search
        </button>
    </div>

    <div id="result-area">
        <div id="loading" style="display:none; color: #64748b;" class="mb-4">
            <i class="fas fa-spinner fa-spin me-2"></i> Searching...
        </div>

        <div id="product-card-container">
            </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function searchParts() {
    const sn = document.getElementById('sn-input').value;
    const resultArea = document.getElementById('result-area');
    const container = document.getElementById('product-card-container');
    const loading = document.getElementById('loading');

    if(!sn) {
        alert("Please enter a serial number.");
        return;
    }

    resultArea.style.display = 'block';
    loading.style.display = 'block';
    container.innerHTML = '';

    fetch(`/worker/search-product/${sn}`)
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            if(data.error) {
                container.innerHTML = `
                    <div style="padding: 30px; background: #fff1f2; color: #be123c; border-radius: 15px; border: 1px solid #ffe4e6;">
                        <i class="fas fa-exclamation-circle me-2"></i> ${data.error}
                    </div>`;
            } else {
                // بناء البطاقة مع الصورة والرابط
                // ملاحظة: الرابط يفترض وجود مسار تفاصيل المنتج بالـ ID
                const productUrl = `/worker/product-view/${data.id}`;
                const imagePath = data.image ? `/uploads/products/${data.image}` : '/assets/no-image.png';

                container.innerHTML = `
                    <div class="product-result-card">
                        <div class="product-image-wrapper">
                            <img src="${imagePath}" alt="${data.name}">
                        </div>
                        <div class="product-info-wrapper">
                            <span class="status-badge">S/N: ${data.serial_number}</span>
                            <a href="${productUrl}" class="product-name-link">${data.name}</a>
                            <p style="color: #64748b; margin-bottom: 0; font-size: 14px;">
                                <i class="fas fa-tag me-1"></i> ${data.category || 'General Equipment'}
                            </p>
                        </div>
                        <div style="text-align: right;">
                             <a href="${productUrl}" class="btn btn-sm btn-outline-dark rounded-pill">
                                View Parts <i class="fas fa-arrow-right ms-1"></i>
                             </a>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            container.innerHTML = `<p style="color: #ef4444;">Error connecting to server.</p>`;
        });
}
</script>
@endsection
