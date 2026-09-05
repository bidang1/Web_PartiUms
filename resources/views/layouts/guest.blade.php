<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PARTI Himatif UMS') }} | Panel Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

        <!-- PWA Meta Tags -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#0c0d0e">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" href="{{ asset('icon-192.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* ponytail: scoped login styles - uses CSS vars from app.css to match landing page */

            /* === Animated Grid (uses --color-paper as base) === */
            .login-grid-bg {
                position: fixed;
                inset: 0;
                z-index: 0;
                overflow: hidden;
                background: var(--color-paper);
                transition: background 0.5s ease;
            }

            .login-grid-bg::before {
                content: '';
                position: absolute;
                inset: -50%;
                background-image:
                    linear-gradient(var(--color-line) 1px, transparent 1px),
                    linear-gradient(90deg, var(--color-line) 1px, transparent 1px);
                background-size: 60px 60px;
                opacity: 0.4;
                animation: grid-drift 25s linear infinite;
            }

            @keyframes grid-drift {
                0%   { transform: translate(0, 0); }
                100% { transform: translate(60px, 60px); }
            }

            /* === Ambient Orbs (match mac-aurora-bg radial glow pattern) === */
            .login-orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                pointer-events: none;
                opacity: 0;
                animation: orb-fade-in 2s ease-out forwards;
            }

            .login-orb--ember {
                width: 420px; height: 420px;
                top: -10%; right: -5%;
                background: radial-gradient(circle, rgba(var(--radial-glow-2), 0.15) 0%, transparent 70%);
                animation: orb-fade-in 2s ease-out 0.3s forwards, orb-float-1 18s ease-in-out infinite 2.3s;
            }

            .login-orb--gold {
                width: 350px; height: 350px;
                bottom: -8%; left: -8%;
                background: radial-gradient(circle, rgba(var(--radial-glow-1), 0.12) 0%, transparent 70%);
                animation: orb-fade-in 2s ease-out 0.6s forwards, orb-float-2 22s ease-in-out infinite 2.6s;
            }

            .login-orb--accent {
                width: 200px; height: 200px;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                background: radial-gradient(circle, rgba(var(--radial-glow-2), 0.06) 0%, transparent 70%);
                animation-delay: 0.9s;
            }

            @keyframes orb-fade-in {
                0%   { opacity: 0; transform: scale(0.8); }
                100% { opacity: 1; transform: scale(1); }
            }

            @keyframes orb-float-1 {
                0%, 100% { transform: translate(0, 0); }
                33%      { transform: translate(30px, -20px); }
                66%      { transform: translate(-15px, 15px); }
            }

            @keyframes orb-float-2 {
                0%, 100% { transform: translate(0, 0); }
                50%      { transform: translate(-25px, -30px); }
            }

            /* === Login Card (matches ios-glass from landing page) === */
            .login-card {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 440px;
                margin: 0 1rem;
                padding: 2.5rem 2rem;

                /* ponytail: reuse exact ios-glass dark values from app.css */
                backdrop-filter: blur(25px);
                -webkit-backdrop-filter: blur(25px);
                background-color: var(--color-paper-warm);
                border: 1px solid var(--color-line);
                border-radius: 28px;
                box-shadow: 0 20px 50px -20px rgba(0,0,0,0.45);
                transition: background-color 0.5s ease, border-color 0.5s ease, box-shadow 0.5s ease;

                /* entrance animation */
                animation: card-enter 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
                transform: translateY(20px) scale(0.97);
            }

            .dark .login-card {
                background-color: rgba(255, 255, 255, 0.04);
                border-color: rgba(255, 255, 255, 0.15);
                box-shadow: 0 15px 35px -12px rgba(0, 0, 0, 0.4), 0 0 20px rgba(255, 255, 255, 0.02);
            }

            @keyframes card-enter {
                to { opacity: 1; transform: translateY(0) scale(1); }
            }

            /* === Animated top-edge glow === */
            .login-card::before {
                content: '';
                position: absolute;
                top: -1px; left: 20%; right: 20%;
                height: 2px;
                background: linear-gradient(90deg, transparent, var(--color-ember), var(--color-gold), transparent);
                border-radius: 2px;
                opacity: 0.5;
                animation: glow-sweep 4s ease-in-out infinite;
            }

            @keyframes glow-sweep {
                0%, 100% { opacity: 0.3; left: 20%; right: 20%; }
                50%      { opacity: 0.8; left: 10%; right: 10%; }
            }

            /* === Form Input Styling === */
            .login-input-group {
                position: relative;
                margin-bottom: 1.25rem;
            }

            .login-label {
                display: block;
                font-family: 'Space Mono', monospace;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: var(--color-ink-soft);
                margin-bottom: 0.5rem;
                transition: color 0.3s ease;
            }

            .login-input {
                display: block;
                width: 100%;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: var(--color-ink);
                background: rgba(0,0,0,0.03);
                border: 1px solid var(--color-line);
                border-radius: 14px;
                outline: none;
                transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .dark .login-input {
                background: rgba(255,255,255,0.04);
            }

            .login-input::placeholder {
                color: var(--color-ink-soft);
                opacity: 0.5;
            }

            .login-input:focus {
                border-color: var(--color-ember);
                box-shadow: 0 0 0 3px rgba(230,81,0,0.08), 0 4px 16px -4px rgba(230,81,0,0.12);
            }

            .dark .login-input:focus {
                background: rgba(255,255,255,0.07);
            }

            .login-input-group:focus-within .login-label {
                color: var(--color-ember);
            }

            /* === Password Toggle === */
            .login-pw-toggle {
                position: absolute;
                right: 0.75rem;
                bottom: 0.7rem;
                padding: 0.25rem;
                background: none;
                border: none;
                cursor: pointer;
                color: var(--color-ink-soft);
                transition: color 0.2s;
                line-height: 1;
            }

            .login-pw-toggle:hover {
                color: var(--color-ember);
            }

            /* === Submit Button (matches landing page CTA) === */
            .login-btn {
                width: 100%;
                padding: 0.85rem 1.5rem;
                font-size: 0.875rem;
                font-weight: 600;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: #FFFFFF;
                border: none;
                border-radius: 100px;
                cursor: pointer;
                position: relative;
                overflow: hidden;
                background: linear-gradient(135deg, var(--color-ember), var(--color-ember-dark));
                box-shadow: 0 8px 20px -4px rgba(255,107,0,0.4);
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .login-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 14px 35px -8px rgba(255,107,0,0.5);
            }

            .login-btn:active {
                transform: translateY(0);
                box-shadow: 0 4px 12px -4px rgba(255,107,0,0.3);
            }

            /* button shimmer */
            .login-btn::after {
                content: '';
                position: absolute;
                top: 0; left: -100%;
                width: 100%; height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
                animation: btn-shimmer 3s ease-in-out infinite;
            }

            @keyframes btn-shimmer {
                0%   { left: -100%; }
                50%  { left: 100%; }
                100% { left: 100%; }
            }

            /* === Checkbox === */
            .login-checkbox {
                width: 16px; height: 16px;
                accent-color: var(--color-ember);
                border-radius: 4px;
                cursor: pointer;
            }

            /* === Stagger children entrance === */
            .login-stagger > * {
                opacity: 0;
                transform: translateY(12px);
                animation: stagger-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            .login-stagger > *:nth-child(1) { animation-delay: 0.3s; }
            .login-stagger > *:nth-child(2) { animation-delay: 0.4s; }
            .login-stagger > *:nth-child(3) { animation-delay: 0.5s; }
            .login-stagger > *:nth-child(4) { animation-delay: 0.6s; }
            .login-stagger > *:nth-child(5) { animation-delay: 0.7s; }

            @keyframes stagger-in {
                to { opacity: 1; transform: translateY(0); }
            }

            /* === Particle Canvas === */
            #login-particles {
                position: fixed;
                inset: 0;
                z-index: 1;
                pointer-events: none;
            }

            /* === Security Badge === */
            .login-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                font-size: 10px;
                font-family: 'Space Mono', monospace;
                letter-spacing: 0.08em;
                color: var(--color-ink-soft);
                opacity: 0.6;
                margin-top: 1.5rem;
                padding: 0.35rem 0.75rem;
                border: 1px solid var(--color-line);
                border-radius: 100px;
                background: rgba(0,0,0,0.02);
            }

            .dark .login-badge {
                background: rgba(255,255,255,0.02);
            }

            .login-badge svg {
                width: 12px; height: 12px;
                opacity: 0.5;
            }

            /* === Theme toggle === */
            .login-theme-toggle {
                position: absolute;
                top: 1.25rem;
                right: 1.25rem;
                z-index: 20;
                padding: 0.5rem;
                border-radius: 9999px;
                border: 1px solid var(--color-line);
                background: transparent;
                color: var(--color-ink);
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .login-theme-toggle:hover {
                background: var(--color-paper-warm);
                color: var(--color-ember);
            }

            @media (min-width: 640px) {
                .login-card { padding: 3rem 2.5rem; }
            }
        </style>
    </head>
    <body class="font-body text-ink antialiased min-h-screen flex items-center justify-center overflow-hidden bg-paper transition-colors duration-500">

        <!-- Background Layer -->
        <div class="login-grid-bg">
            <div class="login-orb login-orb--ember"></div>
            <div class="login-orb login-orb--gold"></div>
            <div class="login-orb login-orb--accent"></div>
        </div>

        <!-- Particle Canvas -->
        <canvas id="login-particles"></canvas>

        <!-- Login Card -->
        <div class="login-card">
            <!-- Theme Toggle -->
            <button @click="darkMode = !darkMode" class="login-theme-toggle" aria-label="Toggle Theme">
                <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
                <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>

            <!-- Header -->
            <div class="flex flex-col items-center mb-8" style="animation: stagger-in 0.5s cubic-bezier(0.16,1,0.3,1) 0.15s forwards; opacity:0; transform:translateY(12px);">
                <a href="/" class="group">
                    <img src="{{ asset('logo.png') }}" alt="Logo PARTI" class="h-16 w-auto drop-shadow-md group-hover:scale-110 transition-transform duration-500">
                </a>
                <h1 class="font-display font-bold text-[22px] tracking-wide text-ink mt-4 uppercase">PARTI {{ session('active_year', config('parti.active_year', 2026)) }}</h1>
                <span class="font-mono text-[9px] tracking-[0.2em] text-ember font-bold uppercase mt-1.5">Vanguard of Tech Admin</span>
            </div>

            <!-- Form Slot -->
            <div class="login-stagger">
                {{ $slot }}
            </div>

            <!-- Security Badge -->
            <div class="flex justify-center">
                <div class="login-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Secure Admin Access
                </div>
            </div>
        </div>

        <!-- Particle Script -->
        <script>
            // ponytail: minimal particle system - reads computed CSS for theme-aware colors
            (function() {
                const canvas = document.getElementById('login-particles');
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                let w, h, particles = [];
                const COUNT = 40;

                function getParticleColor() {
                    // ponytail: use ember color from CSS vars for theme consistency
                    const style = getComputedStyle(document.documentElement);
                    return style.getPropertyValue('--color-ember').trim() || '#FF851B';
                }

                function hexToRgb(hex) {
                    const r = parseInt(hex.slice(1, 3), 16);
                    const g = parseInt(hex.slice(3, 5), 16);
                    const b = parseInt(hex.slice(5, 7), 16);
                    return { r, g, b };
                }

                function resize() {
                    w = canvas.width = window.innerWidth;
                    h = canvas.height = window.innerHeight;
                }

                function createParticle() {
                    return {
                        x: Math.random() * w,
                        y: Math.random() * h,
                        r: Math.random() * 1.5 + 0.5,
                        vx: (Math.random() - 0.5) * 0.3,
                        vy: (Math.random() - 0.5) * 0.3,
                        alpha: Math.random() * 0.3 + 0.1,
                    };
                }

                function init() {
                    resize();
                    particles = [];
                    for (let i = 0; i < COUNT; i++) particles.push(createParticle());
                }

                function draw() {
                    ctx.clearRect(0, 0, w, h);
                    const color = getParticleColor();
                    const rgb = hexToRgb(color);

                    // Draw connections
                    for (let i = 0; i < particles.length; i++) {
                        for (let j = i + 1; j < particles.length; j++) {
                            const dx = particles[i].x - particles[j].x;
                            const dy = particles[i].y - particles[j].y;
                            const dist = Math.sqrt(dx * dx + dy * dy);
                            if (dist < 150) {
                                ctx.beginPath();
                                ctx.moveTo(particles[i].x, particles[i].y);
                                ctx.lineTo(particles[j].x, particles[j].y);
                                ctx.strokeStyle = `rgba(${rgb.r},${rgb.g},${rgb.b},${0.06 * (1 - dist / 150)})`;
                                ctx.lineWidth = 0.5;
                                ctx.stroke();
                            }
                        }
                    }

                    // Draw particles
                    for (const p of particles) {
                        p.x += p.vx;
                        p.y += p.vy;
                        if (p.x < 0 || p.x > w) p.vx *= -1;
                        if (p.y < 0 || p.y > h) p.vy *= -1;

                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                        ctx.fillStyle = `rgba(${rgb.r},${rgb.g},${rgb.b},${p.alpha})`;
                        ctx.fill();
                    }

                    requestAnimationFrame(draw);
                }

                window.addEventListener('resize', resize);
                init();
                draw();
            })();
        </script>
    </body>
</html>
