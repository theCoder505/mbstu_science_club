<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - MBSTUSC</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 40px;
            text-align: center;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
        }
        h1 {
            color: #ffffff;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #f0f0f0;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .otp-box {
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 8px;
            margin: 20px 0;
        }
        .otp-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .footer {
            color: #f0f0f0;
            font-size: 14px;
            margin-top: 30px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
            text-align: left;
            color: #856404;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 MBSTU Science Club</h1>
        <p class="subtitle">Contact Form Verification</p>
        
        <div class="otp-box">
            <p class="otp-label">Your OTP Code is:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p style="color: #666; font-size: 14px; margin-top: 20px;">
                This code will expire in <strong>10 minutes</strong>
            </p>
        </div>

        <div class="warning">
            <strong>⚠️ Security Notice:</strong><br>
            Never share this OTP with anyone. MBSTUSC staff will never ask for your OTP.
        </div>

        <div class="footer">
            <p>If you didn't request this code, please ignore this email.</p>
            <p style="margin-top: 20px;">
                © {{ date('Y') }} MBSTU Science Club. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>