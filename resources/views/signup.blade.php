<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyGuard - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="signup-container">
        <img src="{{ asset('images/logo.png') }}" alt="SkyGuard Logo" class="logo">
        <h1>Create Your Account</h1>
        <form action="{{ route('signup') }}" method="POST">
            @csrf
            <div class="form-group">
                <i class="fa-regular fa-user input-icon left"></i>
                <input type="text" id="name" name="name" placeholder="Username" required>
            </div>
            <div class="form-group">
                <i class="fa-regular fa-envelope input-icon left"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <i class="fa-solid fa-lock input-icon left"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class="fa-regular fa-eye input-icon right"></i>
            </div>
            <div class="form-group">
                <i class="fa-solid fa-lock input-icon left"></i>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                <i class="fa-regular fa-eye input-icon right"></i>
            </div>
            <button type="submit">SIGN UP</button>
        </form>
        <div class="auth-links">
            <p class="signup-text">
                Already have an account?
                <a href="{{ route('login') }}">Login</a>
            </p>
        </div>
    </div>
</body>
</html>
