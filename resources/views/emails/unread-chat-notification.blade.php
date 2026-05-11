<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تنبيه رسائل غير مقروءة</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .highlight {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 تنبيه رسائل جديدة</h1>
        </div>
        <div class="content">
            <p>مرحباً،</p>
            <p>لديك <span class="highlight">{{ $unreadCount }}</span> رسالة/رسائل غير مقروءة في الشات.</p>
            <p>يرجى مراجعة لوحة التحكم للرد على هذه الرسائل.</p>
            <a href="{{ url('/admin/chat') }}" class="btn">فتح الشات</a>
        </div>
        <div class="footer">
            <p>هذه رسالة آلية من نظام إدارة المحادثات</p>
            <p>تم الإرسال في: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
