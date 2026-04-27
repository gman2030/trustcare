@extends('layouts.admin-master')

@section('content')
    <div class="container" dir="ltr" style="text-align: left; padding: 20px;">
        <h2>Workers Control</h2>

        {{-- جدول العمال --}}
        <table class="table"
            style="width: 100%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="padding: 15px;">Worker Name</th>
                    <th style="padding: 15px; text-align: center;">Control</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workers as $worker)
                    <tr>
                        <td style="padding: 15px;">{{ $worker->name }}</td>
                        <td style="padding: 15px; text-align: center;">
                            {{-- استدعاء المودال --}}
                            <button onclick="openAssignModal('{{ $worker->id }}', '{{ $worker->name }}')"
                                style="background: #1b2d95; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">
                                <i class="fas fa-tasks"></i> Assign Task
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- المودال الوحيد والصحيح --}}
        <div id="assignModal"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
            <div
                style="background:white; width:450px; margin:100px auto; padding:25px; border-radius:12px; position:relative; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                <h3 id="modalWorkerName" style="color: #1b2d95; margin-top: 0;">Assign Task</h3>

                <form action="{{ route('admin.tasks.store') }}" method="POST">
                    @csrf
                    {{-- حقل مخفي لمعرف العامل --}}
                    <input type="hidden" name="worker_id" id="modalWorkerId">

                    <div style="margin-top:15px;">
                        <label style="font-weight: bold;">Select Customer Complaint:</label>
                        <select name="order_id" required
                            style="width:100%; padding:12px; margin-top:10px; border-radius:8px; border: 1px solid #ddd;">
                            <option value="">-- Choose a Complaint --</option>
                            @foreach ($pendingOrders as $order)
                                <option value="{{ $order->id }}">
                                    {{-- هنا يتم عرض اسم الزبون المستخرج من علاقة الـ user --}}
                                    Customer: {{ $order->user->name ?? 'Unknown' }} | Issue:
                                    {{ Str::limit($order->message, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-top:25px; display:flex; gap:10px;">
                        <button type="submit"
                            style="background:#1b2d95; color:white; border:none; padding:10px 20px; border-radius:8px; cursor:pointer; flex: 1; font-weight: bold;">
                            Confirm Assignment
                        </button>
                        <button type="button" onclick="closeAssignModal()"
                            style="background:#eee; color: #333; border:none; padding:10px 20px; border-radius:8px; cursor:pointer;">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- جافا سكريبت --}}
    <script>
        function openAssignModal(id, name) {
            document.getElementById('assignModal').style.display = 'block';
            document.getElementById('modalWorkerId').value = id;
            document.getElementById('modalWorkerName').innerText = "Assign Task to: " + name;
        }

        function closeAssignModal() {
            document.getElementById('assignModal').style.display = 'none';
        }

        // إغلاق المودال عند الضغط خارج الإطار الأبيض
        window.onclick = function(event) {
            var modal = document.getElementById('assignModal');
            if (event.target == modal) {
                closeAssignModal();
            }
        }
    </script>
@endsection
