<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SPK PKH Desa Thaibah Raya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 text-slate-100">

    <div class="w-full max-w-md bg-white text-slate-800 rounded-2xl shadow-2xl p-8 border border-slate-100" x-data="{
        fill(u, p) {
            document.getElementById('username').value = u;
            document.getElementById('password').value = p;
        }
    }">
        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-emerald-500/30 mb-3">
                PKH
            </div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">SPK PKH - SAW</h1>
            <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mt-1">Kantor Desa Thaibah Raya</p>
            <p class="text-xs text-slate-500">Kec. Tatah Makmur, Kab. Banjar, Kalsel</p>
        </div>

        @if(session('success'))
            <div class="p-3 mb-4 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3 mb-4 rounded-lg bg-rose-50 text-rose-700 text-xs font-medium border border-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                       placeholder="Masukkan username"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" id="password" required
                       placeholder="Masukkan password"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span>Ingat saya</span>
                </label>
                <span class="text-slate-400">Metode SAW</span>
            </div>

            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/30 transition">
                Masuk ke Sistem
            </button>
        </form>

        <!-- Quick Demo Credentials Box -->
        <div class="mt-6 pt-5 border-t border-slate-100">
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center mb-2">Pilihan Akun Demo:</p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="fill('admin', 'admin123')"
                        class="p-2.5 text-left rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition">
                    <div class="text-xs font-bold text-slate-800">Admin SPK</div>
                    <div class="text-[11px] text-slate-500">admin / admin123</div>
                </button>
                <button type="button" @click="fill('kades', 'kades123')"
                        class="p-2.5 text-left rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition">
                    <div class="text-xs font-bold text-slate-800">Kepala Desa</div>
                    <div class="text-[11px] text-slate-500">kades / kades123</div>
                </button>
            </div>
        </div>
    </div>

</body>
</html>
