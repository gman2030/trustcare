@extends('layouts.Supplychain-master')

@section('page-title', 'Inventory Requests')

@section('content')

    <style>
        /* ===== PAGE HEADER ===== */
        .page-header {
            margin-bottom: 28px;
        }

        .page-header h2 {
            color: #1b2d95;
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .page-header p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }

        /* ===== ORDER CARD ===== */
        .order-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: box-shadow 0.2s ease;
        }

        .order-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.09);
        }

        /* Card Top */
        .card-top {
            background: #f8fafc;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .worker-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #1b2d95;
            color: white;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .time-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #94a3b8;
            font-size: 13px;
        }

        /* Machine Info */
        .machine-info {
            padding: 14px 24px;
            color: #1e293b;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .machine-info strong {
            color: #1b2d95;
        }

        .machine-info .sn {
            color: #94a3b8;
            font-size: 13px;
            margin-left: 6px;
        }

        /* Parts Grid */
        .parts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 12px;
            padding: 20px 24px;
        }

        .part-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: border-color 0.2s;
        }

        .part-item:hover {
            border-color: #1b2d95;
        }

        .part-img {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .part-info .part-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .part-info .part-id {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .qty-indicator {
            margin-left: auto;
            font-weight: 800;
            color: #1b2d95;
            background: #eff6ff;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 13px;
            border: 1px solid #bfdbfe;
            flex-shrink: 0;
        }

        /* Action Bar */
        .action-bar {
            padding: 14px 24px;
            background: #fdfdfd;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }

        .btn-prepare {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1b2d95;
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.25s ease;
        }

        .btn-prepare:hover {
            background: #142170;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(27, 45, 149, 0.3);
            color: white;
        }

        .badge-prepared {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #dcfce7;
            color: #166534;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 10px;
            border: 1px solid #bbf7d0;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 40px;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .empty-state i {
            font-size: 52px;
            color: #e2e8f0;
            margin-bottom: 18px;
            display: block;
        }

        .empty-state h3 {
            color: #94a3b8;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #cbd5e1;
            font-size: 14px;
            margin: 0;
        }

        .btn-reject {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: #dc2626;
            border: 2px solid #dc2626;
            padding: 10px 22px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.25s ease;
        }

        .btn-reject:hover {
            background: #fef2f2;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.2);
        }
    </style>

    <div class="page-header">
        <h2><i class="fas fa-boxes" style="margin-right: 10px;"></i> Inventory Requests</h2>
        <p>Pending parts collection and preparation</p>
    </div>

    @forelse($orders as $order)
        <div class="order-card">

            {{-- Top --}}
            <div class="card-top">
                <span class="worker-badge">
                    <i class="fas fa-user-tag"></i>
                    {{ $order->worker->name }}
                </span>
                <span class="time-label">
                    <i class="far fa-clock"></i>
                    {{ $order->created_at->format('M d, H:i A') }}
                </span>
            </div>

            {{-- Machine --}}
            <div class="machine-info">
                <i class="fas fa-microchip" style="color: #f59e0b;"></i>
                Target Machine: <strong>{{ $order->product->name }}</strong>
                <span class="sn">(S/N: {{ $order->product->serial_number }})</span>
            </div>

            {{-- Parts --}}
            <div class="parts-grid">
                @php $items = is_array($order->items) ? $order->items : json_decode($order->items); @endphp
                @foreach ($items as $item)
                    <div class="part-item">
                        <img src="{{ asset('uploads/parts/' . ($item->image ?? $item['image'])) }}" class="part-img">
                        <div class="part-info">
                            <div class="part-name">{{ $item->name ?? $item['name'] }}</div>
                            <div class="part-id">ID: {{ $item->id ?? $item['id'] }}</div>
                        </div>
                        <div class="qty-indicator">x{{ $item->quantity ?? $item['quantity'] }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Action --}}
            <div class="action-bar">
                @if ($order->status == 'accepted')
                    <form action="{{ route('supply.prepare', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-prepare">
                            <i class="fas fa-check-circle"></i>
                            Mark as Prepared
                        </button>
                    </form>
                    {{-- Action --}}
                    <div class="action-bar">
                        @if ($order->status == 'accepted')
                            {{-- زر الرفض --}}
                            <button class="btn-reject" onclick="openRejectModal({{ $order->id }})">
                                <i class="fas fa-times-circle"></i>
                                Reject Request
                            </button>
                        @elseif($order->status == 'prepared')
                            <span class="badge-prepared">
                                <i class="fas fa-box-open"></i>
                                Ready for Pickup — Voucher Active
                            </span>
                        @endif
                    </div>
                @elseif($order->status == 'prepared')
                    <span class="badge-prepared">
                        <i class="fas fa-box-open"></i>
                        Ready for Pickup — Voucher Active
                    </span>
                @endif
            </div>

        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>Warehouse is clear!</h3>
            <p>No pending requests from the Admin.</p>
        </div>
    @endforelse

    {{-- ============================================================
         REJECT MODAL — plain CSS, compact design, English
         ============================================================ --}}
    <style>
        .reject-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(4px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 16px;
        }

        .reject-modal-overlay.active {
            display: flex;
        }

        .reject-modal {
            background: #ffffff;
            border-radius: 18px;
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.15);
            animation: modalSlideUp 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes modalSlideUp {
            from { transform: translateY(20px) scale(0.97); opacity: 0; }
            to   { transform: translateY(0)    scale(1);    opacity: 1; }
        }

        .reject-modal-stripe {
            height: 4px;
            background: linear-gradient(90deg, #dc2626, #f97316, #fbbf24);
        }

        .reject-modal-body {
            padding: 22px 24px 20px;
        }

        .reject-modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .reject-modal-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #fff1f2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
            font-size: 16px;
            flex-shrink: 0;
        }

        .reject-modal-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 2px;
        }

        .reject-modal-header p {
            font-size: 11px;
            color: #9ca3af;
            margin: 0;
        }

        .reject-modal-desc {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.6;
            background: #f9fafb;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 16px;
        }

        .reject-modal-desc strong {
            color: #374151;
            font-weight: 500;
        }

        .reject-modal-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #9ca3af;
            margin-bottom: 6px;
        }

        .reject-modal-select {
            width: 100%;
            appearance: none;
            background: #f9fafb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%239ca3af'/%3E%3C/svg%3E") no-repeat right 12px center;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 9px 32px 9px 12px;
            font-size: 12px;
            color: #111827;
            outline: none;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s;
            margin-bottom: 12px;
            font-family: inherit;
        }

        .reject-modal-select:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            background-color: #fff;
        }

        .reject-modal-textarea {
            width: 100%;
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 12px;
            color: #111827;
            resize: vertical;
            min-height: 76px;
            outline: none;
            margin-bottom: 16px;
            display: block;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
            box-sizing: border-box;
        }

        .reject-modal-textarea::placeholder { color: #d1d5db; }

        .reject-modal-textarea:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            background: #fff;
        }

        .reject-modal-divider {
            height: 1px;
            background: #f3f4f6;
            margin-bottom: 16px;
        }

        .reject-modal-actions {
            display: flex;
            gap: 10px;
        }

        .reject-btn-cancel {
            flex: 1;
            background: #f3f4f6;
            color: #6b7280;
            border: 1.5px solid #e5e7eb;
            border-radius: 50px;
            padding: 9px 16px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s, transform 0.15s;
            font-family: inherit;
        }

        .reject-btn-cancel:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }

        .reject-btn-confirm {
            flex: 2;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 9px 16px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.28);
            transition: background 0.18s, transform 0.15s, box-shadow 0.18s;
            font-family: inherit;
        }

        .reject-btn-confirm:hover {
            background: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.36);
        }
    </style>

    <div id="rejectModalOverlay" class="reject-modal-overlay" onclick="if(event.target===this) closeRejectModal()">
        <div class="reject-modal">

            <div class="reject-modal-stripe"></div>

            <div class="reject-modal-body">

                <div class="reject-modal-header">
                    <div class="reject-modal-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <h3>Reject Order</h3>
                        <p>This action cannot be undone</p>
                    </div>
                </div>

                <p class="reject-modal-desc">
                    Select a rejection reason. If the device is <strong>under warranty</strong>
                    and parts are <strong>unavailable</strong>, the customer will be redirected
                    to the compensation page.
                </p>

                <form id="rejectForm" method="POST">
                    @csrf

                    <label class="reject-modal-label">Rejection Reason</label>
                    <select name="reason" class="reject-modal-select" required>
                        <option value="out_of_stock">Parts Unavailable — Redirect to Compensation</option>
                        <option value="invalid_request">Invalid Request / Incorrect Data</option>
                        <option value="other">Other Reason</option>
                    </select>

                    <label class="reject-modal-label">
                        Notes &nbsp;<span style="font-weight:400;text-transform:none;letter-spacing:0;color:#d1d5db;">(optional)</span>
                    </label>
                    <textarea name="note" class="reject-modal-textarea" placeholder="Add any relevant notes…"></textarea>

                    <div class="reject-modal-divider"></div>

                    <div class="reject-modal-actions">
                        <button type="button" class="reject-btn-cancel" onclick="closeRejectModal()">Cancel</button>
                        <button type="submit" class="reject-btn-confirm">
                            <i class="fas fa-times-circle"></i>
                            Confirm Rejection
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(orderId) {
            document.getElementById('rejectForm').action = `/supply-chain/reject/${orderId}`;
            document.getElementById('rejectModalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            document.getElementById('rejectModalOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRejectModal();
        });
    </script>

@endsection
