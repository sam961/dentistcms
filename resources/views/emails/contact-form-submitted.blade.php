<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .info-row {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .value {
            font-size: 16px;
            color: #111827;
        }
        .message-box {
            background-color: #f9fafb;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 4px;
            margin-top: 5px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .badge {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📬 New Contact Form Submission</h1>
        </div>

        <div class="content">
            <div style="text-align: center; margin-bottom: 30px;">
                <span class="badge">NEW MESSAGE</span>
            </div>

            <div class="info-row">
                <div class="label">From</div>
                <div class="value">{{ $contactRequest->name }}</div>
            </div>

            <div class="info-row">
                <div class="label">Email Address</div>
                <div class="value">
                    <a href="mailto:{{ $contactRequest->email }}" style="color: #667eea; text-decoration: none;">
                        {{ $contactRequest->email }}
                    </a>
                </div>
            </div>

            @if($contactRequest->phone)
            <div class="info-row">
                <div class="label">Phone Number</div>
                <div class="value">{{ $contactRequest->phone }}</div>
            </div>
            @endif

            <div class="info-row">
                <div class="label">Subject</div>
                <div class="value"><strong>{{ $contactRequest->subject }}</strong></div>
            </div>

            <div class="info-row">
                <div class="label">Message</div>
                <div class="message-box">
                    {{ $contactRequest->message }}
                </div>
            </div>

            <div class="info-row">
                <div class="label">Submitted On</div>
                <div class="value">{{ $contactRequest->created_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0;">This email was sent from the Dental Hub contact form.</p>
            <p style="margin: 10px 0 0 0;">You can reply directly to this email to respond to {{ $contactRequest->name }}.</p>
        </div>
    </div>
</body>
</html>
