<!DOCTYPE html>
<html>
<head>
    <title>{{ $status == 'revision' ? 'Application Revision Required' : 'Application Declined' }} - MBSTUSC</title>
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
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            {!! $status == 'revision' ? 'background-color: #fff3cd; border: 1px solid #ffc107;' : 'background-color: #f8d7da; border: 1px solid #f5c6cb;' !!}
        }
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 16px;
            {!! $status == 'revision' ? 'background-color: #ffc107; color: #856404;' : 'background-color: #dc3545; color: white;' !!}
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
        .message-box {
            background-color: {{ $status == 'revision' ? '#e7f3ff' : '#f8d7da' }};
            border-left: 4px solid {{ $status == 'revision' ? '#007bff' : '#dc3545' }};
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .action-box {
            background-color: #f8f9fa;
            border: 2px dashed #6c757d;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }
        .action-btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: {{ $status == 'revision' ? '#17a2b8' : '#dc3545' }};
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 15px;
        }
        .warning-note {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .note-box {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $status == 'revision' ? '📝 Revision Required' : '❌ Application Declined' }}</h1>
        <p>{{ $status == 'revision' ? 'Your certificate application requires some changes' : 'Your certificate application has been declined' }}</p>
        <div class="status-badge">
            {{ ucfirst($status) }}
        </div>
    </div>

    <div class="info-box">
        <h2>Application Details</h2>
        
        <div class="info-item">
            <span class="label">Applicant Name:</span>
            <span class="value">{{ $application->applicant_name }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Application ID:</span>
            <span class="value">#{{ $application->id }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Applied On:</span>
            <span class="value">{{ $application->created_at->format('F j, Y') }}</span>
        </div>
        
        <div class="info-item">
            <span class="label">Updated On:</span>
            <span class="value">{{ $application->updated_at->format('F j, Y, g:i a') }}</span>
        </div>
    </div>

    @if($status == 'revision')
    <div class="message-box">
        <h3>📌 Revision Required</h3>
        <p>Your certificate application has been marked for <strong>revision</strong>. Please review the following information and resubmit your application with the necessary corrections.</p>
        
        <div class="warning-note">
            ⚠️ Your application status has been reset to "pending". You need to resubmit your details.
        </div>
        
        <p><strong>Next Steps:</strong></p>
        <ol>
            <li>Review the feedback/notes provided below</li>
            <li>Make necessary corrections to your application</li>
            <li>Click the "Resubmit Application" button below</li>
            <li>Fill in the application form again with corrected information</li>
        </ol>
    </div>
    @else
    <div class="message-box">
        <h3>❌ Application Declined</h3>
        <p>We regret to inform you that your certificate application has been <strong>declined</strong>.</p>
        
        <div class="warning-note">
            ⚠️ Your application has been declined. You need to resubmit a new application with proper information.
        </div>
        
        <p><strong>Important:</strong> Since your application has been declined, you cannot simply edit the existing application. You need to submit a completely new application.</p>
        
        <p><strong>Action Required:</strong></p>
        <ol>
            <li>Carefully review the reason for decline provided below</li>
            <li>Address all issues mentioned</li>
            <li>Click the "Submit New Application" button below</li>
            <li>Fill out a completely new application form</li>
            <li>Ensure all information is accurate and complete</li>
        </ol>
    </div>
    @endif

    @if($note)
    <div class="note-box">
        <h4>📋 Admin Notes/Feedback:</h4>
        <p>{{ $note }}</p>
    </div>
    @endif

    <div class="action-box">
        <h3>{{ $status == 'revision' ? 'Resubmit Your Application' : 'Submit New Application' }}</h3>
        <p>{{ $status == 'revision' ? 'Click the button below to resubmit your application with corrections:' : 'Click the button below to submit a new application:' }}</p>
        
        <a href="{{ url('/apply') }}" class="action-btn">
            {{ $status == 'revision' ? 'Resubmit Application' : 'Submit New Application' }}
        </a>
        
        <p style="margin-top: 10px; font-size: 14px; color: #666;">
            {{ $status == 'revision' ? 'You can also track your application status here:' : 'You can track your new application status here:' }}
            <br>
            <a href="{{ url('/track') }}">{{ url('/track') }}</a>
        </p>
    </div>

    @if($status == 'declined')
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h4>💡 Tips for Resubmission:</h4>
        <ul>
            <li>Double-check all personal information</li>
            <li>Ensure dates and designations are accurate</li>
            <li>Provide complete and detailed information in all fields</li>
            <li>Review the admin feedback carefully</li>
            <li>Contact support if you have questions</li>
        </ul>
    </div>
    @endif

    <div class="footer">
        <p>This is an automated notification from MBSTUSC Certificate System.</p>
        <p>If you believe this is an error, please contact the administration.</p>
        <p>Application ID: {{ $application->id }} | Status: {{ ucfirst($status) }}</p>
        <p>© {{ date('Y') }} MBSTUSC. All rights reserved.</p>
    </div>
</body>
</html>