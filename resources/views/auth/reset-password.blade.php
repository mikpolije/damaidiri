<x-auth-layout>
    <form method="POST" class="container-fluid" action="{{ route('password.update') }}">
        @csrf
        <div class="logo d-flex align-items-center">
            <a style="background-size: contain;" href="{{ route('home-landing') }}"></a>
            <p style="padding-left: 10px;" class="pt-1 fw-bold fs-4">| Solusi Untuk Kesehatan Mental.</p>
        </div>
        <p class="auth-description">
            Silahkan masukkan <span class="fw-bold">'Kata Sandi'</span> dan <span class="fw-bold">'Kata Sandi Konfirmasi'</span> untuk melakukan reset kata sandi.
        </p>

        <div class="auth-credentials m-b-xxl">
            <x-atoms.pure-input id="token" label="Token" required="true" type="hidden" name="token" value="{{ $request->token }}" />
            <x-atoms.pure-input id="email" label="Email" required="true" type="hidden" name="email" value="{{ $request->email }}" />
            <br />
            <x-atoms.form-input id="password" label="Kata Sandi" required="true" type="password" name="password" />
            <br />
            <x-atoms.form-input id="password_confirmation" label="Kata Sandi Konfirmasi" required="true" type="password" name="password_confirmation" />
        </div>

        <div class="auth-submit d-flex justify-content-between align-items-center">
            <x-atoms.form-button type="submit" class="btn-primary py-2" icon="login" label="Reset Kata Sandi" />
        </div>
        <div class="divider"></div>
    </form>
</x-auth-layout>