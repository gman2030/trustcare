@extends('layouts.admin-master')

@section('content')
<div class="admin-container" dir="ltr" style="text-align: left; padding: 20px;">

    <div class="header-section" style="margin-bottom: 30px;">
        <h2 style="color: #0f172a; font-weight: 700;">Dashboard <span style="color: #e91e63;">/</span> Customer Requests</h2>
        <p style="color: #64748b; font-size: 14px;">Click on a customer's name to view full details and warranty card.</p>
    </div>

    <div class="table-card" style="background: white; border-radius: 15px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 18px 20px; text-align: left; color: #1b2d95;">Customer Name</th>
                    <th style="padding: 18px 20px; text-align: left; color: #1b2d95;">Serial Number</th>
                    <th style="padding: 18px 20px; text-align: left; color: #1b2d95;">Status</th>
                    <th style="padding: 18px 20px; text-align: center; color: #1b2d95;">Control</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 18px 20px;">
                        {{-- نمرر حقل warranty_image هنا --}}
                        <a href="javascript:void(0)"
                           onclick="openCustomerDetails('{{ addslashes($msg->user->name) }}', '{{ $msg->user->phone }}', '{{ $msg->extracted_sn }}', '{{ addslashes($msg->content) }}', '{{ $msg->warranty_image ? asset('storage/' . $msg->warranty_image) : '' }}')"
                           style="color: #1b2d95; font-weight: 700; text-decoration: none; border-bottom: 1px dashed #e91e63;">
                            {{ $msg->user->name }}
                        </a>
                    </td>
                    <td style="padding: 18px 20px; font-weight: 600; color: #e91e63;">
                        {{ $msg->extracted_sn ?? 'N/A' }}
                    </td>
                    <td style="padding: 18px 20px;">
                        <span style="background: rgba(27, 45, 149, 0.1); color: #1b2d95; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                            {{ $msg->status }}
                        </span>
                    </td>
                    <td style="padding: 18px 20px; text-align: center;">
                        <form action="{{ route('admin.user.delete', $msg->user_id) }}" method="POST" onsubmit="return confirm('Delete user?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer;">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align: center; padding: 30px;">No requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- النافذة المنبثقة المحدثة --}}
<div id="detailsModal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px);">
    <div style="background: white; margin: 5% auto; padding: 25px; width: 45%; border-radius: 15px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 85vh; overflow-y: auto;">
        <span onclick="closeModal()" style="position: absolute; right: 20px; top: 15px; font-size: 28px; cursor: pointer; color: #94a3b8;">&times;</span>

        <h3 style="color: #1b2d95; margin-bottom: 20px; border-bottom: 2px solid #f8fafc; padding-bottom: 10px;">Customer Request Details</h3>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1; line-height: 1.8;">
                <p><strong><i class="fas fa-user"></i> Name:</strong> <span id="m_name"></span></p>
                <p><strong><i class="fas fa-phone"></i> Phone:</strong> <span id="m_phone"></span></p>
                <p><strong><i class="fas fa-barcode"></i> Serial:</strong> <span id="m_serial" style="color: #e91e63; font-weight: bold;"></span></p>
            </div>
            <div style="flex: 1; background: #f8fafc; padding: 10px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <strong><i class="fas fa-comment-dots"></i> Complaint:</strong>
                <p id="m_complaint" style="font-size: 14px; color: #475569; margin-top: 5px;"></p>
            </div>
        </div>

        {{-- قسم بطاقة الضمان --}}
        <div style="margin-top: 20px;">
            <strong style="display: block; margin-bottom: 10px;"><i class="fas fa-image"></i> Warranty Card Image:</strong>
            <div style="text-align: center; background: #eee; border-radius: 10px; padding: 10px; min-height: 150px; display: flex; align-items: center; justify-content: center;">
                <img id="m_image" src="" alt="Warranty Card" style="max-width: 100%; max-height: 300px; border-radius: 8px; display: none; cursor: zoom-in;" onclick="window.open(this.src)">
                <p id="m_no_image" style="color: #94a3b8; display: none;">No image uploaded for this request.</p>
            </div>
        </div>

        <button onclick="closeModal()" style="margin-top: 25px; width: 100%; padding: 12px; background: #1b2d95; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Close Details</button>
    </div>
</div>

<script>
function openCustomerDetails(name, phone, serial, complaint, imageUrl) {
    document.getElementById('m_name').innerText = name;
    document.getElementById('m_phone').innerText = phone;
    document.getElementById('m_serial').innerText = serial;
    document.getElementById('m_complaint').innerText = complaint;

    const imgTag = document.getElementById('m_image');
    const noImgText = document.getElementById('m_no_image');

    if (imageUrl && imageUrl.trim() !== "" && !imageUrl.endsWith('/storage/')) {
        imgTag.src = imageUrl;
        imgTag.style.display = 'inline-block';
        noImgText.style.display = 'none';
    } else {
        imgTag.style.display = 'none';
        noImgText.style.display = 'block';
    }

    document.getElementById('detailsModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('detailsModal')) closeModal();
}
</script>
@endsection
