{{-- resources/views/auth/login.blade.php --}}
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ $notification['title'] ?? 'Уведомление' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: system-ui, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .notification {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            max-width: 400px;
            width: 100%;
            border-left: 6px solid;
        }

        .notification.success {
            border-color: #10b981;
        }

        .notification.info {
            border-color: #3b82f6;
        }

        .notification h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.25rem;
        }

        .notification p {
            margin: 0;
            color: #4b5563;
        }
    </style>
</head>
<body>
@if($notification)
    <div class="notification {{ $notification['type'] }}">
        <h2>{{ $notification['title'] }}</h2>
        <p>{{ $notification['body'] }}</p>
    </div>
@else
    <div class="notification info">
        <h2>No notification</h2>
    </div>
@endif
</body>
</html>
