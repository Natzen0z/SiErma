<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Risk Management System')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Google Fonts — Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- ExcelJS & FileSaver for Export functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <style>
        /* ============================================ */
        /*  DESIGN SYSTEM — SI ERMa Premium            */
        /* ============================================ */
        
        :root {
            --sidebar-w: 18rem;       /* 288px */
            --accent-primary: #0d9488;
            --accent-admin: #d97706;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        /* Smooth scrolling */
        html { scroll-behavior: smooth; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 100px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Alpine cloak */
        [x-cloak] { display: none !important; }

        /* ---- Animations ---- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50%      { opacity: 0.7; }
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out both; }
        .animate-fade-in    { animation: fadeIn 0.4s ease-out both; }
        .animate-slide-left { animation: slideInLeft 0.4s ease-out both; }
        .animate-float      { animation: float 3s ease-in-out infinite; }
        
        /* Stagger children */
        .stagger-children > *:nth-child(1) { animation-delay: 0.05s; }
        .stagger-children > *:nth-child(2) { animation-delay: 0.10s; }
        .stagger-children > *:nth-child(3) { animation-delay: 0.15s; }
        .stagger-children > *:nth-child(4) { animation-delay: 0.20s; }
        .stagger-children > *:nth-child(5) { animation-delay: 0.25s; }
        .stagger-children > *:nth-child(6) { animation-delay: 0.30s; }

        /* Sidebar gradient */
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
        .sidebar-gradient-admin {
            background: linear-gradient(180deg, #0f172a 0%, #1c1917 100%);
        }

        /* Glass card */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
        }

        /* Premium card hover */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px -12px rgba(0, 0, 0, 0.12);
        }

        /* Shimmer button */
        .btn-shimmer {
            position: relative;
            overflow: hidden;
        }
        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s;
        }
        .btn-shimmer:hover::after {
            left: 100%;
        }

        /* Refined nav link */
        .nav-link {
            position: relative;
            transition: all 0.2s ease;
        }
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%) scaleY(0);
            width: 3px; height: 60%;
            border-radius: 0 4px 4px 0;
            background: currentColor;
            transition: transform 0.2s ease;
        }
        .nav-link:hover::before,
        .nav-link.active::before {
            transform: translateY(-50%) scaleY(1);
        }

        /* Table glow row */
        .table-row-hover {
            transition: all 0.15s ease;
        }
        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(13, 148, 136, 0.03), transparent) !important;
        }

        /* Gradient text */
        .text-gradient {
            background: linear-gradient(135deg, #0d9488, #0891b2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .text-gradient-admin {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Input focus ring */
        .input-modern {
            transition: all 0.2s ease;
            border: 1.5px solid #e2e8f0;
        }
        .input-modern:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
            outline: none;
        }

        /* Stat card accent line */
        .stat-accent {
            position: relative;
        }
        .stat-accent::before {
            content: '';
            position: absolute;
            left: 0; top: 12px; bottom: 12px;
            width: 3px;
            border-radius: 0 4px 4px 0;
        }
        .stat-accent-blue::before  { background: linear-gradient(180deg, #3b82f6, #2563eb); }
        .stat-accent-red::before   { background: linear-gradient(180deg, #ef4444, #dc2626); }
        .stat-accent-green::before { background: linear-gradient(180deg, #10b981, #059669); }
        .stat-accent-amber::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
        .stat-accent-teal::before  { background: linear-gradient(180deg, #14b8a6, #0d9488); }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50/80 font-sans text-slate-900 antialiased">
    @yield('content')
    
    @stack('scripts')
</body>
</html>
