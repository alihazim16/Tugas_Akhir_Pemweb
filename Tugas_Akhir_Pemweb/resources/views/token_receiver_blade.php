<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f2f5; color: #333; }
        .spinner { border: 4px solid rgba(0,0,0,.1); width: 36px; height: 36px; border-radius: 50%; border-left-color: #09f; animation: spin 1s ease infinite; margin-right: 10px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .message { font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="spinner"></div>
    <div class="message">Mengamankan sesi Anda...</div>

    <script>
        const token = "{{ $token ?? '' }}"; // Ambil token dari variabel Blade
        if (token) {
            localStorage.setItem('jwt_token', token);
            console.log('JWT Token berhasil disimpan ke localStorage dari halaman perantara.');
        } else {
            console.warn('Tidak ada token JWT yang diterima di halaman perantara.');
        }
        // Redirect ke dashboard setelah menyimpan token
        window.location.href = '/dashboard';
    </script>
</body>
</html>