<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel | TrustCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('image/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>

    {{-- Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <div class="sidebar" id="mainSidebar">
        <div class="sidebar-header">
            <img src="{{ asset('image/logo-icon.png') }}" alt="TrustCare Logo">
            <h3>TrustCare</h3>
            <small>User Area</small>
        </div>

        {{-- User quick info --}}
        <div class="sidebar-user-info">
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=1b2d95&color=fff"
                alt="{{ Auth::user()->name }}">
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">User</div>
            </div>
        </div>

        <ul class="sidebar-menu">

            <li class="menu-section-title">Main</li>

            <li>
                <a class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a class="{{ request()->routeIs('user.invoice') ? 'active' : '' }}" href="{{ route('thebill') }}">
                    <i class="fas fa-file-invoice"></i>
                    <span>The Bill</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('Proposedsolutions') }}">
                    <i class="fas fa-lightbulb me-2"></i>
                    Proposed solutions
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
                {{-- Notification --}}
                <div class="notif-btn">
                    <i class="fas fa-bell" style="font-size: 15px;"></i>
                    <span class="notif-dot"></span>
                </div>

                {{-- Role --}}
                <span class="role-badge">
                    <i class="fas fa-user" style="margin-right: 5px; font-size: 10px;"></i>
                    User
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
