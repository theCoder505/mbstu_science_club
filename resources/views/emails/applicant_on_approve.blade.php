<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Approved</title>
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

                    <!-- Congratulations Banner -->
                    <tr>
                        <td style="background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%); padding: 25px 30px; text-align: center;">
                            <h2 style="color: #1e293b; margin: 0; font-size: 26px; font-weight: bold;">🎉 Congratulations! 🎉</h2>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h3 style="color: #0f766e; margin: 0 0 20px 0; font-size: 22px;">Your Certificate Has Been Approved!</h3>
                            
                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                                Dear {{ $applicant_name ?? 'Applicant' }},
                            </p>
                            
                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                                We are pleased to inform you that your certificate has been reviewed and approved by our authorized personnel. Your certificate is now ready for download!
                            </p>

                            <div style="background-color: #ecfdf5; border-left: 4px solid #10b981; padding: 20px; margin: 25px 0; border-radius: 4px;">
                                <p style="color: #065f46; font-size: 15px; margin: 0 0 10px 0; font-weight: bold;">✓ Certificate Status: Approved</p>
                                <p style="color: #059669; font-size: 14px; margin: 0;">
                                    Your certificate has been digitally signed and is now official.
                                </p>
                            </div>

                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 20px 0;">
                                Click the button below to download your certificate:
                            </p>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $certificate_file }}" download style="display: inline-block; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #1e293b; text-decoration: none; padding: 16px 40px; border-radius: 6px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(251, 191, 36, 0.3);">
                                            📥 Download Certificate
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Important Notice -->
                            <div style="background-color: #fef3c7; border: 1px solid #fbbf24; padding: 20px; margin: 30px 0; border-radius: 6px;">
                                <p style="color: #78350f; font-size: 14px; margin: 0 0 10px 0; font-weight: bold;">📌 Important Notes:</p>
                                <ul style="color: #92400e; font-size: 14px; margin: 0; padding-left: 20px; line-height: 1.6;">
                                    <li style="margin-bottom: 8px;">Please save your certificate in a secure location</li>
                                    <li style="margin-bottom: 8px;">The certificate is in digital format and can be printed if needed</li>
                                    <li style="margin-bottom: 0;">For verification queries, please keep your application ID handy</li>
                                </ul>
                            </div>

                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 20px 0 0 0;">
                                Thank you for your participation and contribution to MBSTU Science Club. We wish you all the best in your future endeavors!
                            </p>

                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                                If you encounter any issues downloading your certificate or have any questions, please don't hesitate to contact us.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #0f766e; padding: 30px; text-align: center;">
                            <p style="color: #fbbf24; margin: 0 0 10px 0; font-size: 16px; font-weight: bold;">MBSTU Science Club</p>
                            <p style="color: #cbd5e1; margin: 0 0 15px 0; font-size: 13px; line-height: 1.5;">
                                Mawlana Bhashani Science and Technology University<br>
                                Santosh, Tangail-1902, Bangladesh
                            </p>
                            <p style="color: #cbd5e1; margin: 0 0 15px 0; font-size: 13px;">
                                Email: info@mbstusc.org | Website: www.mbstusc.org
                            </p>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(203, 213, 225, 0.2);">
                                <p style="color: #94a3b8; margin: 0; font-size: 12px;">
                                    © {{ date('Y') }} MBSTU Science Club. All rights reserved.
                                </p>
                            </div>
                        </td>
                    </tr>

                </table>

                <!-- Alternative Download Link -->
                <table width="600" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                    <tr>
                        <td style="text-align: center; padding: 0 30px;">
                            <p style="color: #64748b; font-size: 12px; line-height: 1.5; margin: 0;">
                                If the download button doesn't work, copy and paste this link into your browser:<br>
                                <a href="{{ $certificate_file }}" style="color: #0f766e; word-break: break-all;">
                                    {{ $certificate_file }}
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