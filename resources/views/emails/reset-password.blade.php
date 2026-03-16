<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Roboto, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #242B33; border-radius: 10px; padding: 30px; text-align: center;">
        <h2 style="color: #B5B8BF;">Reset Your Password</h2>
        <p style="color: #787C82;">Click the button below to reset your password. This link expires in 1 hour.</p>
        <a href="{{ $resetLink }}"
           style="display: inline-block; padding: 12px 30px; background-color: #305AB8; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0;">
            Reset Password
        </a>
        <p style="color: #555; font-size: 11px;">© SkyGuard</p>
    </div>
</body>
</html>