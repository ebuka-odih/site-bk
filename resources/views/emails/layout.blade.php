<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') . ' Notification' }}</title>
    <style>
        :root {
            color-scheme: light;
        }

        body {
            margin: 0;
            background: #f1f5f9;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #0f172a;
            line-height: 1.6;
        }

        .email-container {
            width: 100%;
            padding: 32px 16px;
            box-sizing: border-box;
        }

        .email-card {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
        }

        .brand-bar {
            background: #0f172a;
            color: #ffffff;
            padding: 18px 36px;
            letter-spacing: 0.14em;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .email-content {
            padding: 36px;
        }

        .email-content h1 {
            margin-top: 0;
            font-size: 26px;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .email-content p {
            margin: 0 0 16px 0;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #1d4ed8;
            background: #dbeafe;
            border-radius: 9999px;
            padding: 6px 14px;
            margin-bottom: 24px;
        }

        .summary {
            background: #f8fafc;
            border-radius: 14px;
            padding: 20px 24px;
            margin: 28px 0;
            border: 1px solid #e2e8f0;
        }

        .summary dt {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .summary dd {
            margin: 0 0 16px 0;
            color: #475569;
        }

        .code-display {
            display: inline-block;
            font-weight: 700;
            font-size: 32px;
            letter-spacing: 0.28em;
            padding: 14px 18px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 14px;
            margin: 12px 0 24px;
        }

        .muted {
            color: #64748b;
        }

        .cta-button {
            display: inline-block;
            padding: 14px 28px;
            background: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 9999px;
            font-weight: 600;
            letter-spacing: 0.02em;
            margin: 8px 0 24px;
        }

        .cta-button:hover {
            background: #1d4ed8;
        }

        .footer {
            font-size: 12px;
            color: #64748b;
            margin-top: 36px;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
        }

        a {
            color: #2563eb;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .email-content {
                padding: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-card">
            <div class="brand-bar">{{ $brand ?? strtoupper(config('app.name')) }}</div>
            <div class="email-content">
                @hasSection('tag')
                    <div class="tag">@yield('tag')</div>
                @endif

                @hasSection('headline')
                    <h1>@yield('headline')</h1>
                @endif

                @hasSection('intro')
                    <p class="intro">@yield('intro')</p>
                @endif

                @yield('content')

                <div class="footer">
                    {{ $footer ?? 'This message was sent automatically by ' . config('app.name') . '.' }}<br>
                    Need help? Contact <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.
                </div>
            </div>
        </div>
    </div>
</body>
</html>

