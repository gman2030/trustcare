@extends('layouts.Supplychain-master')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@section('content')
    {{-- ربط خط Inter و Material Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="header-mono mb-5 d-flex justify-content-between align-items-center pb-3">
                    <div>
                        <h2 class="fw-bold text-dark m-0">Asset Configuration</h2>
                        <small class="text-muted">Manage product details and components</small>
                    </div>
                </div>
                <br>
                <div class="row g-4 justify-content-center">
                    {{-- القسم الأيسر: معلومات المنتج الأساسية --}}
                    <div class="col-lg-5">
                        <div class="mono-card shadow-sm border-0">
                            <div class="card-body p-4 p-xl-5">
                                <h5 class="fw-bold text-dark mb-4 text-center">General Information</h5>

                                <form action="{{ route('supply.update', $product->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="avatar-upload mb-4">
                                        <div class="avatar-preview mx-auto">
                                            <img id="main-img" src="{{ asset('uploads/products/' . $product->image) }}">
                                            <label for="main-upload" class="upload-badge-mono">
                                                <i class="fas fa-camera"></i>
                                                <input type="file" name="image" id="main-upload" hidden
                                                    onchange="previewMain(this)">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mono-field mb-3">
                                        <label>Serial Number</label>
                                        <input type="text" name="serial_number" value="{{ $product->serial_number }}"
                                            placeholder="S/N-00000">
                                    </div>

                                    <div class="mono-field mb-5">
                                        <label>Product Label</label>
                                        <input type="text" name="name" value="{{ $product->name }}"
                                            placeholder="Asset Name">
                                    </div>
                                    <br>

                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <button type="submit" class="btn-mono-light px-5">Save Changes</button>
                                        <button type="button" class="btn-mono-light px-5"
                                            onclick="toggleSpareParts()">Manage Spare Parts</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- القسم الأيمن: إدارة قطع الغيار --}}
                    <div class="col-lg-7" id="sparePartsSection" style="display: none;">
                        <div class="mono-card shadow-sm border-0 h-100 animate-fade-in">
                            <div class="card-body p-4 p-xl-5">
                                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                                    <h2 class="fw-bold text-dark m-0">
                                        <i class="fas fa-tools me-2 opacity-50"></i>Spare Parts
                                    </h2>
                                </div>

                                {{-- 1. فورم إضافة قطعة غيار جديدة --}}
                                <form action="{{ route('spare-parts.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="row g-4">
                                        <div class="col-12 text-center mb-2">
                                            <div class="image-drop-mono mx-auto shadow-sm"
                                                style="width: 110px; height: 110px; position: relative;">
                                                <input type="file" name="part_image" id="part-upload" hidden
                                                    onchange="previewPart(this)">
                                                <label for="part-upload"
                                                    style="width: 110px; height: 110px; border: 2px dashed #3e04eb; cursor: pointer; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; background: #f9f9f9;">
                                                    <img id="part-preview-img" src="#"
                                                        style="display:none; max-width: 100%; max-height: 100%; object-fit: contain;">
                                                    <span id="plus-icon" style="font-size: 2rem; color: #1a1af3;">+</span>
                                                </label>
                                            </div>
                                            <p class="text-muted small mt-2">Upload Part Image</p>
                                        </div>

                                        <div class="col-12">
                                            <div class="mono-field">
                                                <label class="form-label small fw-bold text-uppercase">Component
                                                    Name</label>
                                                <input type="text" name="part_name" placeholder="e.g. Engine Valve"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mono-field">
                                                <label class="form-label small fw-bold text-uppercase">Quantity</label>
                                                <input type="number" name="quantity" value="0" min="0"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mono-field">
                                                <label class="form-label small fw-bold text-uppercase">Price (Unit)</label>
                                                <input type="number" name="price" step="0.01" value="0.00"
                                                    min="0" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-12 text-center mt-4">
                                            <button type="submit" class="btn-mono-light w-100 py-3 shadow-sm">
                                                <i class="fas fa-plus-circle me-2"></i>ADD_TO_INVENTORY
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <br>

                                {{-- =============================================
                                     جدول Existing Components - تصميم جديد
                                     ============================================= --}}
                                <div class="parts-list-mono border-top mt-5 pt-4">

                                    {{-- عنوان القسم --}}
                                    <div class="ec-section-header">
                                        <h3 class="ec-section-title">
                                            Existing Components
                                            <span class="ec-count">{{ $product->spareParts->count() }} Items</span>
                                        </h3>
                                    </div>

                                    <form action="{{ route('spare-parts.bulkUpdate') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        {{-- جدول بتصميم جديد --}}
                                        <div class="ec-table-wrapper">
                                            <table class="ec-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width:70px;">Image</th>
                                                        <th>Part Name</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Unit Price (DZ)</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($product->spareParts as $part)
                                                        <tr class="ec-row">
                                                            {{-- صورة القطعة --}}
                                                            <td>
                                                                <div class="ec-img"
                                                                    style="background-image: url('{{ $part->image ? asset('uploads/parts/' . $part->image) : asset('assets/no-image.png') }}')">
                                                                </div>
                                                            </td>

                                                            {{-- الاسم --}}
                                                            <td>
                                                                <span class="ec-part-name">{{ $part->name }}</span>
                                                            </td>

                                                            {{-- الكمية قابلة للتعديل --}}
                                                            <td class="text-center">
                                                                <input type="number"
                                                                    name="existing_parts[{{ $part->id }}][quantity]"
                                                                    value="{{ $part->quantity }}" min="0"
                                                                    class="ec-input" onclick="event.stopPropagation();">
                                                            </td>

                                                            {{-- السعر قابل للتعديل --}}
                                                            <td class="text-center">
                                                                <input type="number"
                                                                    name="existing_parts[{{ $part->id }}][price]"
                                                                    value="{{ $part->price }}" step="0.01"
                                                                    min="0" class="ec-input"
                                                                    onclick="event.stopPropagation();">
                                                            </td>

                                                            {{-- الحالة --}}
                                                            <td class="text-center">
                                                                @if ($part->quantity >= 7)
                                                                    <span class="ec-badge ec-available">
                                                                        <span class="ec-dot"></span> Available
                                                                    </span>
                                                                @elseif($part->quantity >= 5)
                                                                    <span class="ec-badge ec-limited">
                                                                        <span class="ec-dot"></span> Low Stock
                                                                    </span>
                                                                @elseif($part->quantity >= 1)
                                                                    <span class="ec-badge ec-limitede">
                                                                        <span class="ec-dot"></span> Limited
                                                                    </span>
                                                                @else
                                                                    <span class="ec-badge ec-unavailable">
                                                                        <span class="ec-dot"></span> Unavailable
                                                                    </span>
                                                                @endif
                                                            </td>

                                                            {{-- زر الحذف --}}
                                                            <td class="text-right">
                                                                <button type="button" class="ec-delete-btn"
                                                                    onclick="deletePart({{ $part->id }})"
                                                                    title="Delete Component">
                                                                    <svg class="ec-trash-icon" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"></path>
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="ec-empty">
                                                                <i
                                                                    class="fas fa-box-open fa-2x mb-2 d-block opacity-25"></i>
                                                                No components linked yet.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- footer الجدول --}}
                                        <div class="ec-table-footer">
                                            <p class="ec-table-count">
                                                Showing {{ $product->spareParts->count() }} components
                                            </p>
                                            @if ($product->spareParts->count() > 0)
                                                <button type="submit" class="ec-save-btn">
                                                    Save Changes
                                                </button>
                                            @endif
                                        </div>

                                    </form>
                                </div>
                                {{-- نهاية جدول Existing Components --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <form id="global-delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        /* الـ JavaScript الخاص بك كما هو دون أي تغيير */
        function toggleSpareParts() {
            const section = document.getElementById('sparePartsSection');
            section.style.display = (section.style.display === "none") ? "block" : "none";
        }

        function previewMain(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = e => document.getElementById('main-img').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewPart(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('part-preview-img');
                    const icon = document.getElementById('plus-icon');
                    img.src = e.target.result;
                    img.style.display = 'block';
                    icon.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function deletePart(id) {
            if (confirm('Are you sure?')) {
                let form = document.getElementById('global-delete-form');
                form.action = '/spare-parts/' + id;
                form.submit();
            }
        }
    </script>
@endsection
