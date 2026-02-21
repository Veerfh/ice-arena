<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата успешна - Ледовая Арена</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="payment-success">
        <div class="success-card">
            <h1>Оплата прошла успешно!</h1>
            <p>Спасибо за покупку. Ждем вас на нашем катке! При входе назовите фамилию, на которую оформили билет или бронь.</p>
            <a href="{{ route('home') }}" class="btn-return">Вернуться на главную</a>
        </div>
    </div>
</body>
</html>