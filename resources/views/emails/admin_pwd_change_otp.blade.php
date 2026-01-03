<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change Verification</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
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
            color: #f5576c;
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
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #0c5460;
        }
        .security-tips {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .security-tips h3 {
            margin: 0 0 10px 0;
            font-size: 15px;
            color: #155724;
        }
        .security-tips ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .security-tips li {
            font-size: 13px;
            color: #155724;
            margin: 5px 0;
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
            color: #f5576c;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🔑</div>
            <h1>Password Change Verification</h1>
            <p>MBSTU Science Club</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $user->name }},
            </div>
            
            <div class="message">
                We received a request to change the password for your MBSTU Science Club account. 
                To ensure the security of your account, please verify this change using the One-Time Password (OTP) below.
            </div>
            
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-validity">⏱️ Valid for 10 minutes</div>
            </div>
            
            <div class="info-box">
                <p>
                    <strong>ℹ️ Instructions:</strong> Enter this code in the verification dialog to confirm your password change. 
                    Keep this code confidential and do not share it with anyone, including MBSTU Science Club staff.
                </p>
            </div>

            <div class="security-tips">
                <h3>🛡️ Password Security Tips:</h3>
                <ul>
                    <li>Use a strong, unique password with at least 8 characters</li>
                    <li>Include a mix of uppercase, lowercase, numbers, and symbols</li>
                    <li>Avoid using personal information or common words</li>
                    <li>Never reuse passwords across different accounts</li>
                    <li>Consider using a password manager for added security</li>
                </ul>
            </div>

            <div class="security-note">
                <p>
                    <strong>⚠️ Security Alert:</strong> If you did not request this password change, 
                    your account may be compromised. Please take immediate action:
                </p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li style="margin: 5px 0;">Ignore this email and do not enter the OTP</li>
                    <li style="margin: 5px 0;">Change your password immediately through the official website</li>
                    <li style="margin: 5px 0;">Contact our support team for assistance</li>
                    <li style="margin: 5px 0;">Review your account activity for any suspicious actions</li>
                </ul>
            </div>
            
            <div class="message">
                Your account security is our top priority. If you have any questions or concerns, 
                please contact our support team immediately at <strong>support@mbstusc.ac.bd</strong>
            </div>
        </div>
        
        <div class="footer">
            <p class="brand">MBSTU Science Club</p>
            <p>Mawlana Bhashani Science and Technology University</p>
            <p>&copy; {{ date('Y') }} MBSTU Science Club. All rights reserved.</p>
            <p style="margin-top: 15px; font-size: 12px;">
                This is an automated security message. Please do not reply to this email.
            </p>
            <p style="font-size: 12px; margin-top: 10px;">
                📧 For support, email us at support@mbstusc.ac.bd
            </p>
        </div>
    </div>
</body>
</html>