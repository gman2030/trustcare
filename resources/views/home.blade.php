@extends('layouts.home-master')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Success Alert --}}
    @if (session('success'))
        <div style="background:#dcfce7; color:#166534; padding:14px 20px; border-radius:10px; margin-bottom:20px; border:1px solid #bbf7d0; display:flex; align-items:center; gap:10px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- New Maintenance Request --}}
    <div class="form-card" style="max-width:100%; margin-bottom:30px;">
        <div class="form-header">
            <h2><i class="fas fa-tools" style="margin-right:10px;"></i> New Maintenance Request</h2>
            <p>Fill in the details below to submit a new request</p>
        </div>

        @if ($errors->any())
            <div style="background:#fee2e2; color:#b91c1c; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #f87171;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('send.message') }}" method="POST" enctype="multipart/form-data" id="mainRequestForm">
            @csrf

            {{-- ===== WARRANTY UPLOAD BOX ===== --}}
            <div class="input-group-custom">
                <label><i class="fas fa-id-card" style="margin-right:6px;"></i> 1. Upload Warranty Card (Image)</label>

                <div id="upload-box"
                    onclick="document.getElementById('warranty_image_input').click()"
                    style="
                        width: 100%;
                        height: 200px;
                        border: 2px dashed #93a8d6;
                        border-radius: 16px;
                        background: #f0f4ff;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        position: relative;
                        overflow: hidden;
                    "
                    onmouseover="this.style.borderColor='#1b2d95'; this.style.background='#e8edff';"
                    onmouseout="if(!window.warrantyUploaded){ this.style.borderColor='#93a8d6'; this.style.background='#f0f4ff'; }">

                    {{-- Placeholder (before upload) --}}
                    <div id="upload-placeholder" style="display:flex; flex-direction:column; align-items:center; gap:10px; pointer-events:none;">
                        <div style="
                            width: 56px;
                            height: 56px;
                            border-radius: 50%;
                            background: #1b2d95;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 4px 16px rgba(27,45,149,0.35);
                            transition: transform 0.2s;
                        ">
                            <i class="fas fa-plus" style="color:white; font-size:22px;"></i>
                        </div>
                        <p style="color:#4a5568; font-size:14px; font-weight:600; margin:0;">Click to upload warranty card</p>
                        <p style="color:#94a3b8; font-size:12px; margin:0;">PNG, JPG, JPEG supported</p>
                    </div>

                    {{-- Preview (after upload) --}}
                    <img id="warranty-preview"
                        src="" alt="Warranty Preview"
                        style="
                            display: none;
                            width: 100%;
                            height: 100%;
                            object-fit: contain;
                            border-radius: 14px;
                            padding: 8px;
                            pointer-events: none;
                        ">

                    {{-- Change overlay (after upload) --}}
                    <div id="change-overlay" style="
                        display: none;
                        position: absolute;
                        bottom: 0; left: 0; right: 0;
                        background: rgba(27,45,149,0.72);
                        color: white;
                        text-align: center;
                        padding: 9px;
                        font-size: 13px;
                        font-weight: 600;
                        pointer-events: none;
                    ">
                        <i class="fas fa-sync-alt" style="margin-right:5px;"></i> Click to change image
                    </div>
                </div>

                <input type="file" id="warranty_image_input" name="warranty_image"
                    accept="image/*" required style="display:none;"
                    onchange="previewWarranty(this)">
            </div>
            {{-- ===== END WARRANTY UPLOAD BOX ===== --}}

            {{-- Serial Number --}}
            <div class="input-group-custom">
                <label><i class="fas fa-barcode" style="margin-right:6px;"></i> 2. Enter Product Serial Number</label>
                <div style="display:flex; gap:12px;">
                    <input type="text" id="sn_input" name="serial_number" placeholder="Enter SN..." required
                        style="flex:1; padding:13px 18px; border:2px solid #e2e8f0; border-radius:12px; font-size:15px; background:#f8fafc;">
                    <button type="button" onclick="checkProduct()"
                        style="background:#1b2d95; color:white; border:none; padding:13px 24px; border-radius:12px; font-weight:700; cursor:pointer; font-size:14px; transition:0.3s;"
                        onmouseover="this.style.background='#142170'" onmouseout="this.style.background='#1b2d95'">
                        <i class="fas fa-search" style="margin-right:6px;"></i> Check
                    </button>
                </div>
            </div>

            {{-- Product Preview --}}
            <div id="product-display" style="display:none; margin-bottom:22px; padding:20px; border:1px dashed #cbd5e1; background:#f8fafc; border-radius:12px;">
                <p style="color:#166534; font-weight:700; margin-bottom:12px;"><i class="fas fa-check-circle" style="margin-right:6px;"></i> Product Identified:</p>
                <div style="display:flex; gap:20px; align-items:flex-start;">
                    <img id="p-img" src="" alt="Product" style="width:120px; height:120px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0;">
                    <div>
                        <h4 id="p-name" style="margin-top:0; color:#1e293b; font-size:17px;"></h4>
                        <div id="error-guide" style="display:none; margin-top:10px; font-size:13px; background:white; padding:14px; border-radius:8px; border:1px solid #e2e8f0;">
                            <p style="margin-bottom:8px; color:#b91c1c; font-weight:700;"><i class="fas fa-info-circle" style="margin-right:5px;"></i> Common Error Codes:</p>
                            <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:5px;">
                                <li><strong>E1:</strong> Room temperature sensor</li>
                                <li><strong>E3:</strong> Evaporator temperature sensor</li>
                                <li><strong>E4:</strong> Internal fan motor</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Problem Description --}}
            <div class="input-group-custom">
                <label><i class="fas fa-edit" style="margin-right:6px;"></i> 3. Describe the Problem</label>
                <textarea name="content" rows="4" placeholder="Describe the defect in detail..." required
                    style="padding:13px 18px; border:2px solid #e2e8f0; border-radius:12px; font-size:15px; background:#f8fafc; resize:vertical; font-family:inherit;"></textarea>
            </div>

            <button type="submit" class="btn-submit-custom">
                <i class="fas fa-paper-plane"></i> Submit Final Request
            </button>
        </form>
    </div>

    {{-- Requests History --}}
    <div class="inventory-card">
        <div class="form-header">
            <h2><i class="fas fa-history" style="margin-right:10px;"></i> Your Requests History</h2>
        </div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Warranty</th>
                    <th>Worker</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td><strong>{{ $msg->subject }}</strong></td>
                        <td>
                            <span class="badge-stock {{ $msg->status === 'pending' ? 'status-pending' : ($msg->status === 'done' ? 'status-ok' : 'status-low') }}">
                                {{ $msg->status }}
                            </span>
                        </td>
                        <td>
                            @if ($msg->warranty_image)
                                <a href="{{ route('warranty.view', $message->id) }}" target="_blank">
                                    <i class="fas fa-eye"></i> View Card
                                </a>
                            @endif
                        </td>
                        <td>{{ $msg->worker_name ?? 'Pending...' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#94a3b8; padding:30px;">No requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

@section('scripts')
<script>
    window.warrantyUploaded = false;

    function previewWarranty(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.getElementById('upload-box');
                const placeholder = document.getElementById('upload-placeholder');
                const preview = document.getElementById('warranty-preview');
                const overlay = document.getElementById('change-overlay');

                // Show preview, hide placeholder
                placeholder.style.display = 'none';
                preview.src = e.target.result;
                preview.style.display = 'block';
                overlay.style.display = 'block';

                // Update box style to show it's filled
                box.style.borderColor = '#1b2d95';
                box.style.borderStyle = 'solid';
                box.style.background = '#f8fafc';

                window.warrantyUploaded = true;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function checkProduct() {
        let sn = document.getElementById('sn_input').value.trim();
        if (!sn) { alert("Please enter Serial Number."); return; }

        fetch(`/user/search-product/${sn}`)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('product-display').style.display = 'block';
                    document.getElementById('p-name').innerText = data.product.name;
                    document.getElementById('p-img').src = data.product.image;
                    document.getElementById('error-guide').style.display = sn === "0666456" ? 'block' : 'none';
                } else {
                    alert("Warning: Serial number not found in our database.");
                    document.getElementById('product-display').style.display = 'none';
                }
            })
            .catch(() => alert("Connection error during search."));
    }
</script>
@endsection
