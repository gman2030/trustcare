<!-- resources/views/login-sign up.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustCare</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
        <div class="front">
            <img src="image/login page.PNG" alt="">
            <div class="text">
                <span class="text-1">Trust us with the warranty on your devices</span><br>
                <span class="text-2">If you do not have an account, create one now</span>
            </div>
        </div>
        <div class="back">
            <img class="backImg" src="image/sing up page2.jpg" alt="">
            <div class="text">
                <span class="text-3">Trust us with the warranty on your devices</span><br>
                <span class="text-4">Create an account now and join our community</span>
            </div>
        </div>
    </div>

    <div class="form-container">
        <!-- LOGIN FORM -->
        <div class="login-form">
            <div class="title">Login</div>

            @if ($errors->any())
                <div class="text login-text" style="color:red; text-align:center;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="input-boxes">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="input-box">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="button input-box">
                        <input type="submit" value="Login">
                    </div>
                </form>
                <div class="text sign-up-text">
                    Don't have an account?
                    <label for="flip">Sign up now</label>
                </div>
            </div>
        </div>

        <!-- SIGN UP FORM -->
        <div class="signup-form">
            <div class="title">Sign up</div>

            @if(session('success'))
                <div class="text sign-up-text" style="color:green; text-align:center;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="input-boxes">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="input-box">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" placeholder="Enter your username" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" placeholder="Phone number 213" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="button input-box">
                        <input type="submit" value="Sign up">
                    </div>
                </form>
                <div class="text sign-up-text">
                    Already have an account?
                    <label for="flip">Login now</label>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
