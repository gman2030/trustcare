@extends('layouts.admin-master')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@section('content')


    <div class="page-title-box">
        <h2><i class="fas fa-tray"></i> Incoming Requests</h2>
    </div>

    @forelse($orders as $order)
        <div class="order-ticket">
            <div class="ticket-header">
                <div class="worker-profile">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <span class="worker-name">Worker: {{ $order->worker->name ?? 'N/A' }}</span>
                        <div style="font-size: 13px; color: #1b2d95; font-weight: bold; margin-top: 2px;">
                            <i class="fas fa-id-card"></i> Customer:
                            <span style="color: #e91e63;">
                                {{ $order->user->name ?? 'Unknown Customer' }}
                            </span>
                        </div>

                        <span class="time-stamp"><i class="far fa-clock"></i>
                            {{ $order->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="ticket-id" style="color: var(--text-muted); font-size: 13px;">
                    Order #{{ $order->id }}
                </div>
            </div>

            <div class="machine-info">
                Machine Target: <span>{{ $order->product->name ?? 'Unknown' }}</span>
            </div>

            <div class="items-container">
                <table class="custom-grid-table">
                    <thead>
                        <tr>
                            <th>Part</th>
                            <th>Description</th>
                            <th style="text-align: center;">Required Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $items = is_array($order->items) ? $order->items : json_decode($order->items); @endphp
                        @foreach ($items as $item)
                            <tr>
                                <td style="width: 70px;">
                                    <img src="{{ \App\Support\PublicImageUrl::fromPath($item->image ?? $item['image'] ?? null) }}"
                                        class="part-img">
                                </td>
                                <td>
                                    <div class="part-name-text">{{ $item->name ?? $item['name'] }}</div>
                                    <div style="font-size: 11px; color: #a0aec0;">ID: {{ $item->id ?? $item['id'] }}
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="qty-box">{{ $item->quantity ?? $item['quantity'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <form action="{{ route('admin.orders.accept', $order->id) }}" method="POST">
                @csrf

                <style>
                    .warranty-toggle-wrap {
                        margin-bottom: 14px;
                    }

                    .warranty-toggle-label {
                        display: flex;
                        align-items: flex-start;
                        gap: 14px;
                        padding: 14px 16px;
                        border: 1.5px solid #e2e8f0;
                        border-radius: 12px;
                        background: #f8fafc;
                        cursor: pointer;
                        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
                        user-select: none;
                    }

                    .warranty-toggle-label:hover {
                        border-color: #1b2d95;
                        background: #f0f4ff;
                        box-shadow: 0 2px 10px rgba(27, 45, 149, 0.08);
                    }

                    .warranty-checkbox {
                        display: none;
                    }

                    .warranty-checkbox:checked+.warranty-toggle-label {
                        border-color: #1b2d95;
                        background: #eef2ff;
                        box-shadow: 0 2px 12px rgba(27, 45, 149, 0.12);
                    }

                    .warranty-checkbox:checked+.warranty-toggle-label .warranty-icon-wrap {
                        background: #1b2d95;
                        color: #fff;
                    }

                    .warranty-checkbox:checked+.warranty-toggle-label .warranty-custom-check {
                        background: #1b2d95;
                        border-color: #1b2d95;
                    }

                    .warranty-checkbox:checked+.warranty-toggle-label .warranty-custom-check::after {
                        opacity: 1;
                    }

                    .warranty-icon-wrap {
                        width: 38px;
                        height: 38px;
                        border-radius: 10px;
                        background: #e0e7ff;
                        color: #1b2d95;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 16px;
                        flex-shrink: 0;
                        transition: background 0.2s, color 0.2s;
                    }

                    .warranty-text {
                        flex: 1;
                    }

                    .warranty-text strong {
                        display: block;
                        font-size: 13px;
                        font-weight: 700;
                        color: #1e293b;
                        margin-bottom: 3px;
                    }

                    .warranty-text span {
                        font-size: 11px;
                        color: #94a3b8;
                        line-height: 1.5;
                    }

                    .warranty-custom-check {
                        width: 18px;
                        height: 18px;
                        border-radius: 5px;
                        border: 1.5px solid #cbd5e1;
                        background: #fff;
                        flex-shrink: 0;
                        margin-top: 10px;
                        position: relative;
                        transition: background 0.2s, border-color 0.2s;
                    }

                    .warranty-custom-check::after {
                        content: '';
                        position: absolute;
                        top: 2px;
                        left: 5px;
                        width: 5px;
                        height: 9px;
                        border: 2px solid #fff;
                        border-top: none;
                        border-left: none;
                        transform: rotate(45deg);
                        opacity: 0;
                        transition: opacity 0.15s;
                    }

                    .btn-confirm-send {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 8px;
                        width: 100%;
                        padding: 11px 20px;
                        background: #1b2d95;
                        color: #fff;
                        border: none;
                        border-radius: 10px;
                        font-size: 14px;
                        font-weight: 700;
                        cursor: pointer;
                        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
                        font-family: inherit;
                    }

                    .btn-confirm-send:hover {
                        background: #142170;
                        transform: translateY(-1px);
                        box-shadow: 0 6px 16px rgba(27, 45, 149, 0.3);
                    }
                </style>

                <div class="warranty-toggle-wrap">
                    <input class="warranty-checkbox" type="checkbox" name="is_warranty" value="1"
                        id="warrantyCheck{{ $order->id }}">
                    <label class="warranty-toggle-label" for="warrantyCheck{{ $order->id }}">
                        <div class="warranty-icon-wrap">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="warranty-text">
                            <strong>Enable Warranty Coverage</strong>
                            <span>If parts are unavailable, the customer will be redirected to the compensation page.</span>
                        </div>
                        <div class="warranty-custom-check"></div>
                    </label>
                </div>

                <button type="submit" class="btn-confirm-send">
                    <i class="fas fa-paper-plane"></i>
                    Confirm & Send to Warehouse
                </button>
            </form>

        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-inbox" style="font-size: 50px; margin-bottom: 15px; display: block;"></i>
            <h3>No requests at the moment</h3>
            <p>New orders from workers will appear here.</p>
        </div>
    @endforelse

    @if (Auth::user()->unreadNotifications->count() > 0)
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'New Requests!',
                text: 'You have {{ Auth::user()->unreadNotifications->count() }} new spare parts requests.',
                icon: 'info',
                confirmButtonText: 'Check Now',
                confirmButtonColor: '#1b2d95'
            });
        </script>
        {{-- مسح الإشعارات بعد رؤيتها --}}
        @php Auth::user()->unreadNotifications->markAsRead(); @endphp
    @endif

@endsection
