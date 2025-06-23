<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Family Chores App' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .header img {
            height: 50px;
        }
        .content {
            padding: 20px 0;
        }
        .content h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-top: 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            background-color: #3490dc;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #95a5a6;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ config('app.url') }}">
                <img src="{{ asset('logo.png') }}" alt="ChoreBusters Logo">
            </a>
            <h1>ChoreBusters</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            © {{ date('Y') }} ChoreBusters. All rights reserved.
        </div>
    </div>
</body>
</html>