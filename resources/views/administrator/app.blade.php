<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
 
    <title>GoInFlow — @yield('title', 'Dashboard')</title>
 
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
 
    <style>
        /* ─── Variables institucionales GoInFlow ─── */
        :root {
            --primary:        #0C447C;
            --primary-dark:   #042C53;
            --primary-mid:    #185FA5;
            --primary-light:  #E6F1FB;
            --primary-border: #B5D4F4;
            --gray-bg:        #E8EDF4;
            --gray-50:        #F1EFE8;
            --gray-100:       #D3D1C7;
            --gray-400:       #888780;
            --gray-600:       #5F5E5A;
            --gray-900:       #2C2C2A;
            --sidebar-w:      260px;
            --topbar-h:       60px;
            --success-bg:     #EAF3DE;
            --success-text:   #27500A;
            --success-border: #97C459;
            --danger-bg:      #FCEBEB;
            --danger-text:    #A32D2D;
            --danger-border:  #F09595;
            --warning-bg:     #FAEEDA;
            --warning-text:   #633806;
            --warning-border: #EF9F27;
        }
 
        * { box-sizing: border-box; margin: 0; padding: 0; }
 
        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-bg);
            color: var(--gray-900);
            min-height: 100vh;
            padding-top: var(--topbar-h);
        }
 
        /* ═══════════════════════════════════
         |  TOP NAVBAR
         ═══════════════════════════════════ */
        .gf-topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-h);
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1030;
            border-bottom: 1px solid var(--primary-dark);
        }
 
        .gf-topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
 
        /* Botón hamburguesa solo en móvil */
        .gf-sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            line-height: 1;
        }
 
        .gf-sidebar-toggle:hover { background: rgba(255,255,255,0.1); }
 
        .gf-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
 
        .gf-brand-icon {
            width: 34px;
            height: 34px;
            background: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
 
        .gf-brand-icon i {
            color: var(--primary);
            font-size: 17px;
        }
 
        .gf-brand-name {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            letter-spacing: -0.2px;
        }
 
        .gf-brand-sub {
            font-size: 11px;
            color: var(--primary-border);
            font-weight: 400;
        }
 
        .gf-topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
 
        /* Badge de rol */
        .gf-role-badge {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 10px;
        }
 
        /* Menú usuario */
        .gf-user-menu .dropdown-toggle {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 4px;
        }
 
        .gf-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 1.5px solid rgba(255,255,255,0.3);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }
 
        .gf-user-name {
            color: #fff;
            font-size: 13px;
            font-weight: 500;
        }
 
        .gf-user-menu .dropdown-menu {
            min-width: 200px;
            border: 1px solid var(--gray-100);
            border-radius: 10px;
            padding: 6px;
            margin-top: 8px;
            box-shadow: 0 4px 16px rgba(12,68,124,0.12);
        }
 
        .gf-user-menu .dropdown-item {
            font-size: 13px;
            border-radius: 6px;
            padding: 8px 12px;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }
 
        .gf-user-menu .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }
 
        .gf-user-menu .dropdown-item.text-danger:hover {
            background: var(--danger-bg);
            color: var(--danger-text);
        }
 
        .gf-user-menu .dropdown-divider {
            border-color: var(--gray-100);
            margin: 4px 0;
        }
 
        /* ═══════════════════════════════════
         |  SIDEBAR
         ═══════════════════════════════════ */
        .gf-sidebar {
            position: fixed;
            top: var(--topbar-h);
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid var(--gray-100);
            display: flex;
            flex-direction: column;
            z-index: 1020;
            transition: transform 0.25s ease;
            overflow-y: auto;
        }
 
        .gf-sidebar-header {
            padding: 16px 20px 12px;
            border-bottom: 1px solid var(--gray-100);
        }
 
        .gf-sidebar-header-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
 
        /* Sección del sidebar */
        .gf-nav-section {
            padding: 16px 20px 4px;
        }
 
        .gf-nav-section-title {
            font-size: 10px;
            font-weight: 600;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
 
        /* Items de navegación */
        .gf-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-600);
            text-decoration: none;
            border-radius: 0;
            transition: background 0.15s, color 0.15s;
            border-left: 2px solid transparent;
            position: relative;
        }
 
        .gf-nav-item i {
            font-size: 15px;
            width: 18px;
            text-align: center;
            color: var(--gray-400);
            flex-shrink: 0;
        }
 
        .gf-nav-item:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-left-color: var(--primary-border);
        }
 
        .gf-nav-item:hover i { color: var(--primary); }
 
        .gf-nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 600;
        }
 
        .gf-nav-item.active i { color: var(--primary); }
 
        /* Badge contador en nav */
        .gf-nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 1px 7px;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }
 
        /* Divider del sidebar */
        .gf-nav-divider {
            border: none;
            border-top: 1px solid var(--gray-100);
            margin: 8px 0;
        }
 
        /* Pie del sidebar — info del programa */
        .gf-sidebar-footer {
            margin-top: auto;
            padding: 14px 20px;
            border-top: 1px solid var(--gray-100);
            background: var(--primary-light);
        }
 
        .gf-sidebar-footer-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 2px;
        }
 
        .gf-sidebar-footer-sub {
            font-size: 10px;
            color: var(--primary-mid);
        }
 
        /* ═══════════════════════════════════
         |  OVERLAY MÓVIL
         ═══════════════════════════════════ */
        .gf-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1015;
        }
 
        .gf-overlay.show { display: block; }
 
        /* ═══════════════════════════════════
         |  CONTENIDO PRINCIPAL
         ═══════════════════════════════════ */
        .gf-main {
            margin-left: var(--sidebar-w);
            padding: 28px 28px 40px;
            min-height: calc(100vh - var(--topbar-h));
            transition: margin-left 0.25s ease;
        }
 
        /* Breadcrumb */
        .gf-breadcrumb {
            font-size: 12px;
            color: var(--gray-400);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
 
        .gf-breadcrumb a {
            color: var(--primary-mid);
            text-decoration: none;
        }
 
        .gf-breadcrumb a:hover { text-decoration: underline; }
        .gf-breadcrumb-sep { color: var(--gray-100); }
 
        /* Título de página */
        .gf-page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 4px;
        }
 
        .gf-page-sub {
            font-size: 13px;
            color: var(--gray-600);
            margin-bottom: 24px;
        }
 
        /* ─── Cards ─── */
        .gf-card {
            background: #fff;
            border: 1px solid var(--gray-100);
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }
 
        .gf-card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--gray-50);
            display: flex;
            align-items: center;
            gap: 8px;
        }
 
        /* ─── Métricas ─── */
        .gf-metric {
            background: var(--gray-50);
            border: 1px solid var(--gray-100);
            border-radius: 10px;
            padding: 16px 18px;
        }
 
        .gf-metric-label {
            font-size: 11px;
            color: var(--gray-600);
            font-weight: 500;
            margin-bottom: 6px;
        }
 
        .gf-metric-value {
            font-size: 26px;
            font-weight: 600;
            color: var(--primary);
            line-height: 1;
        }
 
        .gf-metric-sub {
            font-size: 11px;
            color: var(--gray-400);
            margin-top: 6px;
        }
 
        /* ─── Alertas / Mensajes de sesión ─── */
        .gf-alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }
 
        .gf-alert-success {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }
 
        .gf-alert-danger {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
        }
 
        .gf-alert-warning {
            background: var(--warning-bg);
            border: 1px solid var(--warning-border);
            color: var(--warning-text);
        }
 
        /* ─── Botones ─── */
        .gf-btn {
            height: 38px;
            padding: 0 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }
 
        .gf-btn-primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }
 
        .gf-btn-primary:hover {
            background: var(--primary-mid);
            border-color: var(--primary-mid);
            color: #fff;
        }
 
        .gf-btn-outline {
            background: #fff;
            color: var(--primary);
            border-color: var(--primary-border);
        }
 
        .gf-btn-outline:hover {
            background: var(--primary-light);
            color: var(--primary);
        }
 
        .gf-btn-danger {
            background: var(--danger-bg);
            color: var(--danger-text);
            border-color: var(--danger-border);
        }
 
        .gf-btn-danger:hover {
            background: #F7C1C1;
            color: var(--danger-text);
        }
 
        /* ─── Tabla institucional ─── */
        .gf-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
 
        .gf-table th {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--primary-border);
        }
 
        .gf-table td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--gray-50);
            color: var(--gray-900);
            vertical-align: middle;
        }
 
        .gf-table tbody tr:hover td { background: var(--gray-50); }
        .gf-table tbody tr:last-child td { border-bottom: none; }
 
        /* ─── Estado badges ─── */
        .gf-status {
            display: inline-block;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 10px;
        }
 
        .gf-status-aprobado  { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-border); }
        .gf-status-revision  { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }
        .gf-status-borrador  { background: var(--gray-50); color: var(--gray-600); border: 1px solid var(--gray-100); }
        .gf-status-rechazado { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger-border); }
 
        /* ─── Forms ─── */
        .gf-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #444441;
            margin-bottom: 6px;
        }
 
        .gf-input {
            width: 100%;
            height: 38px;
            border: 1px solid var(--gray-100);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 13px;
            color: var(--gray-900);
            background: var(--gray-50);
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
 
        .gf-input:focus {
            outline: none;
            border-color: var(--primary-mid);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(24,95,165,0.1);
        }
 
        .gf-textarea {
            width: 100%;
            border: 1px solid var(--gray-100);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            color: var(--gray-900);
            background: var(--gray-50);
            font-family: 'Inter', sans-serif;
            resize: vertical;
            min-height: 80px;
        }
 
        .gf-textarea:focus {
            outline: none;
            border-color: var(--primary-mid);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(24,95,165,0.1);
        }
 
        .gf-select {
            width: 100%;
            height: 38px;
            border: 1px solid var(--gray-100);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 13px;
            color: var(--gray-900);
            background: var(--gray-50);
            font-family: 'Inter', sans-serif;
        }
 
        .gf-select:focus {
            outline: none;
            border-color: var(--primary-mid);
            box-shadow: 0 0 0 3px rgba(24,95,165,0.1);
        }
 
        .gf-field-error {
            font-size: 11px;
            color: var(--danger-text);
            margin-top: 4px;
        }
 
        /* ═══════════════════════════════════
         |  RESPONSIVE
         ═══════════════════════════════════ */
        @media (max-width: 991px) {
            .gf-sidebar { transform: translateX(-100%); }
            .gf-sidebar.show { transform: translateX(0); }
            .gf-sidebar-toggle { display: block; }
            .gf-main { margin-left: 0; padding: 20px 16px 32px; }
            .gf-role-badge { display: none; }
            .gf-user-name { display: none; }
        }
    </style>
 
    @stack('styles')
</head>
 
<body>
 
    <!-- ═══ TOP NAVBAR ═══ -->
    <nav class="gf-topbar">
        <div class="gf-topbar-left">
            <button class="gf-sidebar-toggle" id="sidebarToggle" aria-label="Menú">
                <i class="bi bi-list"></i>
            </button>
            <a class="gf-brand" href="{{ route('administrator.app') }}">
                <div class="gf-brand-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <div>
                    <div class="gf-brand-name">GoInFlow</div>
                    <div class="gf-brand-sub">UCatólica · Factor 5</div>
                </div>
            </a>
        </div>
 
        <div class="gf-topbar-right">
            @auth
                {{-- Badge de rol --}}
                <span class="gf-role-badge">
                    {{ Auth::user()->getRolNombre() }}
                </span>
 
                {{-- Menú de usuario --}}
                <div class="gf-user-menu dropdown">
                    <button class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="gf-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->first_surname ?? '', 0, 1)) }}
                        </div>
                        <span class="gf-user-name">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down" style="color:#fff;font-size:11px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div style="padding:10px 14px 8px;">
                                <div style="font-size:13px;font-weight:600;color:var(--gray-900);">
                                    {{ Auth::user()->name }} {{ Auth::user()->first_surname }}
                                </div>
                                <div style="font-size:11px;color:var(--gray-400);">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> Mi perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </nav>
 
    <!-- ═══ OVERLAY MÓVIL ═══ -->
    <div class="gf-overlay" id="sidebarOverlay"></div>
 
    <!-- ═══ SIDEBAR ═══ -->
    <aside class="gf-sidebar" id="sidebar">
        <div class="gf-sidebar-header">
            <div class="gf-sidebar-header-title">Navegación principal</div>
        </div>
 
        <nav style="flex:1; padding: 8px 0;">
 
            {{-- GENERAL --}}
            <div class="gf-nav-section">
                <div class="gf-nav-section-title">General</div>
            </div>
 
            <a class="gf-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
 
            {{-- ACREDITACIÓN CNA --}}
            <hr class="gf-nav-divider">
            <div class="gf-nav-section">
                <div class="gf-nav-section-title">Acreditación CNA</div>
            </div>
 
            <a class="gf-nav-item {{ request()->routeIs('factores*') ? 'active' : '' }}"
               href="{{ route('factores.index') }}">
                <i class="bi bi-bookmark-star"></i> Factor
            </a>
 
            <a class="gf-nav-item {{ request()->routeIs('caracteristicas*') ? 'active' : '' }}"
               href="{{ route('caracteristicas.index') }}">
                <i class="bi bi-diagram-3"></i> Características
            </a>
 
            <a class="gf-nav-item {{ request()->routeIs('aspectos*') ? 'active' : '' }}"
               href="{{ route('aspectos.index') }}">
                <i class="bi bi-list-check"></i> Aspectos por evaluar
            </a>
 
            {{-- GESTIÓN DOCUMENTAL --}}
            <hr class="gf-nav-divider">
            <div class="gf-nav-section">
                <div class="gf-nav-section-title">Gestión documental</div>
            </div>
 
            <a class="gf-nav-item {{ request()->routeIs('evidencias*') ? 'active' : '' }}"
               href="{{ route('evidencias.index') }}">
                <i class="bi bi-file-earmark-text"></i> Evidencias
            </a>
 
            <a class="gf-nav-item {{ request()->routeIs('resultados*') ? 'active' : '' }}"
               href="{{ route('resultados.index') }}">
                <i class="bi bi-bar-chart-line"></i> Resultados
            </a>
 
            {{-- ADMINISTRACIÓN --}}
            @auth
                @if(Auth::user()->isAdmin())
                    <hr class="gf-nav-divider">
                    <div class="gf-nav-section">
                        <div class="gf-nav-section-title">Administración</div>
                    </div>
 
                    <a class="gf-nav-item {{ request()->routeIs('usuarios*') ? 'active' : '' }}"
                       href="{{ route('usuarios.index') }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
 
                    <a class="gf-nav-item {{ request()->routeIs('auditoria*') ? 'active' : '' }}"
                       href="{{ route('administrator.auditoria') }}">
                        <i class="bi bi-shield-check"></i> Auditoría
                    </a>
                @endif
            @endauth
 
            {{-- SOPORTE --}}
            <hr class="gf-nav-divider">
            <div class="gf-nav-section">
                <div class="gf-nav-section-title">Soporte</div>
            </div>
 
            <!--a class="gf-nav-item {{ request()->routeIs('otras-apps*') ? 'active' : '' }}"
                target="_blank">
                <i class="bi bi-globe"></i> UCatólica
            </!--a-->
 
            <a class="gf-nav-item {{ request()->routeIs('ayuda*') ? 'active' : '' }}"
               href="{{ route('administrator.ayuda') }}">
                <i class="bi bi-question-circle"></i> Ayuda
            </a>
        </nav>
 
        {{-- Pie del sidebar --}}
        <div class="gf-sidebar-footer">
            <div class="gf-sidebar-footer-title">Factor 5 — CNA 2022</div>
            <div class="gf-sidebar-footer-sub">Ing. de Sistemas y Computación</div>
        </div>
    </aside>
 
    <!-- ═══ CONTENIDO PRINCIPAL ═══ -->
    <main class="gf-main" id="mainContent">
 
        {{-- Mensajes de sesión globales --}}
        @if(session('success'))
            <div class="gf-alert gf-alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
 
        @if(session('error'))
            <div class="gf-alert gf-alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif
 
        @if(session('warning'))
            <div class="gf-alert gf-alert-warning">
                <i class="bi bi-exclamation-circle"></i>
                {{ session('warning') }}
            </div>
        @endif
 
        @if($errors->any())
            <div class="gf-alert gf-alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
 
        {{-- Contenido de la vista hija --}}
        @yield('content')
    </main>
 
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar  = document.getElementById('sidebar');
            const overlay  = document.getElementById('sidebarOverlay');
            const toggle   = document.getElementById('sidebarToggle');
 
            function openSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
            }
 
            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
 
            toggle.addEventListener('click', function () {
                sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
            });
 
            overlay.addEventListener('click', closeSidebar);
 
            // Cerrar sidebar al hacer clic en un enlace en móvil
            sidebar.querySelectorAll('.gf-nav-item').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 992) closeSidebar();
                });
            });
        });
    </script>
 
    @stack('scripts')
</body>
</html>