<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EVIDENTIA — Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #E8EDF4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 920px;
            min-height: 540px;
            display: flex;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(12, 68, 124, 0.12);
        }

        .panel-brand {
            width: 42%;
            background: #0C447C;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .panel-form {
            flex: 1;
            background: white;
            padding: 40px;
        }

        .form-control-goinflow {
            width: 100%;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .btn-goinflow {
            width: 100%;
            background: #0C447C;
            color: white;
            border-radius: 8px;
            padding: 10px;
        }

    </style>
</head>

<body>

    <div class="login-wrapper">
        {{ $slot }}
    </div>

</body>
</html>