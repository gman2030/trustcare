<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | TrustCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('image/logo-icon.png') }}">
    <style>
        :root {
            --primary: #1b2d95;
            --primary-dark: #142170;
            --secondary: #e91e63;
            --bg-light: #f0f2f5;
            --sidebar-width: 260px;
            --text-muted: #64748b;
            --text-dark: #1e293b;
            --border: #e2e8f0;
            --white: #ffffff;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            --radius: 14px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-light);
            display: flex;
            min-height: 100vh;
        }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 200;
            box-shadow: 4px 0 20px rgba(27, 45, 149, 0.25);
            transform: translateX(0);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 150;
            backdrop-filter: blur(2px);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .sidebar-overlay.visible {
            opacity: 1;
            pointer-events: all;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header img {
            height: 50px;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.2));
        }

        .sidebar-header h3 {
            margin-top: 12px;
            font-size: 20px;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .sidebar-header small {
            color: #94a3b8;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* User Info Block inside sidebar */
        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            margin: 16px 14px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.25);
        }

        .sidebar-user-info .user-name {
            font-size: 14px;
            font-weight: 600;
            color: white;
        }

        .sidebar-user-info .user-role {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .online-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            flex-shrink: 0;
        }

        /* Menu */
        .sidebar-menu {
            list-style: none;
            padding: 10px 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 2px;
        }

        .sidebar-menu .menu-section-title {
            padding: 10px 25px 6px;
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 600;
        }

        .sidebar-menu li a {
            padding: 13px 25px;
            display: flex;
            align-items: center;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.25s ease;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 15px;
            border-left: 4px solid transparent;
            position: relative;
        }

        .sidebar-menu li i {
            margin-right: 14px;
            font-size: 17px;
            width: 22px;
            text-align: center;
            transition: transform 0.2s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 4px solid var(--secondary);
        }

        .sidebar-menu li a:hover i,
        .sidebar-menu li a.active i {
            transform: scale(1.15);
        }

        /* Notification badge */
        .menu-notif-badge {
            margin-left: auto;
            background: var(--secondary);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            line-height: 1.6;
        }

        /* Logout */
        .logout-form {
            padding: 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            background: none;
            border: none;
            color: #f87171;
            padding: 13px 25px;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-size: 15px;
            font-family: inherit;
            display: flex;
            align-items: center;
            transition: all 0.25s ease;
            border-left: 4px solid transparent;
        }

        .logout-btn i {
            margin-right: 14px;
            font-size: 17px;
            width: 22px;
            text-align: center;
        }

        .logout-btn:hover {
            background: rgba(248, 113, 113, 0.1);
            color: #fca5a5;
            border-left: 4px solid #f87171;
        }

        /* ===================== MAIN CONTENT ===================== */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            padding: 30px;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                width 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }

        /* ===================== TOP NAV ===================== */
        .top-nav {
            background: var(--white);
            padding: 14px 24px;
            border-radius: var(--radius);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border);
        }

        .top-nav-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .menu-toggle-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0f4ff;
            border: 1.5px solid #c7d2fe;
            color: var(--primary);
            font-weight: 700;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            letter-spacing: 0.5px;
        }

        .menu-toggle-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(27, 45, 149, 0.25);
        }

        .menu-toggle-btn i {
            font-size: 15px;
            transition: transform 0.3s ease;
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .breadcrumb-nav strong {
            color: var(--primary);
        }

        .breadcrumb-nav .separator {
            color: #cbd5e1;
        }

        .top-nav-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .notif-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--bg-light);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s;
            text-decoration: none;
        }

        .notif-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .notif-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary);
            color: white;
            font-size: 9px;
            font-weight: 800;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--white);
        }

        .role-badge {
            background: #eff6ff;
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid #bfdbfe;
            letter-spacing: 0.5px;
        }

        .top-nav-avatar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            border-radius: 30px;
            border: 1px solid var(--border);
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s;
        }

        .top-nav-avatar:hover {
            border-color: var(--primary);
            background: #eff6ff;
        }

        .top-nav-avatar .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: white;
        }

        .top-nav-avatar span {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-dark);
        }

        .content-area {
            flex: 1;
        }

        /* ===================== CARDS ===================== */
        .form-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            border: 1px solid var(--border);
        }

        .inventory-card {
            background: var(--white);
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-top: 20px;
            border: 1px solid var(--border);
        }

        .form-header {
            margin-bottom: 30px;
            border-bottom: 2px solid var(--bg-light);
            padding-bottom: 20px;
        }

        .form-header h2 {
            color: var(--primary);
            font-weight: 700;
            font-size: 26px;
            margin-bottom: 6px;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        /* ===================== TABLE ===================== */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .custom-table thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            padding: 14px 16px;
            border: none;
            letter-spacing: 0.8px;
        }

        .custom-table tbody tr {
            background-color: var(--white);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .custom-table tbody tr:hover {
            transform: scale(1.005);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .custom-table td {
            padding: 14px 16px;
            vertical-align: middle;
            color: #2d3748;
            border-top: 1px solid #edf2f7;
            border-bottom: 1px solid #edf2f7;
        }

        /* ===================== BADGES ===================== */
        .badge-stock {
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-ok {
            background: #dcfce7;
            color: #166534;
        }

        .status-low {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        /* ===================== BUTTONS ===================== */
        .btn-edit-action {
            background-color: #f1f5f9;
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-edit-action:hover {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(27, 45, 149, 0.2);
        }

        /* ===================== FORM INPUTS ===================== */
        .input-group-custom {
            margin-bottom: 22px;
            display: flex;
            flex-direction: column;
        }

        .input-group-custom label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-group-custom input,
        .input-group-custom select,
        .input-group-custom textarea {
            padding: 13px 18px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8fafc;
            color: var(--text-dark);
            font-family: inherit;
        }

        .input-group-custom input:focus,
        .input-group-custom select:focus,
        .input-group-custom textarea:focus {
            border-color: var(--primary);
            background: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(27, 45, 149, 0.08);
        }

        .btn-submit-custom {
            background: var(--primary);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            font-family: inherit;
        }

        .btn-submit-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(27, 45, 149, 0.25);
        }

        /* ===================== STATS GRID ===================== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #eff6ff;
            color: var(--primary);
        }

        .stat-icon.pink {
            background: #fdf2f8;
            color: var(--secondary);
        }

        .stat-icon.green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .stat-icon.amber {
            background: #fffbeb;
            color: #d97706;
        }

        .stat-info .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .stat-info .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 2px;
        }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.hidden {
                transform: translateX(-100%);
            }

            .sidebar.visible-mobile {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 16px;
            }

            .role-badge {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <div class="sidebar" id="mainSidebar">

        <div class="sidebar-header">
            <img src="{{ asset('image/logo-icon.png') }}" alt="TrustCare Logo">
            <h3>TrustCare</h3>
            <small>Admin Panel</small>
        </div>

        {{-- Admin user info --}}
        <div class="sidebar-user-info">
            <div class="admin-avatar-circle">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">
                    <span class="online-dot"></span> Online
                </div>
            </div>
        </div>

        <ul class="sidebar-menu">

            <li class="menu-section-title">Management</li>

            <li>
                <a href="{{ route('admin.home') }}" class="{{ request()->routeIs('admin.home') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.workers') }}"
                    class="{{ request()->routeIs('admin.workers') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>Workers Control</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.workers.create') }}"
                    class="{{ request()->routeIs('admin.workers.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-square"></i> Add Worker
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}"
                    class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                    <i class="fas fa-tools"></i>
                    <span>Spare Parts Requests</span>
                    @php $count = Auth::user()->unreadNotifications->count(); @endphp
                    @if ($count > 0)
                        <span class="menu-notif-badge">{{ $count }}</span>
                    @endif
                </a>
            </li>

        </ul>

        <div class="logout-form">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="main-content" id="mainContent">

        {{-- Top Navigation Bar --}}
        <div class="top-nav">
            <div class="top-nav-left">
                <button class="menu-toggle-btn" onclick="toggleSidebar()" id="menuToggleBtn">
                    <i class="fas fa-bars" id="menuToggleIcon"></i>
                    Menu
                </button>
                <div class="breadcrumb-nav">
                    <i class="fas fa-home" style="color: #94a3b8;"></i>
                    <span class="separator">/</span>
                    <strong>@yield('page-title', 'Dashboard')</strong>
                </div>
            </div>

            <div class="top-nav-right">

                {{-- Notifications bell --}}
                @php $notifCount = Auth::user()->unreadNotifications->count(); @endphp
                <a href="{{ route('admin.orders.index') }}" class="notif-btn">
                    <i class="fas fa-bell" style="font-size: 15px;"></i>
                    @if ($notifCount > 0)
                        <span class="notif-count">{{ $notifCount }}</span>
                    @endif
                </a>

                {{-- Role badge --}}
                <span class="role-badge">
                    <i class="fas fa-shield-alt" style="margin-right: 5px; font-size: 10px;"></i>
                    Administrator
                </span>

                {{-- Avatar --}}
                <div class="top-nav-avatar">
                    <div class="avatar-circle">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                </div>

            </div>
        </div>

        {{-- Page Content --}}
        <div class="content-area">
            @yield('content')
        </div>

    </div>

    @yield('scripts')

    <script>
        let sidebarOpen = true;

        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const icon = document.getElementById('menuToggleIcon');

            sidebarOpen = !sidebarOpen;

            if (sidebarOpen) {
                sidebar.classList.remove('hidden');
                mainContent.classList.remove('expanded');
                overlay.classList.remove('visible');
                icon.style.transform = 'rotate(0deg)';
            } else {
                sidebar.classList.add('hidden');
                mainContent.classList.add('expanded');
                overlay.classList.add('visible');
                icon.style.transform = 'rotate(90deg)';
            }
        }
    </script>

</body>

</html>
