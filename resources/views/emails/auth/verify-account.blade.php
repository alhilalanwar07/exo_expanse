<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun {{ $appName }}</title>
</head>
<body style="margin:0;padding:0;background:#f4ece0;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:linear-gradient(145deg,#f8f1e4,#fffaf2,#f2e5cc);padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#fffdf8;border:1px solid #e5d5b7;border-radius:20px;overflow:hidden;box-shadow:0 20px 40px rgba(122,86,40,0.18);">
                    <tr>
                        <td style="padding:0;">
                            <div style="height:8px;background:linear-gradient(90deg,#9b6a35,#d2a45c,#8b5b2b);"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 30px 10px 30px;text-align:center;">
                            <p style="margin:0;color:#9a6f3f;font-size:12px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;">ExoInvite Account</p>
                            <h1 style="margin:10px 0 0 0;color:#4b2f18;font-size:30px;line-height:36px;font-family:Georgia,'Times New Roman',serif;">Aktivasi Akun Anda</h1>
                            <p style="margin:10px 0 0 0;color:#866a4a;font-size:14px;line-height:22px;">Satu langkah lagi untuk mulai mengelola undangan digital Anda.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 30px 10px 30px;">
                            <p style="margin:0;color:#5f4a32;font-size:15px;line-height:24px;">
                                Halo {{ $recipientName }},
                            </p>
                            <p style="margin:12px 0 0 0;color:#6f5538;font-size:15px;line-height:24px;">
                                Terima kasih sudah mendaftar di {{ $appName }}. Klik tombol di bawah ini untuk mengaktifkan akun Anda dan mulai membuat undangan yang lebih berkesan.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px 8px 30px;text-align:center;">
                            <a href="{{ $verificationUrl }}" style="display:inline-block;padding:14px 30px;border-radius:12px;background:linear-gradient(90deg,#9b6a35,#c9964f,#8b5b2b);color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;box-shadow:0 10px 20px rgba(138,91,43,0.28);">
                                Aktifkan Akun Sekarang
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 30px 0 30px;">
                            <div style="background:#fef7eb;border:1px solid #ecdab9;border-radius:14px;padding:14px 14px;">
                                <p style="margin:0;color:#8b6f4e;font-size:13px;line-height:20px;">
                                    Link aktivasi berlaku selama {{ $expiresInMinutes }} menit.
                                </p>
                                <p style="margin:8px 0 0 0;color:#9a7a55;font-size:12px;line-height:19px;word-break:break-all;">
                                    Jika tombol tidak berfungsi, salin link berikut:<br>
                                    <a href="{{ $verificationUrl }}" style="color:#9b6a35;text-decoration:none;">{{ $verificationUrl }}</a>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px 30px 30px;">
                            <div style="border-top:1px solid #eadcc4;padding-top:14px;">
                                <p style="margin:0;color:#9d8766;font-size:12px;line-height:18px;text-align:center;">
                                    Jika Anda tidak merasa melakukan pendaftaran, email ini dapat diabaikan.
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0;">
                            <div style="height:8px;background:linear-gradient(90deg,#9b6a35,#d2a45c,#8b5b2b);"></div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
