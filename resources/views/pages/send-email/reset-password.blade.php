<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Atur Ulang Kata Sandi Damaidiri</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div
        style="background-color: #ffffff; margin: 20px auto; padding: 20px; width: 90%; max-width: 600px; border-radius: 8px;">
        <div style="background-color: #20293A; text-align: center; padding: 20px;">
            <h1 style="color: white; margin: 0; font-size: 28px;">
                <strong>Damaidiri</strong>
            </h1>
            <h2 style="color: white;">Atur Ulang Kata Sandi Pengguna Damaidiri</h2>
        </div>
        <div style="font-size: 16px; color: #333; border: 1px solid #20293A; padding: 20px; border-radius: 4px;">
            <p>Halo, <strong>{{ $user }}</strong>!</p>
            <p>Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda.</p>
            <div style="text-align: center; margin-top: 20px; margin-bottom: 20px;">
                <a href="{{ $url }}" target="_blank"
                    style="background-color: #0172BB; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 16px; margin-top: 10px;">Atur Ulang Kata Sandi</a>
            </div>
            <p>
                Jika Anda mengalami masalah saat mengeklik tombol <strong>“Atur Ulang Kata Sandi”</strong>, salin dan tempelkan URL di bawah ini ke dalam browser web Anda:
                <a href="{{ $url }}" target="_blank">{{ $url }}"></a>
            </p>

            <br />

            <p>Tautan pengaturan ulang kata sandi ini akan kedaluwarsa dalam <strong>60 menit</strong>.Jangan pernah bagikan kode OTP kepada siapapun.</p>
            <p style="font-size: 12px; color: #aaaaaa; text-align: center; margin-top: 30px;">
                <strong>Jika Anda tidak meminta kode ini, abaikan email ini.</strong>
            </p>

            <p>Salam hormat,<br /><strong>Damaidiri Team</strong></p>
        </div>
        <div style="background-color: #20293A; text-align: center; font-size: 12px; color: white; padding: 5px;">
            <p>
                &copy; {{ date('Y') }} Damaidiri. Made with ❤️ by
                <a href="https://damaidiri.com" target="_blank" style="color: #F9CF00;">Damaidiri</a>
            </p>
        </div>
    </div>
</body>

</html>
