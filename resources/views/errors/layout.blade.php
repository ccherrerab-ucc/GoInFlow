<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evidentia — @yield('title', 'Error')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0C447C;
            --primary-dark: #042C53;
            --primary-light: #E6F1FB;
            --primary-border: #B5D4F4;
            --gray-bg: #E8EDF4;
            --gray-50: #F1EFE8;
            --gray-100: #D3D1C7;
            --gray-600: #5F5E5A;
            --gray-900: #2C2C2A;
            --danger-bg: #FCEBEB;
            --danger-text: #A32D2D;
            --danger-border: #F09595;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-bg);
            color: var(--gray-900);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(12,68,124,.10);
            padding: 48px 56px;
            max-width: 520px;
            width: 100%;
            text-align: center;
        }
        .error-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 32px;
        }
        .error-logo-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-logo-icon i { color: #fff; font-size: 18px; }
        .error-logo-text { font-size: 20px; font-weight: 600; color: var(--primary); }
        .error-code {
            font-size: 72px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 8px;
        }
        .error-title {
            font-size: 22px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 12px;
        }
        .error-message {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .error-divider {
            width: 48px;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
            margin: 0 auto 24px;
        }
        .btn-gf-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
        }
        .btn-gf-primary:hover { background: var(--primary-dark); color: #fff; }
        .btn-gf-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary-border);
            border-radius: 8px;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
        }
        .btn-gf-outline:hover { background: var(--primary-light); color: var(--primary); }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-logo">
            <div class="error-logo-icon"><i class="bi bi-diagram-3-fill"></i></div>
            <span class="error-logo-text">Evidentia</span>
        </div>
        @yield('body')
    </div>
</body>
</html>
