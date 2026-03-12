<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyGuard - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/logo.png') }}" alt="SkyGuard Logo" class="logo">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <i class="fa-regular fa-user input-icon left"></i>
                <input type="text" id="username" name="username" placeholder="Username or Email" required>
            </div>
            <div class="form-group">
                <i class="fa-solid fa-lock input-icon left"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class="fa-regular fa-eye input-icon right"></i>
            </div>
            <button type="submit">LOGIN</button>
        </form>
        <div class="auth-links">
            <p class="signup-text">
                Don't have an account?
                <a href="{{ route('signup') }}">Sign up</a>
            </p>
            <hr class="auth-divider">
            <a class="forgot-link" href="{{ route('forgot-password')}}">
                Forgot password?
            </a>
        </div>
    </div>
</body>
</html>
