<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Certificate</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            position: relative;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .certificate-border {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 3px solid #2c8aa6;
            background: linear-gradient(135deg, #f8fdff 0%, #ffffff 100%);
        }

        .corner-decoration {
            position: absolute;
            width: 25px;
            height: 25px;
            border: 3px solid #2c8aa6;
        }

        .corner-decoration::before,
        .corner-decoration::after {
            content: '';
            position: absolute;
            background: #2c8aa6;
        }

        .corner-decoration.top-left {
            top: -3px;
            left: -3px;
            border-right: none;
            border-bottom: none;
        }

        .corner-decoration.top-left::before {
            top: 8px;
            left: 8px;
            width: 14px;
            height: 3px;
        }

        .corner-decoration.top-left::after {
            top: 8px;
            left: 8px;
            width: 3px;
            height: 14px;
        }

        .corner-decoration.top-right {
            top: -3px;
            right: -3px;
            border-left: none;
            border-bottom: none;
        }

        .corner-decoration.top-right::before {
            top: 8px;
            right: 8px;
            width: 14px;
            height: 3px;
        }

        .corner-decoration.top-right::after {
            top: 8px;
            right: 8px;
            width: 3px;
            height: 14px;
        }

        .corner-decoration.bottom-left {
            bottom: -3px;
            left: -3px;
            border-right: none;
            border-top: none;
        }

        .corner-decoration.bottom-left::before {
            bottom: 8px;
            left: 8px;
            width: 14px;
            height: 3px;
        }

        .corner-decoration.bottom-left::after {
            bottom: 8px;
            left: 8px;
            width: 3px;
            height: 14px;
        }

        .corner-decoration.bottom-right {
            bottom: -3px;
            right: -3px;
            border-left: none;
            border-top: none;
        }

        .corner-decoration.bottom-right::before {
            bottom: 8px;
            right: 8px;
            width: 14px;
            height: 3px;
        }

        .corner-decoration.bottom-right::after {
            bottom: 8px;
            right: 8px;
            width: 3px;
            height: 14px;
        }

        .certificate-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 20px;
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo-text {
            color: #2c8aa6;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .logo-subtitle {
            color: #5fb3d1;
            font-size: 24px;
            font-weight: 700;
            position: relative;
        }

        .logo-wave {
            position: absolute;
            left: -30px;
            top: 50%;
            transform: translateY(-50%);
            width: 25px;
            height: 15px;
            background: linear-gradient(45deg, #5fb3d1, #7bc142);
            border-radius: 50px;
        }

        .logo-wave::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 2px;
            width: 20px;
            height: 11px;
            background: linear-gradient(45deg, #7bc142, #5fb3d1);
            border-radius: 50px;
        }

        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            color: #2c5f7a;
            font-weight: 400;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .certificate-subtitle {
            font-size: 18px;
            color: #666;
            font-weight: 300;
            margin-bottom: 40px;
        }

        .awarded-to {
            color: #5fb3d1;
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 20px;
        }

        .recipient-name {
            font-size: 36px;
            color: #2c5f7a;
            font-weight: 600;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c8aa6;
            display: inline-block;
            min-width: 300px;
        }

        .certificate-text {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 50px;
        }

        .date-section, .signature-section {
            text-align: center;
            flex: 1;
        }

        .date-section {
            text-align: left;
        }

        .signature-section {
            text-align: right;
        }

        .date-label, .signature-label {
            color: #5fb3d1;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .date-value, .signature-value {
            color: #2c5f7a;
            font-size: 14px;
            font-weight: 400;
        }

        .seal {
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, #ffd700 0%, #ffed4a  50%, #f39c12 100%);
            border-radius: 50%;
            position: relative;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }

        .seal::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border: 2px dashed #e67e22;
            border-radius: 50%;
        }

        .seal::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30px;
            height: 30px;
            background: #d35400;
            border-radius: 50%;
        }

        .decorative-line {
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #5fb3d1, transparent);
            margin: 20px auto;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .certificate-container {
                box-shadow: none;
                max-width: none;
                margin: 0;
            }
        }

        @media (max-width: 768px) {
            .certificate-container {
                padding: 20px;
            }

            .certificate-title {
                font-size: 32px;
                letter-spacing: 2px;
            }

            .recipient-name {
                font-size: 28px;
                min-width: 250px;
            }

            .certificate-footer {
                flex-direction: column;
                gap: 30px;
            }

            .date-section, .signature-section {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="corner-decoration top-left"></div>
            <div class="corner-decoration top-right"></div>
            <div class="corner-decoration bottom-left"></div>
            <div class="corner-decoration bottom-right"></div>
        </div>

        <div class="certificate-content">
            <!-- Logo Section -->
            <div class="logo">
                <div class="logo-text">nova scotia</div>
                <div class="logo-subtitle">
                    <div class="logo-wave"></div>
                    health
                </div>
            </div>

            <!-- Title -->
            <h1 class="certificate-title">VOLUNTEER CERTIFICATE</h1>
            <div class="certificate-subtitle">of Appreciation</div>

            <div class="decorative-line"></div>

            <!-- Awarded To -->
            <div class="awarded-to">Awarded to:</div>

            <!-- Recipient Name -->
            <div class="recipient-name">
                {{ $recipientName ?? '________________________' }}
            </div>

            <!-- Certificate Text -->
            <div class="certificate-text">
                for {{ $yearsOfService ?? '___' }} years of dedicated volunteer service to the patients and families in the
                Hospice Palliative Care Service in Cape Breton. We truly value your time,
                compassion and unwavering commitment.
            </div>

            <!-- Seal -->
            <div class="seal"></div>

            <!-- Footer -->
            <div class="certificate-footer">
                <div class="date-section">
                    <div class="date-label">DATE</div>
                    <div class="date-value">{{ $date ?? '________________' }}</div>
                </div>

                <div class="signature-section">
                    <div class="signature-label">Palliative Care Volunteer Program Lead</div>
                    <div class="signature-value">{{ $signatureName ?? '________________' }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
