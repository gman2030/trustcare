@extends('layouts.Supplychain-master')
<link rel="stylesheet" href="{{ asset('css/add-product.css') }}">
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="bg-white p-5 shadow-sm" style="border-radius: 30px; border: 1px solid #f0f0f0;">

                <div class="text-center mb-5">
                    <h4 class="fw-bold text-dark">Add New Item</h4>
                    <p class="text-muted small">Fill in the essential details</p>
                </div>

                <form action="{{ route('supply.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="d-flex justify-content-center mb-5">
                        <label for="imageInput" class="upload-square">
                            <input type="file" name="image" id="imageInput" accept="image/*" hidden>
                            <img id="imagePreview" src="" style="display: none;">
                            <div id="plusContent">
                                <i class="fas fa-plus text-muted"></i>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <div class="custom-input-group mb-4">
                            <label class="small fw-bold text-secondary ml-2 mb-1 d-block text-uppercase" style="letter-spacing: 1px;">Product Name</label>
                            <input type="text" name="name" class="modern-input" placeholder="Enter name..." required>
                        </div>

                        <div class="custom-input-group mb-5">
                            <label class="small fw-bold text-secondary ml-2 mb-1 d-block text-uppercase" style="letter-spacing: 1px;">Serial Number</label>
                            <input type="text" name="serial_number" class="modern-input" placeholder="Enter S/N..." required>
                        </div>
                    </div>
                    <br>

                    <button type="submit" class="btn-premium">
                        Save Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('imageInput').onchange = evt => {
        const [file] = document.getElementById('imageInput').files;
        if (file) {
            const preview = document.getElementById('imagePreview');
            const plus = document.getElementById('plusContent');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            plus.style.display = 'none';
        }
    }
</script>
@endsection
