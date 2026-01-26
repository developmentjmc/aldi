<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP JMC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 30px;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 8px;
            margin: 20px 0;
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
            border: 2px dashed #2563eb;
        }
        .warning {
            color: #dc2626;
            font-size: 14px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kode OTP Verifikasi {{ env('APP_NAME') }}</h2>
        <p>Halo {{ $otp->name }},</p>
        <p>Berikut adalah kode OTP Anda:</p>
        
        <div class="otp-code">
            {{ $otp->code }}
        </div>
        
        <p>Kode ini akan kadaluarsa pada <strong>{{ date('d-m-Y H:i', strtotime($otp->expired_at)) }}</strong></p>
        
        <p class="warning">
            ⚠️ Jangan bagikan kode ini kepada siapa pun!
        </p>
    </div>
</body>
</html>
