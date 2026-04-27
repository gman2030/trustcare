@extends('layouts.admin-master') 

@section('content')
<div class="form-card">
    <div class="form-header">
        <h2><i class="fas fa-user-plus"></i> Add New Worker</h2>
        <p>Create a dedicated account for a maintenance technician</p>
    </div>

    @if(session('success'))
        <div style="color: green; margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.workers.store') }}" method="POST">
        @csrf
        <div class="input-group-custom">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter worker name" required>
            @error('name') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div class="input-group-custom">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="worker@trustcare.com" required>
            @error('email') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div class="input-group-custom">
            <label>Password</label>
            <input type="password" name="password" placeholder="Minimum 8 characters" required>
            @error('password') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn-submit-custom">
            <i class="fas fa-check-circle"></i> Create Worker Account
        </button>
    </form>
</div>
@endsection
