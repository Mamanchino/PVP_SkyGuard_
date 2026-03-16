<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyGuard - Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/forgot-pass.css'])
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="SkyGuard Logo" class="logo">
        <h1>Forgot Password</h1>
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('forgot-password') }}" method="POST">
            @csrf
            <div class="form-group">
                <i class="fa-regular fa-envelope input-icon left"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>