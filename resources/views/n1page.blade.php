<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustCare</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('image/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/n1page.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">Trust<span>Care</span></a>
            <div class="ms-auto">
                <a href="{{ route('login.view') }}" class="btn-main">Login</a>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 fade-up">
                    <h1 class="hero-title">Get peace of mind with <span>TrustCare.</span></h1>
                    <p class="lead text-muted mb-5">
                        A unified platform for providing after-sales services and assisting our customers,
                        all in one sleek interface.
                    </p>
                    <div class="d-flex align-items-center gap-4">
                        <a href="{{ route('login.view') }}" class="btn-main">Get Started</a>
                        <a href="#" class="btn-ghost">
                            <span class="material-symbols-outlined">play_circle</span> Watch the video
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 position-relative fade-up" style="animation-delay: 0.2s;">
                    <div class="hero-image-container">


                        <div class="floating-stats" style="top: 15%; left: -20px;">
                            <span class="material-symbols-outlined text-success">verified</span>
                            <div>
                                <h6 class="m-0 fw-bold">100% Accuracy</h6>
                                <small class="text-muted">Real-time tracking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features-section">
        <div class="container">
            <div class="text-center mb-5 fade-up">
                <h2 class="fw-800 mb-3" style="font-size: 2.5rem;">Why Choose Our System?</h2>
                <p class="text-muted">Engineered for businesses that demand efficiency and speed.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4 fade-up" style="animation-delay: 0.1s;">
                    <div class="feature-card">
                        <div class="icon-box" style="background: #f0f9ff; color: #0369a1;">
                            <span class="material-symbols-outlined">home</span>
                        </div>
                        <h4 class="fw-bold">Field Service</h4>
                        <p class="text-muted">Forget the hassle of transporting bulky or sensitive products.
                            Through our platform, you can request certified technicians to visit your location.
                            We bring the tools and expertise to your doorstep.</p>
                    </div>
                </div>

                <div class="col-md-4 fade-up" style="animation-delay: 0.2s;">
                    <div class="feature-card">
                        <div class="icon-box" style="background: #f0fdf4; color: #16a34a;">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <h4 class="fw-bold">Transparency & Trust</h4>
                        <p class="text-muted">We eliminate concerns about "hidden costs." We provide price lists for spare parts and repair costs via downloadable invoices,
                            ensuring fair transactions and long-term relationships.</p>
                    </div>
                </div>

                <div class="col-md-4 fade-up" style="animation-delay: 0.3s;">
                    <div class="feature-card">
                        <div class="icon-box" style="background: #fff1f2; color: #e11d48;">
                            <span class="material-symbols-outlined">encrypted</span>
                        </div>
                        <h4 class="fw-bold">Enterprise Security</h4>
                        <p class="text-muted">Advanced permissions system ensures only the right people access sensitive
                            data, keeping your warranty information safe.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 bg-dark text-white text-center">
        <div class="container">
            <h3 class="fw-bold mb-4">Ready to organize your assets?</h3>
            <a href="{{ route('login.view') }}" class="btn-main">Start Now for Free</a>
            <hr class="my-5 opacity-25">
            <p class="m-0 opacity-50">© TrustCare. TrustCare – Trust us with the warranty on your devices.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
