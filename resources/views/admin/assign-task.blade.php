@extends('layouts.admin')

@section('content')
<div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto;">
    <h3 style="color: #1b2d95; border-bottom: 2px solid #e91e63; padding-bottom: 10px;">
        Assign Task to: <span style="color: #e91e63;">{{ $worker->name }}</span>
    </h3>

    <form action="{{ route('admin.tasks.store') }}" method="POST" style="margin-top: 20px;">
        @csrf
        <input type="hidden" name="worker_id" value="{{ $worker->id }}">

        <div style="margin-bottom: 20px;">
            <label style="font-weight: bold; display: block; margin-bottom: 10px;">Select a Customer Request:</label>
            <select name="order_id" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                <option value="">-- Choose a complaint --</option>
                @foreach($pendingOrders as $order)
                    <option value="{{ $order->id }}">
                        Customer: {{ $order->user->name }} | Problem: {{ Str::limit($order->message, 50) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #1b2d95; color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; flex: 1;">
                <i class="fas fa-check"></i> Assign Now
            </button>
            <a href="{{ route('admin.workers') }}" style="background: #ccc; color: black; padding: 12px 20px; text-decoration: none; border-radius: 8px; text-align: center;">Cancel</a>
        </div>
    </form>
</div>
@endsection
