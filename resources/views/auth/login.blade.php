<x-auth-layout>
    <form method="POST" class="container-fluid">
        @csrf
        <div class="logo d-flex align-items-center">
            <a style="background-size: contain; height: 110px;" href="{{ route('home-landing') }}"></a>
            <p style="padding-left: 10px;" class="pt-1 fw-bold fs-4">| Solusi Untuk Kesehatan Mental.</p>
        </div>
        <p class="auth-description">
            Silahkan masukkan <span class="fw-bold">'Email'</span> dan <span class="fw-bold">'Kata Sandi'</span> untuk masuk ke akun anda.
        </p>
        <div class="auth-credentials m-b-xxl">
            <x-atoms.form-input id="email" label="Email" required="true" type="email" name="email" />
            <br />
            <x-atoms.form-input id="password" label="Kata Sandi" required="true" type="password" name="password" />
        </div>

        <div class="auth-submit d-flex justify-content-between align-items-center">
            <div class="auth-alts">
                <a href="{{ route('auth.google.redirect')}}" class="auth-alts-google"></a>
                {{-- <a href="{{ route('maintenance') }}" class="auth-alts-facebook"></a>
                <a href="{{ route('maintenance') }}" class="auth-alts-twitter"></a> --}}
            </div>
            <x-atoms.form-button type="submit" class="btn-primary py-2" icon="login" label="Masuk" />
        </div>
        <div class="divider"></div>
        <a href="{{ route('password.request') }}" class="auth -forgot-password float-end">Lupa Kata Sandi?</a>
    </form>
</x-auth-layout>