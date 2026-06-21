<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SYMPHONY SIMRS v2.0</title>
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-warm-bg flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Logo Area --}}
        <div class="text-center mb-8 animate-fade-in">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="{{ asset('esa-unggul-logo.png') }}" alt="Logo Esa Unggul" class="h-30 w-auto object-contain">
            </div>
            <h4 class="text-2xl font-bold text-text-primary tracking-tight">SYMPHONY SIMRS</h4>
            <!-- <p class="text-sm text-text-muted mt-1">Fasyankes Academic Simulation Engine v2.0</p> -->
            <p class="text-xs text-text-muted mt-0.5">Universitas Esa Unggul</p>
        </div>

        {{-- Login Card --}}
        <div class="card p-8 animate-fade-in" style="animation-delay: 0.1s">
            <h2 class="text-lg font-semibold text-text-primary mb-1">Selamat Datang</h2>
            <p class="text-sm text-text-muted mb-6">Masuk ke akun Anda untuk melanjutkan</p>

            @if($errors->any())
                <div class="alert alert-error mb-6">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-5">
                @csrf
                <div>
                    <label for="username" class="form-label">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                    >
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-full py-3 text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Masuk
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-text-muted mt-6">
            &copy; {{ date('Y') }} SYMPHONY SIMRS &mdash; Built for Academic Simulation
        </p>
    </div>
</body>
</html>
