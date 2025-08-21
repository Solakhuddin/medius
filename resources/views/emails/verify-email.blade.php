<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
</head>
<body>
    <h2>Halo {{ $user->name }},</h2>
    <p>Terima kasih sudah mendaftar di {{ config('app.name') }}.</p>
    <p>Silakan klik link berikut untuk verifikasi email kamu:</p>
    <p>
        <a href="{{ $verificationUrl }}">
            Verifikasi Email
        </a>
    </p>
    <p>Jika kamu tidak membuat akun ini, abaikan email ini.</p>
</body>
</html>
