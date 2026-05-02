<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Risk Management System RSUD dr. Murjani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] } } }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.4; }
            50%      { transform: translateY(-20px) rotate(5deg); opacity: 0.7; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse-ring {
            0%   { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        .animated-bg {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #134e4a, #0f172a, #1e1b4b);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            animation: float linear infinite;
        }
        .input-glow:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.12);
        }
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        .btn-shine:hover::after {
            left: 100%;
        }
        .logo-ring {
            position: relative;
        }
        .logo-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 20px;
            background: rgba(13, 148, 136, 0.3);
            animation: pulse-ring 2s ease-out infinite;
        }
    </style>
</head>
<body class="animated-bg min-h-screen flex items-center justify-center p-4 font-sans antialiased relative overflow-hidden">
    <!-- Floating Particles -->
    <div class="particle" style="width: 300px; height: 300px; top: -100px; left: -100px; animation-duration: 20s;"></div>
    <div class="particle" style="width: 200px; height: 200px; bottom: -50px; right: -50px; animation-duration: 25s; animation-delay: 2s;"></div>
    <div class="particle" style="width: 150px; height: 150px; top: 40%; left: 60%; animation-duration: 18s; animation-delay: 4s;"></div>
    <div class="particle" style="width: 100px; height: 100px; top: 20%; right: 20%; animation-duration: 22s; animation-delay: 1s;"></div>
    <div class="particle" style="width: 80px; height: 80px; bottom: 30%; left: 15%; animation-duration: 16s; animation-delay: 3s;"></div>

    <div class="w-full max-w-[420px] relative z-10" style="animation: fadeInUp 0.6s ease-out both;">
        <!-- Logo & Title -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl mb-5 shadow-2xl shadow-teal-500/25 logo-ring">
                <i data-lucide="activity" class="w-10 h-10 text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-white mb-1 tracking-tight">SI ERMa</h1>
            <p class="text-slate-400 text-sm font-medium">Sistem Informasi Enterprise Risk Management</p>
            <p class="text-slate-500 text-xs mt-1">RSUD dr. Murjani Sampit</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-3xl shadow-2xl shadow-black/20 p-8" style="animation: fadeInUp 0.6s ease-out 0.15s both;">
            <h2 class="text-xl font-bold text-slate-800 mb-1 text-center">Selamat Datang</h2>
            <p class="text-slate-400 text-center mb-8 text-sm">Masuk ke akun Anda untuk melanjutkan</p>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50/80 border border-red-200/60 rounded-2xl backdrop-blur-sm">
                    <div class="flex items-center text-red-600">
                        <i data-lucide="alert-circle" class="w-5 h-5 mr-2 flex-shrink-0"></i>
                        <span class="font-medium text-sm">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50/80 border border-emerald-200/60 rounded-2xl backdrop-blur-sm">
                    <div class="flex items-center text-emerald-600">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-2 flex-shrink-0"></i>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-[18px] h-[18px] text-slate-400 group-focus-within:text-teal-500 transition-colors duration-200"></i>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            value="{{ old('email') }}"
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50/80 border-[1.5px] border-slate-200 rounded-xl focus:outline-none transition-all duration-200 text-sm font-medium input-glow"
                            placeholder="nama@rsudmurjani.id"
                            required
                            autofocus
                        >
                    </div>
                </div>
 
                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-[18px] h-[18px] text-slate-400 group-focus-within:text-teal-500 transition-colors duration-200"></i>
                        </div>
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            id="password"
                            class="w-full pl-12 pr-12 py-3.5 bg-slate-50/80 border-[1.5px] border-slate-200 rounded-xl focus:outline-none transition-all duration-200 text-sm font-medium input-glow"
                            placeholder="Masukkan password"
                            required
                        >
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-teal-600 transition-colors"
                        >
                            <span x-show="!showPassword"><i data-lucide="eye" class="w-[18px] h-[18px]"></i></span>
                            <span x-show="showPassword" x-cloak><i data-lucide="eye-off" class="w-[18px] h-[18px]"></i></span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500 transition">
                        <span class="ml-2.5 text-sm text-slate-500 group-hover:text-slate-700 transition-colors">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-semibold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-lg shadow-teal-600/25 hover:shadow-xl hover:shadow-teal-600/30 flex items-center justify-center btn-shine active:scale-[0.98]"
                >
                    <i data-lucide="log-in" class="w-[18px] h-[18px] mr-2"></i>
                    Masuk
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-slate-500/60 text-xs mt-8 font-medium" style="animation: fadeInUp 0.6s ease-out 0.3s both;">
            © {{ date('Y') }} RSUD dr. Murjani Sampit — SI ERMa v2.0
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
