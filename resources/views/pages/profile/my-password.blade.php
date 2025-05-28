<x-app-layout>
    <x-slot name="page_title"> Profil Saya </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Profil Saya'</b> berfungsi untuk memperbarui data profil pada akun." />
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Profil Saya.', 'url' => route('profile.my-profile')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-5">
            <div class="col-md-12">
                @php
                    $role = strtolower(auth()->user()->roles->first()->name);
                    $tabs = [
                        ['label' => 'Akun', 'permission' => 'my_account', 'route_active' => 'profile.my-account', 'route' => route('profile.my-account')],
                    ];
                    if ($role === 'psychologist') {
                        $tabs[] = ['label' => 'Profil', 'permission' => 'my_profile_psychologist', 'route_active' => 'profile.my-profile-psychologist', 'route' => route('profile.my-profile-psychologist')];
                    }
                    if ($role === 'patient') {
                        $tabs[] = ['label' => 'Profil', 'permission' => 'my_profile', 'route_active' => 'profile.my-profile', 'route' => route('profile.my-profile')];
                    }
                    $tabs[] = ['label' => 'Kata Sandi', 'permission' => 'my_password', 'route_active' => 'profile.my-password', 'route' => route('profile.my-password')];
                @endphp
                <x-molecules.tabulation :tabs="$tabs" />
                <hr>
            </div>
            <div class="col-md-12">
                <form class="row" method="POST" action="{{ route('profile.my-password.update') }}">
                    @method('PUT')
                    @csrf
                    <div class="col-md-12">
                        <label for="new_password" class="form-label fw-bold">Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control form-control-solid-bordered @error('new_password') border border-2 border-danger @enderror"
                                id="new_password"
                                name="new_password"
                                placeholder="Masukkan Kata Sandi Baru"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <i class="material-icons-two-tone mx-auto">visibility</i>
                            </button>
                        </div>
                        @error('new_password')
                            <div id="passwordHelpBlock" class="form-text text-danger">
                                {{ $message }}.
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="new_password_confirm" class="form-label fw-bold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control form-control-solid-bordered @error('new_password_confirm') border border-2 border-danger @enderror"
                                id="new_password_confirm"
                                name="new_password_confirm"
                                placeholder="Masukkan Konfirmasi Kata Sandi Baru"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPasswordConfirm">
                                <i class="material-icons-two-tone mx-auto">visibility</i>
                            </button>
                        </div>
                        @error('new_password_confirm')
                            <div id="passwordHelpBlock" class="form-text text-danger">
                                {{ $message }}.
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <label for="password" class="form-label fw-bold">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control form-control-solid-bordered @error('password') border border-2 border-danger @enderror"
                                id="password"
                                name="password"
                                placeholder="Masukkan Kata Sandi Saat Ini"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="material-icons-two-tone mx-auto">visibility</i>
                            </button>
                        </div>
                        @error('password')
                            <div id="passwordHelpBlock" class="form-text text-danger">
                                {{ $message }}.
                            </div>
                        @enderror
                    </div>
                    @can('my_password')
                        <div class="col-md-12 text-end mt-4">
                            <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>

    @push('js-internal')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const passwordIcon = toggleBtn.querySelector('i');

            toggleBtn.addEventListener('click', function () {
                if (!passwordInput) return;

                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                passwordIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
            });

            const toggleNewBtn = document.getElementById('toggleNewPassword');
            const newPasswordInput = document.getElementById('new_password');
            const newPasswordIcon = toggleNewBtn.querySelector('i');

            toggleNewBtn.addEventListener('click', function () {
                if (!newPasswordInput) return;

                const isNewPassword = newPasswordInput.type === 'password';
                newPasswordInput.type = isNewPassword ? 'text' : 'password';
                newPasswordIcon.textContent = isNewPassword ? 'visibility_off' : 'visibility';
            });

            const toggleNewConfirmBtn = document.getElementById('toggleNewPasswordConfirm');
            const newPasswordConfirmInput = document.getElementById('new_password_confirm');
            const newPasswordConfirmIcon = toggleNewConfirmBtn.querySelector('i');

            toggleNewConfirmBtn.addEventListener('click', function () {
                if (!newPasswordConfirmInput) return;

                const isNewPasswordConfirm = newPasswordConfirmInput.type === 'password';
                newPasswordConfirmInput.type = isNewPasswordConfirm ? 'text' : 'password';
                newPasswordConfirmIcon.textContent = isNewPasswordConfirm ? 'visibility_off' : 'visibility';
            });
        });
    </script>
    @endpush
</x-app-layout>