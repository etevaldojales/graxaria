<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SisGraxaria - Motorista</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0f766e;
            --primary-light: #14b8a6;
            --primary-dark: #115e59;
            --secondary: #0ea5e9;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text: #0f172a;
            --text-muted: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--background);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: stretch;
        }

        .mobile-container {
            width: 100%;
            max-width: 480px;
            background-color: var(--background);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 24px 20px;
            border-bottom-left-radius: var(--radius-lg);
            border-bottom-right-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-subtitle {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 4px;
            font-weight: 400;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .logout-btn:active {
            background: rgba(255, 255, 255, 0.3);
        }

        main {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding-bottom: 40px;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: var(--radius-md);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:active {
            transform: scale(0.99);
            box-shadow: var(--shadow-sm);
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Large Touch Buttons */
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            height: 60px; /* Big touch targets */
            border: none;
            border-radius: var(--radius-md);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:active {
            background-color: var(--primary-dark);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: white;
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text-muted);
            border: 2px solid var(--text-muted);
        }

        /* Alert notifications */
        .alert {
            padding: 16px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border-left: 5px solid var(--success);
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 5px solid var(--danger);
        }

        .alert-warning {
            background-color: #fffbeb;
            color: #92400e;
            border-left: 5px solid var(--warning);
        }

        /* Simple Inputs */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            height: 56px;
            border-radius: var(--radius-md);
            border: 2px solid #cbd5e1;
            padding: 0 16px;
            font-size: 16px;
            font-weight: 500;
            background-color: #f8fafc;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            background-color: #ffffff;
        }

        textarea.form-control {
            height: 100px;
            padding: 12px 16px;
            resize: none;
        }

        /* Simple big checkbox toggles */
        .checkbox-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background-color: #f1f5f9;
            border-radius: var(--radius-md);
            margin-bottom: 12px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }

        .checkbox-container.checked {
            background-color: #f0fdf4;
            border-color: var(--success);
        }

        .checkbox-label {
            font-size: 16px;
            font-weight: 600;
        }

        .checkbox-switch {
            width: 50px;
            height: 28px;
            background-color: #cbd5e1;
            border-radius: 14px;
            position: relative;
            transition: background-color 0.2s;
        }

        .checkbox-switch::after {
            content: '';
            width: 22px;
            height: 22px;
            background-color: white;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            left: 3px;
            transition: transform 0.2s;
            box-shadow: var(--shadow-sm);
        }

        input[type="checkbox"] {
            display: none;
        }

        input[type="checkbox"]:checked + .checkbox-switch {
            background-color: var(--success);
        }

        input[type="checkbox"]:checked + .checkbox-switch::after {
            transform: translateX(22px);
        }

        /* Helper commission list */
        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item-title {
            font-size: 15px;
            font-weight: 600;
        }

        .list-item-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .list-item-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }

        .back-btn:active {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="mobile-container">
        <header>
            <div class="header-content">
                @if(request()->routeIs('mobile.dashboard') || request()->routeIs('mobile.login'))
                    <div>
                        <div class="header-title">
                            <span style="font-size: 24px;">🚛</span> SisGraxaria
                        </div>
                        <div class="header-subtitle">Painel do Motorista</div>
                    </div>
                @else
                    <a href="{{ route('mobile.dashboard') }}" class="back-btn">
                        <span style="font-size: 20px;">⬅</span> Voltar
                    </a>
                @endif

                @auth
                    <form action="{{ route('mobile.logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-btn">Sair</button>
                    </form>
                @endauth
            </div>
        </header>

        <main>
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    <span>🔔</span> {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
