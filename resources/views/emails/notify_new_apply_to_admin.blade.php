<!DOCTYPE html>
<html>
<head>
    <title>{{ $isUpdate ? 'Application Updated' : 'New Application' }} - MBSTUSC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-box {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #495057;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #212529;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            background-color: #ffc107;
            color: #212529;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .action-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .changes-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .changes-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $isUpdate ? '📝 Application Updated' : '🎯 New Application Received' }}</h1>
        <p>{{ $isUpdate ? 'An applicant has updated their certificate application' : 'A new certificate application has been submitted' }}</p>
    </div>

    <div class="info-box">
        <h2>Application Details</h2>
        
        <div class="info-item">
            <span class="label">Application Type:</span>
            <span class="value">
                @if($isUpdate)
                    <strong>Updated Application</strong>
                @else
                    <strong>New Application</strong>
                @endif
            </span>
        </div>
        
        <div class="info-item">
            <span class="label">Applicant Name:</span>
            <span class="value">{{ $application->applicant_name }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Email:</span>
            <span class="value">{{ $application->email }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Designation:</span>
            <span class="value">{{ $application->designation }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Member Since:</span>
            <span class="value">{{ $application->member_since }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Member Till:</span>
            <span class="value">{{ $application->member_till }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Impact/Contribution:</span>
            <span class="value">{{ $application->impact ?? 'Not specified' }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Status:</span>
            <span class="status">{{ ucfirst($application->certificate_status) }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Applied/Updated At:</span>
            <span class="value">{{ $application->updated_at->format('F j, Y, g:i a') }}</span>
        </div>
        
        @if($isUpdate)
        <div class="info-item">
            <span class="label">Previously Applied:</span>
            <span class="value">{{ $existingData->created_at->format('F j, Y') }}</span>
        </div>
        
        <!-- Show changes if needed -->
        @if($existingData)
        <div class="changes-box">
            <div class="changes-title">Application Updated</div>
            <p>This applicant has previously submitted an application on {{ $existingData->created_at->format('F j, Y') }}.</p>
            <p>The application has been refreshed and status reset to "pending".</p>
        </div>
        @endif
        @endif
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/applications') }}" class="action-btn">View Application in Admin Panel</a>
    </div>

    <div class="footer">
        <p>This is an automated notification from MBSTUSC Certificate System.</p>
        <p>Application ID: {{ $application->id }}</p>
        <p>© {{ date('Y') }} MBSTUSC. All rights reserved.</p>
    </div>
</body>
</html>