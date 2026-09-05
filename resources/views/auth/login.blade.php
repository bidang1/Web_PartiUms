<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        <!-- Email Address -->
        <div class="login-input-group">
            <label for="email" class="login-label">Email Panitia</label>
            <input id="email" class="login-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <!-- Password -->
        <div class="login-input-group" style="position:relative;">
            <label for="password" class="login-label">Kata Sandi</label>
            <input id="password" class="login-input" style="padding-right:2.75rem;"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />
            <button type="button" class="login-pw-toggle" onclick="togglePassword()" aria-label="Toggle password visibility" tabindex="-1">
                <svg id="pw-eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                <svg id="pw-eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center text-xs mb-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer" style="color:rgba(255,255,255,0.4);">
                <input id="remember_me" type="checkbox" class="login-checkbox" name="remember">
                <span class="ms-2 font-medium">Ingat Saya</span>
            </label>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit" class="login-btn" id="login-submit-btn">
                <span id="login-btn-text">Masuk ke Panel Admin →</span>
                <span id="login-btn-loading" style="display:none;" class="inline-flex items-center gap-2">
                    <svg class="animate-spin" style="width:16px;height:16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity:0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Memproses...
                </span>
            </button>
        </div>
    </form>

    <script>
        // ponytail: vanilla JS - no Alpine needed for 2 interactions
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOff = document.getElementById('pw-eye-off');
            const eyeOn = document.getElementById('pw-eye-on');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            eyeOff.style.display = isPassword ? 'none' : 'block';
            eyeOn.style.display = isPassword ? 'block' : 'none';
        }

        document.getElementById('login-form').addEventListener('submit', function() {
            const btn = document.getElementById('login-submit-btn');
            const btnText = document.getElementById('login-btn-text');
            const btnLoading = document.getElementById('login-btn-loading');
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.style.cursor = 'wait';
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
        });
    </script>
</x-guest-layout>
