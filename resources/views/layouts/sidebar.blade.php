<div class="px-5 py-4 border-b border-white/10 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center shadow text-white font-bold text-sm">
            PKH
        </div>
        <div>
            <h1 class="text-sm font-bold text-white leading-tight">SPK SAW PKH</h1>
            <p class="text-[11px] text-emerald-400 font-medium">Desa Thaibah Raya</p>
        </div>
    </div>
    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
        ✕
    </button>
</div>

<div class="px-4 py-1.5 text-[10px] uppercase font-bold tracking-wider text-emerald-400/80">
    Kec. Tatah Makmur, Banjar
</div>

<nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto text-sm">
    <a href="{{ route('dashboard') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>📊</span> <span>Dashboard</span>
    </a>

    <a href="{{ route('pengguna.index') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('pengguna.*') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>👥</span> <span>Manajemen Pengguna</span>
    </a>

    <a href="{{ route('keluarga.index') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('keluarga.*') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>👨‍👩‍👧‍👦</span> <span>Data Kepala Keluarga</span>
    </a>

    <a href="{{ route('kriteria.index') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('kriteria.*') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>⚖️</span> <span>Kriteria & Sub-Kriteria</span>
    </a>

    <a href="{{ route('periode.index') }}" 
       class="flex items-center gap-2.5 px-3 py-1.5 text-xs rounded-lg transition {{ request()->routeIs('periode.*') ? 'bg-emerald-700/60 text-white font-semibold' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
        <span>📅</span> <span>Master Periode</span>
    </a>

    <div class="pt-2 pb-1 px-3 text-[10px] font-bold tracking-wider text-emerald-400 uppercase">
        Metode SAW
    </div>

    <a href="{{ route('penilaian.index') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('penilaian.*') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>📝</span> <span>Penilaian Calon Penerima</span>
    </a>

    <a href="{{ route('spk.normalisasi') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('spk.normalisasi') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>🔢</span> <span>Menu Normalisasi</span>
    </a>

    <a href="{{ route('spk.perhitungan') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('spk.perhitungan') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>🧮</span> <span>Menu Perhitungan SAW</span>
    </a>

    <a href="{{ route('spk.perangkingan') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('spk.perangkingan') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>🏆</span> <span>Menu Perankingan</span>
    </a>

    <div class="pt-2 pb-1 px-3 text-[10px] font-bold tracking-wider text-emerald-400 uppercase">
        Laporan
    </div>

    <a href="{{ route('laporan.index') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('laporan.*') ? 'bg-emerald-600 text-white font-semibold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
        <span>📄</span> <span>Laporan & Cetak PDF</span>
    </a>
</nav>

<div class="p-3 border-t border-white/10 bg-black/20">
    <div class="flex items-center justify-between">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->nama ?? 'User' }}</p>
            <span class="inline-block px-1.5 py-0.5 text-[9px] rounded bg-emerald-500/20 text-emerald-300 font-medium">
                {{ Auth::user()->role === 'kepala_desa' ? 'Pambakal' : 'Admin' }}
            </span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" title="Keluar" class="px-2 py-1 text-xs text-rose-300 hover:text-white hover:bg-rose-600/30 rounded transition">
                Logout
            </button>
        </form>
    </div>
</div>
