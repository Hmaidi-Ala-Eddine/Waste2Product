<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $ecoIdea->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }

        .certificate-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .certificate {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            padding: 60px 50px;
            border: 15px double #10b981;
            position: relative;
            overflow: hidden;
        }

        .decorative-circle-1 {
            position: absolute;
            top: -80px;
            right: -80px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .decorative-circle-2 {
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .award-icon {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 20px 35px;
            border-radius: 50px;
            margin-bottom: 25px;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        }

        .award-icon i {
            font-size: 50px;
            color: white;
        }

        h1 {
            font-size: 42px;
            font-weight: 900;
            color: #1f2937;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 18px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .presented-to {
            font-size: 15px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
        }

        .recipient-name {
            font-size: 38px;
            font-weight: 900;
            color: #10b981;
            margin: 15px 0;
            font-family: 'Georgia', serif;
        }

        .completion-text {
            font-size: 16px;
            color: #6b7280;
            margin-top: 15px;
            margin-bottom: 35px;
        }

        .project-details {
            background: rgba(16, 185, 129, 0.05);
            border-radius: 18px;
            padding: 30px;
            margin: 35px auto;
            border-left: 5px solid #10b981;
            max-width: 750px;
        }

        .project-title {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            text-align: left;
            margin-top: 25px;
        }

        .detail-item {
            padding: 15px;
            background: white;
            border-radius: 10px;
        }

        .detail-label {
            font-size: 11px;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 700;
        }

        .achievement-message {
            font-size: 17px;
            line-height: 1.9;
            color: #4b5563;
            max-width: 700px;
            margin: 35px auto;
        }

        .achievement-message strong {
            color: #1f2937;
        }

        .ngo-donation {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 18px;
            padding: 25px;
            margin: 35px auto;
            border-left: 5px solid #f59e0b;
            max-width: 700px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .ngo-icon {
            font-size: 40px;
            color: #f59e0b;
            flex-shrink: 0;
        }

        .ngo-text h4 {
            font-size: 18px;
            font-weight: 800;
            color: #92400e;
            margin-bottom: 8px;
        }

        .ngo-text p {
            font-size: 14px;
            color: #78350f;
            line-height: 1.6;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            margin-top: 45px;
        }

        .verified-badge i {
            font-size: 24px;
        }

        .verified-badge span {
            font-weight: 800;
            font-size: 16px;
            letter-spacing: 1.5px;
        }

        .footer {
            margin-top: 55px;
            padding-top: 35px;
            border-top: 3px solid #e5e7eb;
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            gap: 25px;
        }

        .footer-item {
            text-align: center;
        }

        .signature-line {
            width: 180px;
            height: 3px;
            background: linear-gradient(to right, transparent, #10b981, transparent);
            margin: 0 auto 12px;
        }

        .footer-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .footer-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 800;
        }

        .leaf-icon {
            font-size: 50px;
            color: #10b981;
            opacity: 0.2;
        }

        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            border: none;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-button {
                display: none;
            }

            .certificate-container {
                box-shadow: none;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .certificate {
                padding: 40px 25px;
            }

            h1 {
                font-size: 32px;
            }

            .recipient-name {
                font-size: 30px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate">
            <div class="decorative-circle-1"></div>
            <div class="decorative-circle-2"></div>
            
            <div class="content">
                <!-- Header -->
                <div class="award-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h1>CERTIFICATE OF ACHIEVEMENT</h1>
                <p class="subtitle">Environmental Impact & Sustainability</p>

                <!-- Recipient -->
                <p class="presented-to">This certifies that</p>
                <h2 class="recipient-name">{{ $user->name }}</h2>
                <p class="completion-text">has successfully completed the eco-innovation project</p>

                <!-- Project Details -->
                <div class="project-details">
                    <h3 class="project-title">{{ $ecoIdea->title }}</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Waste Type</div>
                            <div class="detail-value">{{ ucfirst($ecoIdea->waste_type) }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Difficulty Level</div>
                            <div class="detail-value">{{ ucfirst($ecoIdea->difficulty_level) }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Team Size</div>
                            <div class="detail-value">{{ $ecoIdea->team()->count() + 1 }} Members</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Completion Date</div>
                            <div class="detail-value">{{ $ecoIdea->updated_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Achievement Message -->
                <p class="achievement-message">
                    <strong>Congratulations!</strong> You have demonstrated exceptional commitment to environmental sustainability by successfully completing this eco-innovation project. Your contribution in transforming waste materials into valuable resources exemplifies the spirit of circular economy and environmental stewardship.
                </p>

                @if($isVerified)
                <!-- NGO Donation Message -->
                <div class="ngo-donation">
                    <i class="fas fa-hands-helping ngo-icon"></i>
                    <div class="ngo-text">
                        <h4>Project Donated to NGO</h4>
                        <p>This verified project has been donated to environmental organizations for continued impact and community benefit.</p>
                    </div>
                </div>
                @endif

                @if($isVerified)
                <!-- Verification Badge -->
                <div class="verified-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>VERIFIED PROJECT</span>
                </div>
                @endif

                <!-- Footer -->
                <div class="footer">
                    <div class="footer-item">
                        <div class="signature-line"></div>
                        <div class="footer-label">Project Creator</div>
                        <div class="footer-value">{{ $ecoIdea->creator->name }}</div>
                    </div>
                    <div class="footer-item">
                        <i class="fas fa-leaf leaf-icon"></i>
                    </div>
                    <div class="footer-item">
                        <div class="signature-line"></div>
                        <div class="footer-label">Waste2Product Platform</div>
                        <div class="footer-value">{{ now()->format('Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i>
        Print / Save as PDF
    </button>
</body>
</html>
