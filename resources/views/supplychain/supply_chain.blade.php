@extends('layouts.Supplychain-master')
<link rel="stylesheet" href="{{ asset('css/supply-chain.css') }}">
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 p-2 animate-fade-in">
            <div>
                <h3 class="fw-bold text-dark m-0">Inventory Management</h3>
                <p class="text-muted small">Monitor and manage your products and components</p>
            </div>
        </div>
        <br>
        <div class="table-card shadow-sm border-0 animate-slide-up">
            <div class="table-responsive">
                <div class="search-bar-container"
                    style="margin-bottom: 25px; background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <form action="{{ route('supply.dashboard') }}" method="GET"
                        style="display: flex; gap: 10px; align-items: center;">
                        <div style="position: relative; flex: 1;">
                            <i class="fas fa-barcode"
                                style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #1b2d95;"></i>
                            <input type="text" name="search_sn" placeholder="Scan or type Serial Number..."
                                value="{{ request('search_sn') }}"
                                style="width: 100%; padding: 12px 12px 12px 45px; border: 2px solid #e2e8f0; border-radius: 10px; outline: none; transition: border-color 0.3s;">
                        </div>

                        <button type="submit"
                            style="background: #1b2d95; color: white; padding: 12px 25px; border: none; border-radius: 10px; cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-search"></i> Search
                        </button>

                        @if (request('search_sn'))
                            <a href="{{ route('supply.dashboard') }}"
                                style="color: #ef4444; text-decoration: none; font-size: 14px; font-weight: bold;">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        @endif
                    </form>
                </div>
                <table class="table custom-table align-middle m-0">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Product Details</th>
                            <th style="width: 30%;">Serial Number</th>
                            <th style="width:15%; ">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="product-row">
                                <td>
                                    <div class="d-flex align-items-center cursor-pointer group-hover"
                                        onclick="openPartsModal('{{ $product->id }}', '{{ $product->name }}')">
                                        <div class="product-img-box me-3 shadow-sm">
                                            <img src="{{ $product->image ? asset('uploads/products/' . $product->image) : asset('assets/no-image.png') }}"
                                                alt="">
                                        </div>
                                        <div class="product-info">
                                            <a href="{{ route('supply.show', $product->id) }}"
                                                class="text-decoration-none fw-bold text-dark">
                                                {{ $product->name }}
                                            </a>

                                        </div>
                                    </div>
                                </td>
                                <td><code class="serial-tag">{{ $product->serial_number }}</code></td>
                                <td class="text-center">
                                    <a href="{{ route('supply.edit', $product->id) }}" class="btn-action-edit"
                                        title="Edit Product">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editModal" class="custom-modal">
        <div class="modal-content-premium animate-zoom-in">
            <div class="modal-header-clean">
                <h4 class="fw-bold m-0 text-dark"><i class="fas fa-edit me-2 text-primary"></i>Edit Information</h4>
                <span class="close-modal" onclick="closeEditModal()">&times;</span>
            </div>
        </div>
        <script>
            function openEditModal(id, name, serial, imgSrc) {
                document.getElementById('editForm').action = `/supply-chain/update/${id}`;
                document.getElementById('editName').value = name;
                document.getElementById('editSerial').value = serial;
                document.getElementById('editImagePreview').src = imgSrc;
                document.getElementById('editModal').style.display = "block";
            }

            function closeEditModal() {
                document.getElementById('editModal').style.display = "none";
            }



            function previewEditImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => document.getElementById('editImagePreview').src = e.target.result;
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function addSparePartRow() {
                const container = document.getElementById('sparePartsContainer');
                const row = `
            <div class="d-flex gap-2 mb-2 animate-zoom-in align-items-center bg-light p-2 rounded-3">
                <input type="text" class="modern-input-small py-1" placeholder="Part Name" style="flex:2">
                <input type="file" class="form-control form-control-sm" style="flex:1">
                <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
            </div>`;
                container.insertAdjacentHTML('beforeend', row);
            }

            window.onclick = e => {
                if (e.target.className === 'custom-modal') {
                    closeEditModal();
                    closePartsModal();
                }
            }
        </script>
    @endsection
