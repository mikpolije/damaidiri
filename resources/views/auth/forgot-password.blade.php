<x-auth-layout>
    <form method="POST" class="container-fluid">
        @csrf
        <div class="logo d-flex align-items-center">
            <a style="background-size: contain;" href="{{ route('home-landing') }}"></a>
            <p style="padding-left: 10px;" class="pt-1 fw-bold fs-4">| Solusi Untuk Kesehatan Mental.</p>
        </div>
        <p class="auth-description">
            Silahkan masukkan <span class="fw-bold">'Email'</span> dan Periksa <span class="fw-bold">'Email'</span> anda untuk melakukan reset kata sandi.
        </p>

        <div class="auth-credentials m-b-xxl">
            <x-atoms.form-input id="email" label="Email" required="true" type="email" name="email" />
        </div>

        <div class="auth-submit d-flex justify-content-between align-items-center">
            <x-atoms.form-button type="submit" class="btn-primary py-2" icon="login" label="Reset Kata Sandi" />
        </div>
        <div class="divider"></div>
    </form>
</x-auth-layout>