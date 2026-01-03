<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Change Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .message {
            font-size: 15px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .otp-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .otp-validity {
            font-size: 13px;
            color: #888;
            margin-top: 10px;
        }
        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #856404;
        }
        .new-email {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .new-email p {
            margin: 5px 0;
            font-size: 14px;
            color: #0c5460;
        }
        .new-email strong {
            color: #004085;
        }
        .security-note {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .security-note p {
            margin: 0;
            font-size: 14px;
            color: #721c24;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 13px;
            color: #6c757d;
        }
        .footer .brand {
            font-weight: 600;
            color: #667eea;
            font-size: 16px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Email Change Verification</h1>
            <p>MBSTU Science Club</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $user->name }},
            </div>
            
            <div class="message">
                We received a request to change the email address associated with your MBSTU Science Club account. 
                To complete this change, please use the One-Time Password (OTP) below.
            </div>

            <div class="new-email">
                <p><strong>New Email Address:</strong></p>
                <p>{{ $newEmail }}</p>
            </div>
            
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-validity">⏱️ Valid for 10 minutes</div>
            </div>
            
            <div class="info-box">
                <p>
                    <strong>⚠️ Important:</strong> Enter this code in the verification dialog to confirm your email change. 
                    Do not share this code with anyone.
                </p>
            </div>

            <div class="security-note">
                <p>
                    <strong>🛡️ Security Notice:</strong> If you did not request this email change, 
                    please ignore this email and ensure your account is secure. Consider changing your password immediately.
                </p>
            </div>
            
            <div class="message">
                If you have any questions or concerns, please contact our support team immediately.
            </div>
        </div>
        
        <div class="footer">
            <p class="brand">MBSTU Science Club</p>
            <p>Mawlana Bhashani Science and Technology University</p>
            <p>&copy; {{ date('Y') }} MBSTU Science Club. All rights reserved.</p>
            <p style="margin-top: 15px; font-size: 12px;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>