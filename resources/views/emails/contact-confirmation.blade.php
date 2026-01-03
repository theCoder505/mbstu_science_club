<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Received - MBSTUSC</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
        }
        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #ffffff;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        .content {
            margin: 30px 0;
        }
        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .social-links {
            text-align: center;
            margin: 30px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #14b8a6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="checkmark">✓</div>
            <h1>Thank You for Contacting Us!</h1>
            <p class="subtitle">We've received your message</p>
        </div>

        <div class="content">
            <p>Hello,</p>
            <p>
                Thank you for reaching out to the MBSTU Science Club. We have successfully received 
                your message regarding "<strong>{{ $subject }}</strong>".
            </p>

            <div class="info-box">
                <strong>📬 What happens next?</strong><br>
                Our team will review your message and get back to you as soon as possible. 
                We typically respond within 24-48 hours during business days.
            </div>

            <p>
                In the meantime, feel free to explore our website and learn more about our activities, 
                events, and how you can get involved with the MBSTU Science Club.
            </p>
        </div>

        <div class="social-links">
            <p><strong>Stay Connected:</strong></p>
            <a href="https://www.facebook.com/share/1Chf17ewNv/?mibextid=wwXIfr" target="_blank">Facebook</a> | 
            <a href="https://mbstusc.edu.bd">Website</a>
        </div>

        <div class="footer">
            <p><strong>MBSTU Science Club</strong></p>
            <p>Mawlana Bhashani Science and Technology University</p>
            <p>Santosh, Tangail, Bangladesh</p>
            <p style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} MBSTU Science Club. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>