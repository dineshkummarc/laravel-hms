<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HMS Hospital — Advanced Care, Modern Medicine">
    <title>@yield('title', 'HMS Hospital')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <style>
        /* ── CSS Variables ── */
        :root {
            --navy:    #050d1a;
            --navy2:   #0b1930;
            --navy3:   #0f2040;
            --teal:    #00c9b1;
            --teal2:   #00e5cc;
            --blue:    #1a6bff;
            --amber:   #f5a623;
            --rose:    #f54263;
            --glass:   rgba(255,255,255,0.04);
            --border:  rgba(255,255,255,0.08);
            --text:    #e8edf5;
            --muted:   #7a8ba8;
            --nav-h:   68px;
        }

        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--navy);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        h1,h2,h3,h4,h5,h6 { font-family: 'Syne', sans-serif; line-height: 1.15; }
        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }
        button { cursor: pointer; font-family: inherit; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy); }
        ::-webkit-scrollbar-thumb { background: var(--navy3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--teal); }

        /* ── Selection ── */
        ::selection { background: rgba(0,201,177,.25); color: #fff; }

        /* ══════════════════════════════════════
           NAVIGATION
        ══════════════════════════════════════ */
        #hms-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--nav-h);
            z-index: 1000;
            background: rgba(5,13,26,0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            transition: background .3s, box-shadow .3s;
        }
        #hms-nav.scrolled {
            background: rgba(5,13,26,0.96);
            box-shadow: 0 4px 40px rgba(0,0,0,.4);
        }
        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .nav-logo-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--teal), var(--blue));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800; font-size: 14px; color: #fff;
            flex-shrink: 0;
        }
        .nav-logo-text {
            font-family: 'Syne', sans-serif;
            font-weight: 700; font-size: 18px;
            color: var(--text);
        }
        .nav-logo-text span { color: var(--teal); }

        /* Desktop links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2px;
            list-style: none;
        }
        .nav-links a {
            display: block;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--muted);
            transition: color .2s, background .2s;
            white-space: nowrap;
        }
        .nav-links a:hover,
        .nav-links a.active {
            color: var(--teal);
            background: rgba(0,201,177,.08);
        }

        /* Dropdown */
        .nav-dropdown { position: relative; }
        .nav-dropdown > a::after {
            content: ' ▾';
            font-size: 10px;
            opacity: .6;
        }
        .nav-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 200px;
            background: var(--navy2);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 8px;
            box-shadow: 0 20px 60px rgba(0,0,0,.5);
            z-index: 100;
        }
        .nav-dropdown:hover .nav-dropdown-menu { display: block; }
        .nav-dropdown-menu a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 8px;
            font-size: 13px; color: var(--muted);
            transition: all .15s;
        }
        .nav-dropdown-menu a:hover { background: rgba(0,201,177,.08); color: var(--teal); }
        .nav-dropdown-menu a .dm-icon { font-size: 16px; }

        /* Buttons */
        .nav-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 20px; border-radius: 10px;
            font-family: 'Syne', sans-serif;
            font-weight: 700; font-size: 13px;
            border: none; transition: all .2s; white-space: nowrap;
        }
        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { color: var(--text); border-color: rgba(255,255,255,.2); }
        .btn-primary {
            background: linear-gradient(135deg, var(--teal), var(--blue));
            color: #fff;
            box-shadow: 0 4px 20px rgba(0,201,177,.3);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(0,201,177,.4);
        }
        .btn-emergency {
            background: var(--rose);
            color: #fff;
            box-shadow: 0 4px 20px rgba(245,66,99,.3);
            animation: pulseRed 2s ease-in-out infinite;
        }
        @keyframes pulseRed {
            0%,100% { box-shadow: 0 4px 20px rgba(245,66,99,.3); }
            50%      { box-shadow: 0 4px 30px rgba(245,66,99,.6); }
        }

        /* Mobile hamburger */
        .nav-hamburger {
            display: none;
            flex-direction: column; justify-content: center; align-items: center;
            width: 38px; height: 38px; gap: 5px;
            background: transparent; border: 1px solid var(--border); border-radius: 8px;
        }
        .nav-hamburger span {
            width: 18px; height: 2px;
            background: var(--text); border-radius: 2px;
            transition: all .3s;
        }
        .nav-hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
        .nav-hamburger.open span:nth-child(2) { opacity: 0; }
        .nav-hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px,-5px); }

        /* Mobile drawer */
        #hms-mobile-menu {
            display: none;
            position: fixed;
            top: var(--nav-h); left: 0; right: 0;
            background: var(--navy2);
            border-bottom: 1px solid var(--border);
            z-index: 999;
            padding: 20px 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,.5);
        }
        #hms-mobile-menu.open { display: block; }
        .mobile-nav-link {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 4px;
            border-bottom: 1px solid var(--border);
            font-size: 15px; font-weight: 500; color: var(--text);
            transition: color .2s;
        }
        .mobile-nav-link:last-child { border-bottom: none; }
        .mobile-nav-link:hover { color: var(--teal); }
        .mobile-nav-actions { display: flex; gap: 10px; margin-top: 16px; }
        .mobile-nav-actions .btn { flex: 1; justify-content: center; }

        /* Emergency ticker in nav */
        .nav-ticker {
            background: rgba(245,66,99,.12);
            border: 1px solid rgba(245,66,99,.25);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 11px; font-weight: 600;
            color: var(--rose);
            display: flex; align-items: center; gap: 6px;
            white-space: nowrap;
        }
        .nav-ticker .dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--rose);
            animation: pulseDot 1.4s infinite;
        }
        @keyframes pulseDot {
            0%,100%{opacity:1;transform:scale(1)}
            50%{opacity:.4;transform:scale(1.5)}
        }

        /* ══════════════════════════════════════
           PAGE OFFSET (so content doesn't hide under fixed nav)
        ══════════════════════════════════════ */
        .page-body { padding-top: var(--nav-h); }

        /* ══════════════════════════════════════
           FLASH MESSAGES
        ══════════════════════════════════════ */
        .flash-wrap {
            position: fixed; top: calc(var(--nav-h) + 12px);
            right: 20px; z-index: 998;
            display: flex; flex-direction: column; gap: 8px;
            max-width: 360px; width: calc(100% - 40px);
        }
        .flash {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 14px 16px; border-radius: 12px;
            border: 1px solid;
            animation: slideIn .3s ease;
            backdrop-filter: blur(12px);
        }
        .flash-success { background: rgba(0,201,177,.12); border-color: rgba(0,201,177,.3); color: var(--teal2); }
        .flash-error   { background: rgba(245,66,99,.12);  border-color: rgba(245,66,99,.3);  color: #ff7096; }
        .flash-warning { background: rgba(245,166,35,.12); border-color: rgba(245,166,35,.3); color: var(--amber); }
        .flash-info    { background: rgba(26,107,255,.12); border-color: rgba(26,107,255,.3); color: #6fa3ff; }
        .flash-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
        .flash-body { flex: 1; font-size: 13px; font-weight: 500; line-height: 1.5; }
        .flash-close {
            background: none; border: none; color: inherit; opacity: .5;
            font-size: 16px; padding: 0; margin-left: 4px; flex-shrink: 0;
            cursor: pointer; transition: opacity .2s;
        }
        .flash-close:hover { opacity: 1; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ══════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════ */
        #hms-main { min-height: calc(100vh - var(--nav-h)); }

        /* ══════════════════════════════════════
           FOOTER
        ══════════════════════════════════════ */
        #hms-footer {
            background: #020810;
            border-top: 1px solid var(--border);
        }
        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 64px 24px 32px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }
        .footer-brand-desc {
            font-size: 13.5px; color: var(--muted);
            line-height: 1.8; margin: 12px 0 20px;
            max-width: 280px;
        }
        .footer-social { display: flex; gap: 10px; }
        .footer-social a {
            width: 36px; height: 36px; border-radius: 9px;
            background: var(--glass); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; transition: border-color .2s, transform .2s;
        }
        .footer-social a:hover { border-color: var(--teal); transform: translateY(-2px); }
        .footer-col-title {
            font-family: 'Syne', sans-serif;
            font-size: 11px; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase;
            color: var(--text); margin-bottom: 16px;
        }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 9px; }
        .footer-links a {
            font-size: 13.5px; color: var(--muted);
            transition: color .2s;
        }
        .footer-links a:hover { color: var(--teal); }
        .footer-divider { height: 1px; background: var(--border); margin-bottom: 28px; }
        .footer-bottom {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
            font-size: 12.5px; color: var(--muted);
        }
        .footer-status { display: flex; align-items: center; gap: 7px; }
        .footer-status-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--teal);
            animation: pulseDot 2s infinite;
        }
        .footer-badges { display: flex; gap: 8px; flex-wrap: wrap; }
        .footer-badge {
            padding: 4px 10px; border-radius: 6px;
            background: rgba(255,255,255,.04); border: 1px solid var(--border);
            font-size: 11px; color: var(--muted);
        }

        /* ══════════════════════════════════════
           BREADCRUMB (used on inner pages)
        ══════════════════════════════════════ */
        .breadcrumb {
            max-width: 1280px; margin: 0 auto;
            padding: 16px 24px;
            display: flex; align-items: center; gap: 8px;
            font-size: 12.5px; color: var(--muted);
            border-bottom: 1px solid var(--border);
        }
        .breadcrumb a { color: var(--muted); transition: color .2s; }
        .breadcrumb a:hover { color: var(--teal); }
        .breadcrumb-sep { opacity: .4; }
        .breadcrumb-current { color: var(--text); }

        /* ══════════════════════════════════════
           EMERGENCY FLOATING BUTTON
        ══════════════════════════════════════ */
        .emergency-fab {
            position: fixed;
            bottom: 28px; right: 28px;
            z-index: 900;
            display: flex; flex-direction: column; align-items: flex-end; gap: 10px;
        }
        .emergency-fab-main {
            display: flex; align-items: center; gap: 8px;
            background: var(--rose); color: #fff;
            padding: 13px 20px; border-radius: 50px;
            font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px;
            border: none; box-shadow: 0 8px 30px rgba(245,66,99,.45);
            animation: pulseRed 2s infinite;
            transition: transform .2s;
        }
        .emergency-fab-main:hover { transform: scale(1.05); }
        .emergency-fab-sub {
            background: var(--navy2); border: 1px solid var(--border);
            color: var(--text); padding: 10px 16px; border-radius: 50px;
            font-size: 12px; font-weight: 500; display: flex; align-items: center; gap: 7px;
            box-shadow: 0 4px 20px rgba(0,0,0,.3);
            transition: border-color .2s;
        }
        .emergency-fab-sub:hover { border-color: var(--teal); color: var(--teal); }

        /* ══════════════════════════════════════
           CART BADGE (top nav icon)
        ══════════════════════════════════════ */
        .cart-icon-wrap { position: relative; display: inline-flex; }
        .cart-badge {
            position: absolute; top: -6px; right: -6px;
            width: 18px; height: 18px; border-radius: 50%;
            background: var(--rose); color: #fff;
            font-size: 9px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
        }

        /* ══════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════ */
        @media (max-width: 1100px) {
            .nav-links .hide-md { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 36px; }
        }
        @media (max-width: 768px) {
            .nav-links, .nav-ticker { display: none !important; }
            .nav-hamburger { display: flex; }
            .footer-grid { grid-template-columns: 1fr; gap: 28px; }
            .emergency-fab { bottom: 16px; right: 16px; }
            .emergency-fab-main { padding: 12px 16px; font-size: 12px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ╔══════════════════════════════════════════════
     ║  NAVIGATION
     ╚══════════════════════════════════════════════ -->
<nav class="bg-white shadow p-4 flex justify-between items-center relative z-20">
    <a href="{{ route('home') }}" class="font-bold text-2xl text-blue-700">HMS Hospital</a>
    <div class="hidden md:flex space-x-5 text-gray-600 font-medium items-center">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
        <a href="{{ route('doctors.index') }}" class="hover:text-blue-600">Doctors</a>
        <a href="{{ route('departments.public') }}" class="hover:text-blue-600">Departments</a>
        <a href="{{ route('services') }}" class="hover:text-blue-600">Services</a>
        <a href="{{ route('lab.tests') }}" class="hover:text-blue-600">Lab Tests</a>
        <a href="{{ route('ambulance.create') }}" class="hover:text-red-600">Ambulance</a>
        <a href="{{ route('notices.public') }}" class="hover:text-blue-600">Notices</a>
        <a href="{{ route('about') }}" class="hover:text-blue-600">About</a>
        <a href="{{ route('contact') }}" class="hover:text-blue-600">Contact</a>
        <a href="{{ route('cart.index') }}" class="hover:text-blue-600">Cart</a>
        @auth
            <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">Portal</a>
        @else
            <a href="{{ route('login') }}" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-900 transition">Login</a>
        @endauth
    </div>
    <button type="button" id="hms-hamburger" class="nav-hamburger md:hidden" aria-label="Menu"><span></span><span></span><span></span></button>
</nav>

<!-- Mobile drawer -->
<div id="hms-mobile-menu">
    @foreach([
        ['Home','/'],
        ['Services','/services'],
        ['Departments','/departments'],
        ['Doctors','/doctor'],
        ['Lab Tests','/lab-tests'],
        ['Ambulance','/ambulance'],
        ['Notices','/notices'],
        ['About','/about'],
        ['Contact','/contact'],
        ['Cart','/cart'],
    ] as $ml)
    <a href="{{ url($ml[1]) }}" class="mobile-nav-link">
        {{ $ml[0] }} <span style="color:var(--muted);font-size:12px">›</span>
    </a>
    @endforeach

    <div class="mobile-nav-actions">
        @auth
            <a href="{{ url('/patient/dashboard') }}" class="btn btn-primary">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
        @endauth
    </div>
</div>


<!-- ╔══════════════════════════════════════════════
     ║  FLASH MESSAGES
     ╚══════════════════════════════════════════════ -->
<div class="flash-wrap" id="hms-flashes">
    @if(session('success'))
    <div class="flash flash-success" id="flash-success">
        <span class="flash-icon">✓</span>
        <div class="flash-body">{{ session('success') }}</div>
        <button class="flash-close" onclick="closeFlash('flash-success')">✕</button>
    </div>
    @endif
    @if(session('error'))
    <div class="flash flash-error" id="flash-error">
        <span class="flash-icon">✕</span>
        <div class="flash-body">{{ session('error') }}</div>
        <button class="flash-close" onclick="closeFlash('flash-error')">✕</button>
    </div>
    @endif
    @if(session('warning'))
    <div class="flash flash-warning" id="flash-warning">
        <span class="flash-icon">⚠</span>
        <div class="flash-body">{{ session('warning') }}</div>
        <button class="flash-close" onclick="closeFlash('flash-warning')">✕</button>
    </div>
    @endif
    @if(session('info'))
    <div class="flash flash-info" id="flash-info">
        <span class="flash-icon">ℹ</span>
        <div class="flash-body">{{ session('info') }}</div>
        <button class="flash-close" onclick="closeFlash('flash-info')">✕</button>
    </div>
    @endif
    @if($errors->any())
    <div class="flash flash-error" id="flash-val">
        <span class="flash-icon">✕</span>
        <div class="flash-body">{{ $errors->first() }}</div>
        <button class="flash-close" onclick="closeFlash('flash-val')">✕</button>
    </div>
    @endif
</div>


<!-- ╔══════════════════════════════════════════════
     ║  PAGE CONTENT
     ╚══════════════════════════════════════════════ -->
<div class="page-body">
    @hasSection('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        @yield('breadcrumb')
    </div>
    @endif

    <main id="hms-main">
        @yield('content')
    </main>
</div>


<!-- ╔══════════════════════════════════════════════
     ║  FOOTER
     ╚══════════════════════════════════════════════ -->
<footer id="hms-footer">
    <div class="footer-inner">
        <div class="footer-grid">

            <!-- Brand col -->
            <div>
                <a href="{{ url('/') }}" class="nav-logo" style="display:inline-flex">
                    <div class="nav-logo-icon">H+</div>
                    <span class="nav-logo-text">HMS <span>Hospital</span></span>
                </a>
                <p class="footer-brand-desc">
                    Delivering advanced, compassionate healthcare since 1998. Accredited by JCIA &amp; ISO 9001:2015.
                </p>
                <div class="footer-social">
                    @foreach(['📘','📸','🐦','▶️'] as $social)
                    <a href="#" aria-label="Social">{{ $social }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Quick links -->
            <div>
                <div class="footer-col-title">Quick Links</div>
                <ul class="footer-links">
                    @foreach([['Home','/'],['Services','/services'],['Departments','/departments'],['Doctors','/doctor'],['Lab Tests','/lab-tests'],['Careers','/careers']] as $l)
                    <li><a href="{{ url($l[1]) }}">{{ $l[0] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Patients -->
            <div>
                <div class="footer-col-title">Patients</div>
                <ul class="footer-links">
                    @foreach([['Book Appointment','/doctor'],['Patient Portal','/patient/dashboard'],['Lab Tests','/lab-tests'],['Ambulance','/ambulance'],['Notices','/notices'],['Insurance','/insurance']] as $l)
                    <li><a href="{{ url($l[1]) }}">{{ $l[0] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Legal & Contact -->
            <div>
                <div class="footer-col-title">Contact & Legal</div>
                <ul class="footer-links">
                    <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                    <li><a href="{{ url('/about') }}">About HMS</a></li>
                    <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('/terms') }}">Terms of Service</a></li>
                    <li><a href="{{ url('/anti-fraud-policy') }}">Anti-Fraud Policy</a></li>
                    <li><a href="{{ url('/accessibility') }}">Accessibility</a></li>
                </ul>
                <div style="margin-top:20px;padding:14px;border-radius:12px;background:rgba(245,66,99,.08);border:1px solid rgba(245,66,99,.2)">
                    <div style="font-size:11px;color:var(--rose);font-weight:700;letter-spacing:.05em;text-transform:uppercase;margin-bottom:6px">🚨 Emergency</div>
                    <a href="tel:+8801700000000" style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:#fff">+880 1700-000000</a>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px">Available 24 hours / 7 days</div>
                </div>
            </div>

        </div>

        <div class="footer-divider"></div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} HMS Hospital. All rights reserved.</span>
            <div class="footer-badges">
                <span class="footer-badge">JCIA Accredited</span>
                <span class="footer-badge">ISO 9001:2015</span>
                <span class="footer-badge">DGDA Licensed</span>
            </div>
            <div class="footer-status">
                <span class="footer-status-dot"></span>
                All systems operational
            </div>
        </div>
    </div>
</footer>


<!-- ╔══════════════════════════════════════════════
     ║  EMERGENCY FAB
     ╚══════════════════════════════════════════════ -->
<div class="emergency-fab">
    <a href="{{ url('/ambulance') }}" class="emergency-fab-sub">
        🚑 Ambulance
    </a>
    <a href="tel:+8801700000000" class="emergency-fab-main">
        🚨 Emergency
    </a>
</div>


<!-- ╔══════════════════════════════════════════════
     ║  SCRIPTS
     ╚══════════════════════════════════════════════ -->
<script>
    const nav = document.getElementById('hms-nav') || document.querySelector('nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });
    }

    const hamburger = document.getElementById('hms-hamburger');
    const mobileMenu = document.getElementById('hms-mobile-menu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            mobileMenu.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (nav && !nav.contains(e.target) && !mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('open');
                mobileMenu.classList.remove('open');
            }
        });
    }

    /* ── Flash auto-dismiss ── */
    function closeFlash(id) {
        const el = document.getElementById(id);
        if (el) { el.style.opacity = '0'; el.style.transform = 'translateX(20px)'; el.style.transition = 'all .3s'; setTimeout(() => el.remove(), 300); }
    }
    setTimeout(() => {
        document.querySelectorAll('.flash').forEach(f => closeFlash(f.id));
    }, 5000);

    /* ── Active nav link ── */
    const path = window.location.pathname;
    document.querySelectorAll('.nav-links a').forEach(a => {
        if (a.getAttribute('href') === path) a.classList.add('active');
    });

    /* ── Scroll-reveal ── */
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('[data-reveal]').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(24px)';
        el.style.transition = 'opacity .6s ease, transform .6s ease';
        revealObserver.observe(el);
    });
    document.addEventListener('animationend', () => {});
    // applied via class
    document.head.insertAdjacentHTML('beforeend', `<style>
        .revealed { opacity: 1 !important; transform: translateY(0) !important; }
        [data-reveal-delay="1"] { transition-delay: .1s !important; }
        [data-reveal-delay="2"] { transition-delay: .2s !important; }
        [data-reveal-delay="3"] { transition-delay: .3s !important; }
        [data-reveal-delay="4"] { transition-delay: .4s !important; }
    </style>`);
</script>

@stack('scripts')
</body>
</html>
