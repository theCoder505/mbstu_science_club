<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Approval Request</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header with Club Logo -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%); padding: 40px 30px; text-align: center;">
                            <img src="{{ url('/assets/images/club_logo.png') }}" alt="MBSTU Science Club" style="max-width: 100px; height: auto; margin-bottom: 20px;">
                            <h1 style="color: #fbbf24; margin: 0; font-size: 28px; font-weight: bold;">MBSTU Science Club</h1>
                            <p style="color: #ffffff; margin: 10px 0 0 0; font-size: 14px;">Mawlana Bhashani Science and Technology University</p>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #0f766e; margin: 0 0 20px 0; font-size: 24px;">Certificate Approval Request</h2>
                            
                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                                Dear {{ $club_role ?? 'Sir/Madam' }},
                            </p>
                            
                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                                A new certificate has been prepared and is awaiting your approval. Your review and authorization are required to proceed with the certificate issuance.
                            </p>

                            <div style="background-color: #f1f5f9; border-left: 4px solid #fbbf24; padding: 20px; margin: 25px 0; border-radius: 4px;">
                                <p style="color: #334155; font-size: 15px; margin: 0 0 10px 0; font-weight: bold;">Application Details:</p>
                                <p style="color: #475569; font-size: 14px; margin: 0;">
                                    <strong>Application ID:</strong> #{{ $application_id }}
                                </p>
                            </div>

                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 20px 0;">
                                Please click the button below to review and approve the certificate:
                            </p>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/advisor/dashboard?certificate=' . $application_id) }}" style="display: inline-block; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #1e293b; text-decoration: none; padding: 16px 40px; border-radius: 6px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(251, 191, 36, 0.3);">
                                            Review & Approve Certificate
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                                If you have any questions or concerns, please contact the administration team.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #0f766e; padding: 30px; text-align: center;">
                            <p style="color: #fbbf24; margin: 0 0 10px 0; font-size: 16px; font-weight: bold;">MBSTU Science Club</p>
                            <p style="color: #cbd5e1; margin: 0; font-size: 13px; line-height: 1.5;">
                                Mawlana Bhashani Science and Technology University<br>
                                Santosh, Tangail-1902, Bangladesh
                            </p>
                            <div style="margin-top: 20px;">
                                <p style="color: #94a3b8; margin: 0; font-size: 12px;">
                                    © {{ date('Y') }} MBSTU Science Club. All rights reserved.
                                </p>
                            </div>
                        </td>
                    </tr>

                </table>

                <!-- Alternative Link -->
                <table width="600" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                    <tr>
                        <td style="text-align: center; padding: 0 30px;">
                            <p style="color: #64748b; font-size: 12px; line-height: 1.5; margin: 0;">
                                If the button doesn't work, copy and paste this link into your browser:<br>
                                <a href="{{ url('/advisor/dashboard?certificate=' . $application_id) }}" style="color: #0f766e; word-break: break-all;">
                                    {{ url('/advisor/dashboard?certificate=' . $application_id) }}
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>