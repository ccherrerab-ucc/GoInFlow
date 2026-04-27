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

        /*.login-wrapper {
            width: 100%;
            max-width: 920px;
            min-height: 540px;
            display: flex;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(12, 68, 124, 0.12);
        }*/
        .login-wrapper {
            width: 100%;
            max-width: 920px;
            min-height: 540px;
            display: flex;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(12, 68, 124, 0.12);
        }

        /* 📱 Responsive */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 100%;
                min-height: auto;
                border-radius: 0;
            }
        }

        @media (max-width: 768px) {
            .panel-brand {
                width: 100%;
                padding: 25px 20px;
                border-radius: 0;
            }
            .login-wrapper {
                flex-direction: column;
                max-width: 100%;
                height: 100vh;
                border-radius: 0;
            }

        }

        @media (max-width: 768px) {
            .panel-form {
                width: 100%;
                padding: 25px 20px;
                border-radius: 0;
            }

            .login-wrapper {
                flex-direction: column;
                max-width: 100%;
                height: 100vh;
                border-radius: 0;
            }
        }

       

        

        @media (max-width: 768px) {
            .form-control-goinflow {
                height: 45px;
                font-size: 0.9rem;
            }

            .btn-goinflow {
                padding: 12px;
                font-size: 0.95rem;
            }
        }

        /*.panel-brand {
            width: 42%;
            background: #0C447C;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }*/

        .panel-brand {
            width: 42%;
            background: #0067ad;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 12px;
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .panel-brand .logo-universidad {
            max-height: 100px;
            width: auto;
            margin-bottom: 0.5rem;
        }

        .panel-brand .logo-universidad-white {
            filter: brightness(0) invert(1);
        }

        /* Ajuste para el logo en blanco sobre fondo azul */
        .panel-brand img {
            max-height: 80px;
            object-fit: contain;
        }

        .panel-brand img.brand-logo-white {
            filter: brightness(0) invert(1);
        }

        .panel-form {
            flex: 1;
            background: #ffffff;
            padding: 40px;

        }

        .panel-form .logo-universidad {
            max-height: 120px;
            width: auto;
            margin-bottom: 0.5rem;
            text-align: center;
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>