<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyGuard - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="signup-container">
        <img src="{{ asset('images/logo.png') }}" alt="SkyGuard Logo" class="logo">
        <h1>Create Your Account</h1>
        <form action="{{ route('signup') }}" method="POST">
            @csrf
            <div class="form-group">
                <i class="fa-regular fa-user input-icon left"></i>
                <input type="text" id="name" name="name" placeholder="Username" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <i class="fa-regular fa-envelope input-icon left"></i>
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
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
            <div class="password-criteria">
                <ul class="password-criteria">
                    <li id="length" class="invalid"><i class="fa fa-times"></i> At least 8 characters</li>
                    <li id="uppercase" class="invalid"><i class="fa fa-times"></i> One uppercase letter</li>
                    <li id="lowercase" class="invalid"><i class="fa fa-times"></i> One lowercase letter</li>
                    <li id="digit" class="invalid"><i class="fa fa-times"></i> One digit</li>
                    <li id="special" class="invalid"><i class="fa fa-times"></i> One special character</li>
                </ul>
            </div>
            <button type="submit">SIGN UP</button>
        </form>
        <div class="auth-links">
            <p class="signup-text">
                Already have an account?
                <a href="{{ route('login') }}">Login</a>
            </p>
        </div>
        {{-- Modal --}}
        @if ($errors->any())
        <div class="modal-overlay" id="errorModal">
            <div class="modal-box">
                <div class="modal-icon">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <h2>Registration Failed</h2>
                <ul class="modal-errors">
                    @if ($errors->has('name'))
                        <li><i class="fa fa-times"></i> A user with this username already exists.</li>
                    @endif
                    @if ($errors->has('email'))
                        <li><i class="fa fa-times"></i> A user with this email already exists.</li>
                    @endif
                    @if ($errors->has('password'))
                        <li><i class="fa fa-times"></i> Passwords do not match.</li>
                    @endif
                </ul>
                <button class="modal-close" onclick="closeModal()">OK</button>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
