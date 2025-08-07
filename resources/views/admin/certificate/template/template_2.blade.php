<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion</title>
    <style>


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .certificate-container {
            background: white;
            box-shadow: 0 10px 30px rgba(14, 138, 64, 0.1);
            border-radius: 10px;
            overflow: hidden;
            max-width: 1024px;
            width: 100%;
        }

        .certificate {
            border: 20px solid #065F46;
            padding: 40px;
            min-height: 600px;
            position: relative;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .certificate:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="%23E3F2FD" stroke-width="2" opacity="0.3"/><text x="50" y="55" text-anchor="middle" font-size="12" fill="%23E3F2FD" opacity="0.5">CERTIFICATE</text></svg>'); */
            background-size: 200px 200px;
            background-repeat: repeat;
            background-position: center;
            z-index: 0;
            opacity: 0.05;
        }

        .certificate-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .certificate-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }

        .certificate-header .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #065F46;
            background: #f8f9fa;
            padding: 10px;
        }

        .certificate-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .certificate-title strong {
            font-size: 16px;
            color: #065F46;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 10px;
        }

        .certificate-body {
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px 0;
        }

        h1 {
            font-weight: 300;
            font-size: 48px;
            color: #065F46;
            margin-bottom: 30px;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(10, 138, 63, 0.1);
        }

        .student-name {
            font-size: 32px;
            font-weight: 600;
            color: #059669;
            margin: 25px 0;
            border-bottom: 3px solid #065F46;
            padding-bottom: 10px;
            display: inline-block;
            min-width: 400px;
        }

        .main-content {
            max-width: 750px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .about-certificate {
            margin: 25px auto;
            font-size: 18px;
            line-height: 1.6;
            color: #000306;
        }

        .topic-title {
            font-size: 16px;
            font-weight: 600;
            color: #065F46;
            margin: 25px 0 15px 0;
        }

        .topic-description {
            font-size: 14px;
            color: #636b6c;
            line-height: 1.8;
            text-align: justify;
            margin: 0 auto;
            max-width: 600px;
            padding: 15px;
            background: #e1f9df;
            border-left: 4px solid #065F46;
            border-radius: 4px;
        }

        .certificate-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #E9ECEF;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-section {
            flex: 1;
        }

        .accreditation-section {
            flex: 1;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .signature-line {
            border-bottom: 2px solid #065F46;
            width: 200px;
            margin: 20px 0 10px 0;
            height: 40px;
        }

        .signature-label {
            font-size: 14px;
            color: #6C757D;
            font-weight: 500;
        }

        .accreditation-item {
            font-size: 12px;
            color: #0f5333;
        }

        .accreditation-item strong {
            display: block;
            margin-bottom: 5px;
            color: #065F46;
            font-weight: 600;
        }

        .certificate-date {
            position: absolute;
            bottom: 15px;
            right: 20px;
            font-size: 12px;
            color: #6C757D;
            font-style: italic;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .certificate {
                padding: 20px;
                border-width: 10px;
            }

            h1 {
                font-size: 36px;
            }

            .student-name {
                font-size: 24px;
                min-width: 300px;
            }

            .certificate-footer {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate">
            <div class="certificate-content">
                <div class="certificate-header">
                    <img src="{{ 'assets/LogoSobatBumi.png' }}" class="logo" alt="SobatBumi">
                </div>

                {{-- <div class="certificate-title">
                    <strong>RENR NCLEX AND CONTINUING EDUCATION (CME) Review Masters</strong>
                </div> --}}

                <div class="certificate-body">
                    <h1>Sertifikat Partisipasi</h1>

                    <p style="font-size: 18px; color: #02120a; margin-bottom: 10px;">Sertifikat ini diberikan kepada </p>

                    <div class="student-name">{{ $volunteerName ?? 'Matthew Taylor' }}</div>

                    <div class="main-content">
                        <div class="about-certificate">
                            <p>Telah menyelesaikan kegiatan volunteer KawanBumi</p>
                            <p><strong>{{ $missionTitle ?? 'Misi' }}</strong></p>
                            <p>Yang Diselesaikan pada <strong>{{ $certificateDate ?? '[Date of Completion]' }}</strong></p>
                        </div>

                        {{-- <p class="topic-title">
                            This program consists of {{ $total_hours ?? '[hours]' }} continuing education hours and includes the following topics:
                        </p> --}}

                        {{-- <div class="topic-description">
                            {{ $course_description ?? 'Contract administrator - Types of claim - Claim Strategy - Delay analysis - The preliminaries to a claim - The essential elements to a successful claim - Responses - Claim preparation and presentation' }}
                        </div> --}}
                    </div>
                </div>

                <div class="certificate-footer">
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <p class="signature-label">Tim KawanBumi</p>
                    </div>


                </div>

                <div class="certificate-date">
                    Certificate No: {{ $certificateCode ?? 'CERT-2024-001' }} | Issue Date: {{ $certificateDate ?? date('F d, Y') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
