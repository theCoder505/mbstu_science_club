<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message - MBSTUSC</title>
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
            background: linear-gradient(135deg, #fbbf24 0%, #14b8a6 100%);
            color: #ffffff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -40px -40px 30px -40px;
            text-align: center;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #14b8a6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
            font-size: 16px;
        }
        .message-box {
            background: #ffffff;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            min-height: 100px;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .timestamp {
            color: #999;
            font-size: 12px;
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Contact Message</h1>
        </div>

        <div class="info-box">
            <div class="info-label">From:</div>
            <div class="info-value">{{ $sender_email }}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Subject:</div>
            <div class="info-value">{{ $subject }}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Message:</div>
        </div>
        <div class="message-box">
            {!! nl2br(e($message_body)) !!}
        </div>

        <div class="timestamp">
            Received: {{ date('F j, Y, g:i a') }}
        </div>

        <div class="footer">
            <p>This message was sent via the MBSTUSC website contact form.</p>
            <p>© {{ date('Y') }} MBSTU Science Club. All rights reserved.</p>
        </div>
    </div>
</body>
</html>