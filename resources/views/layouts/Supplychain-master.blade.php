<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Chain Panel | TrustCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('image/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/Supplychain.css') }}">
</head>

<body>

    {{-- Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <div class="sidebar" id="mainSidebar">

        <div class="sidebar-header">
            <img src="{{ asset('image/logo-icon.png') }}" alt="TrustCare Logo">
            <h3>TrustCare</h3>
            <span class="supply-tag">Supply Chain Area</span>
        </div>

        {{-- User info --}}
        <div class="sidebar-user-info">
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=1b2d95&color=fff"
                 alt="{{ Auth::user()->name }}">
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">
                    <i class="fas fa-circle"></i> Online
                </div>
            </div>
        </div>

        <ul class="sidebar-menu">

            <li class="menu-section-title">Inventory</li>

            <li>
                <a href="{{ route('supply.dashboard') }}"
                    class="{{ request()->routeIs('supply.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Inventory List</span>
                </a>
            </li>

            <li>
                <a href="{{ route('supply.create') }}"
                    class="{{ request()->routeIs('supply.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Part</span>
                </a>
            </li>

            <li class="menu-section-title">Requests</li>

            <li>
                <a href="{{ route('supplychain.requests') }}"
                    class="{{ request()->routeIs('supplychain.requests') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span>Requests Received</span>
                </a>
            </li>

            <li class="logout-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>

        </ul>
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

                {{-- Bell --}}
                <div class="notif-btn">
                    <i class="fas fa-bell" style="font-size: 15px;"></i>
                    <span class="notif-dot"></span>
                </div>

                {{-- Role badge --}}
                <span class="role-badge">
                    <i class="fas fa-truck" style="margin-right: 5px; font-size: 10px;"></i>
                    Supply Chain
                </span>

                {{-- Avatar --}}
                <div class="top-nav-avatar">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=1b2d95&color=fff"
                         alt="{{ Auth::user()->name }}">
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
