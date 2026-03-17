<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyGuard - Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/profile.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="SkyGuard Logo" class="logo">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <i class="fa-regular fa-user input-icon left"></i>
                <input type="text" id="username" name="username" value="{{ $user->name }}" readonly>
            </div>
            <div class="form-group">
                <i class="fa-regular fa-envelope input-icon left"></i>
                <input type="text" id="email" name="email" value="{{ $user->email }}" readonly>
            </div>
            <button type="submit">CHANGE PASSWORD</button>
        </form>
    </div>
</body>
</html>