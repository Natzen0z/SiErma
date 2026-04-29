<!-- Admin Sidebar Component -->
<div class="w-72 sidebar-gradient-admin text-white h-screen fixed left-0 top-0 p-5 flex flex-col shadow-2xl shadow-black/30 z-50 hidden md:flex border-r border-white/5">
    <!-- Brand -->
    <div class="mb-8 flex items-center space-x-3 px-2">
        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
            <i data-lucide="{{ Auth::user()->isAuditor() ? 'eye' : 'shield-check' }}" class="w-5 h-5 text-white"></i>
        </div>
        <div>
            <h1 class="text-lg font-extrabold tracking-tight">SI ERMa {{ Auth::user()->isAuditor() ? 'Auditor' : 'Admin' }}</h1>
            <p class="text-[11px] text-slate-400 font-medium">RSUD dr. Murjani</p>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 space-y-1">
        @php $currentRoute = Route::currentRouteName(); @endphp
        
        <a href="{{ route('admin.dashboard') }}" class="nav-link w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $currentRoute === 'admin.dashboard' ? 'bg-gradient-to-r from-amber-600 to-amber-500 text-white shadow-lg shadow-amber-900/40' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
            <i data-lucide="layout-dashboard" class="w-[18px] h-[18px]"></i>
            <span class="font-medium text-sm">Dashboard</span>
        </a>
        @if(!Auth::user()->isAuditor())
        <a href="{{ route('admin.users') }}" class="nav-link w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $currentRoute === 'admin.users' ? 'bg-gradient-to-r from-amber-600 to-amber-500 text-white shadow-lg shadow-amber-900/40' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
            <i data-lucide="users" class="w-[18px] h-[18px]"></i>
            <span class="font-medium text-sm">Kelola User</span>
        </a>
        @endif
        <a href="{{ route('admin.risks') }}" class="nav-link w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $currentRoute === 'admin.risks' ? 'bg-gradient-to-r from-amber-600 to-amber-500 text-white shadow-lg shadow-amber-900/40' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
            <i data-lucide="file-text" class="w-[18px] h-[18px]"></i>
            <span class="font-medium text-sm">Semua Risiko</span>
        </a>
        <a href="{{ route('admin.annual_recap') }}" class="nav-link w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $currentRoute === 'admin.annual_recap' ? 'bg-gradient-to-r from-amber-600 to-amber-500 text-white shadow-lg shadow-amber-900/40' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
            <i data-lucide="bar-chart-3" class="w-[18px] h-[18px]"></i>
            <span class="font-medium text-sm">Rekap Tahunan</span>
        </a>
        
        @if(!Auth::user()->isAuditor())
        <a href="{{ route('admin.units') }}" class="nav-link w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $currentRoute === 'admin.units' ? 'bg-gradient-to-r from-amber-600 to-amber-500 text-white shadow-lg shadow-amber-900/40' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
            <i data-lucide="building" class="w-[18px] h-[18px]"></i>
            <span class="font-medium text-sm">Kelola Unit</span>
        </a>
        <a href="{{ route('admin.categories') }}" class="nav-link w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $currentRoute === 'admin.categories' ? 'bg-gradient-to-r from-amber-600 to-amber-500 text-white shadow-lg shadow-amber-900/40' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
            <i data-lucide="tag" class="w-[18px] h-[18px]"></i>
            <span class="font-medium text-sm">Kelola Kategori</span>
        </a>

        <div class="pt-2">
            <a href="{{ route('risk.index', ['notify' => 1]) }}" class="nav-link w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-white/5 hover:text-slate-200 transition-all duration-200">
                <i data-lucide="megaphone" class="w-[18px] h-[18px] text-amber-400"></i>
                <span class="font-medium text-sm">Notify Users</span>
            </a>
        </div>
        @endif
    </nav>

    <!-- Bottom Section -->
    <div class="mt-auto space-y-3">
        <a href="{{ route('risk.index') }}" class="w-full bg-teal-600 hover:bg-teal-500 text-white py-2.5 px-4 rounded-xl flex items-center justify-center font-semibold text-sm shadow-lg transition-all btn-shimmer">
            <i data-lucide="activity" class="w-[18px] h-[18px] mr-2"></i>
            <span>Risk Dashboard</span>
        </a>
        
        <!-- User Info -->
        <div class="px-4 py-3 bg-white/5 rounded-xl border border-white/5">
            <div class="flex items-center mb-1.5 text-amber-400">
               <i data-lucide="user-circle" class="w-3.5 h-3.5 mr-1.5"></i>
               <p class="text-[10px] font-bold uppercase tracking-wider">{{ Auth::user()->isAuditor() ? 'Auditor' : 'Admin' }}</p>
            </div>
            <p class="font-bold text-sm text-white truncate">{{ Auth::user()->name }}</p>
            <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-red-600/80 hover:bg-red-600 text-white py-2.5 px-4 rounded-xl flex items-center justify-center font-semibold text-sm shadow-lg transition-all">
                <i data-lucide="log-out" class="w-[18px] h-[18px] mr-2"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
