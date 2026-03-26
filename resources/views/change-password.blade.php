<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyGuard - Change Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/reset-pass.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <a href="{{ route('add-drone') }}" class="logo-link">
            <img src="{{ asset('images/logo.png') }}" alt="SkyGuard Logo" class="logo">
        </a>
        <h1>Change Password</h1>
        <form action="{{ route('change-password') }}" method="POST">
            @csrf

            <div class="form-group">
                <i class="fa-solid fa-lock input-icon left"></i>
                <input type="password" name="current_password" id="current_password" placeholder="Current Password" required>
                <i class="fa-regular fa-eye input-icon right"></i>
            </div>
            <div class="form-group">
                <i class="fa-solid fa-lock input-icon left"></i>
                <input type="password" name="password" id="password" placeholder="New Password" required>
                <i class="fa-regular fa-eye input-icon right"></i>
            </div>
            <div class="form-group">
                <i class="fa-solid fa-lock input-icon left"></i>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
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
            <button type="submit">Change Password</button>
        </form>
        {{-- Modal --}}
        @if ($errors->any() || session('error'))
        <div class="modal-overlay" id="errorModal">
            <div class="modal-box">
                <div class="modal-icon">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <h2>Registration Failed</h2>
                <ul class="modal-errors">
                    @if ($errors->has('password'))
                        <li><i class="fa fa-times"></i> Passwords do not match.</li>
                    @endif
                    @if (session('error') == 'same_password')
                        <li><i class="fa fa-times"></i> New password must be different from your current password.</li>
                    @endif
                    @if (session('error') == 'invalid_token')
                        <li><i class="fa fa-times"></i> Invalid or expired reset link.</li>
                    @endif
                </ul>
                <button class="modal-close" onclick="closeModal()">OK</button>
            </div>
        </div>
        @endif
    </div>
</body>
</html>