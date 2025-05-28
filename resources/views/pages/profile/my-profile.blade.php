<x-app-layout>
    @push('css-internal')
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    @endpush
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
            @if (check_filled_profile_patient(auth()->user()->id))
                <div class="col-md-12">
                    <form class="row align-items-center g-3" method="POST" action="{{ route('profile.my-profile.update') }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="col-md-2 text-center">
                            <img src="{{ asset( auth()->user()->profile->photo ?? 'assets/images/avatars/avatar.png') }}" class="w-100 border rounded p-3" alt="avatar" id="preview">
                        </div>
                        <div class="col-md-10">
                            <x-atoms.form-input id="photo" label="Foto" required="true" type="file" name="photo" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="nin" label="Nomor Induk Kependudukan (NIK)" required="true" type="text" name="nin" value="{{ auth()->user()->profile->nin ?? '' }}" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-select-option id="gender" name="gender" label="Jenis Kelamin" :options="
                                [
                                    ['value' => 'Laki-laki', 'label' => 'Laki-laki', 'config' => old('gender', auth()->user()->profile->gender ?? '') == 'Laki-laki' ? 'selected' : ''],
                                    ['value' => 'Perempuan', 'label' => 'Perempuan', 'config' => old('gender', auth()->user()->profile->gender ?? '') == 'Perempuan' ? 'selected' : ''],
                                    ['value' => 'Tidak Ingin Menyebutkan', 'label' => 'Tidak Ingin Menyebutkan', 'config' => old('gender', auth()->user()->profile->gender ?? '') == 'Tidak Ingin Menyebutkan' ? 'selected' : ''],
                                    ['value' => 'Tidak Diketahui', 'label' => 'Tidak Diketahui', 'config' => old('gender', auth()->user()->profile->gender ?? '') == 'Tidak Diketahui' ? 'selected' : ''],
                                    ['value' => 'Tulis Sendiri', 'label' => 'Tulis Sendiri', 'config' => old('gender', auth()->user()->profile->gender ?? '') == 'Tulis Sendiri' ? 'selected' : ''],
                                ]
                            " />
                        </div>
                        <div class="col-md-6" id="form_gender_other">
                            <x-atoms.form-input id="gender_other" label="Jenis Kelamin (Lainnya)" type="text" name="gender_other" value="{{ auth()->user()->profile->gender_other ?? '' }}" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="place_of_birth" label="Tempat Lahir" required="true" type="text" name="place_of_birth" value="{{ auth()->user()->profile->place_of_birth ?? '' }}" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="date_of_birth" label="Tanggal Lahir" required="true" type="date" name="date_of_birth" value="{{ auth()->user()->profile->date_of_birth ?? '' }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="phone_number">Nomor Telepon <span class="text-danger">*</span> :</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') border border-2 border-danger @enderror" placeholder="Masukkan Nomor Telepon" aria-label="Nomor Telepon" aria-describedby="phone_number" value="{{ auth()->user()->profile->phone_number ?? '' }}">
                            </div>
                            @error('phone_number')
                                <div id="passwordHelpBlock" class="form-text text-danger">
                                    {{ $message }}.
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="regency">Kabupaten / Kota <span class="text-danger">*</span> :</label>
                            <select class="js-states form-control select2 @error('regency') border border-2 border-danger @enderror" tabindex="-1" style="display: none; width: 100%" name="regency">
                                <option value="" disabled selected>Pilih Kabupaten / Kota</option>
                                @foreach ($data_regency as $item)
                                    <option value="{{ $item->id }}" {{ old('regency', auth()->user()->profile->regency_id) == $item->id ? 'selected' : '' }}>({{ $item->province->name }}) - {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('regency')
                                <div id="passwordHelpBlock" class="form-text text-danger">
                                    {{ $message }}.
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="address" label="Alamat Lengkap" required="true" type="text" name="address" value="{{ auth()->user()->profile->address ?? '' }}" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="job" label="Pekerjaan" required="true" type="text" name="job" value="{{ auth()->user()->profile->job ?? '' }}" />
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        {{-- <div class="col-md-12">
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
                        </div> --}}
                        @can('my_profile')
                            <div class="col-md-12 text-end mt-4">
                                <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                            </div>
                        @endcan
                    </form>
                </div>
            @else
                <div class="col-md-12">
                    <form class="row g-3 align-items-center" method="POST" action="{{ route('profile.my-profile.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-2 text-center">
                            <img src="{{ asset('assets/images/avatars/avatar.png') }}" class="w-100 border rounded p-3" alt="avatar" id="preview">
                        </div>
                        <div class="col-md-10">
                            <x-atoms.form-input id="photo" label="Foto" required="true" type="file" name="photo" />
                        </div>

                        <div class="col-md-6">
                            <x-atoms.form-input id="nin" label="Nomor Induk Kependudukan (NIK)" required="true" type="text" name="nin" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-select-option id="gender" name="gender" label="Jenis Kelamin" :options="
                                [
                                    ['value' => 'Laki-laki', 'label' => 'Laki-laki', 'config' => old('gender') == 'Laki-laki' ? 'selected' : ''],
                                    ['value' => 'Perempuan', 'label' => 'Perempuan', 'config' => old('gender') == 'Perempuan' ? 'selected' : ''],
                                    ['value' => 'Tidak Ingin Menyebutkan', 'label' => 'Tidak Ingin Menyebutkan', 'config' => old('gender') == 'Tidak Ingin Menyebutkan' ? 'selected' : ''],
                                    ['value' => 'Tidak Diketahui', 'label' => 'Tidak Diketahui', 'config' => old('gender') == 'Tidak Diketahui' ? 'selected' : ''],
                                    ['value' => 'Tulis Sendiri', 'label' => 'Tulis Sendiri', 'config' => old('gender') == 'Tulis Sendiri' ? 'selected' : ''],
                                ]
                            " />
                        </div>
                        <div class="col-md-6" id="form_gender_other">
                            <x-atoms.form-input id="gender_other" label="Jenis Kelamin (Lainnya)" type="text" name="gender_other" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="place_of_birth" label="Tempat Lahir" required="true" type="text" name="place_of_birth" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="date_of_birth" label="Tanggal Lahir" required="true" type="date" name="date_of_birth" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="phone_number">Nomor Telepon <span class="text-danger">*</span> :</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') border border-2 border-danger @enderror" placeholder="Masukkan Nomor Telepon" aria-label="Nomor Telepon" aria-describedby="phone_number" value="{{ old('phone_number') }}">
                            </div>
                            @error('phone_number')
                                <div id="passwordHelpBlock" class="form-text text-danger">
                                    {{ $message }}.
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="regency">Kabupaten / Kota <span class="text-danger">*</span> :</label>
                            <select class="js-states form-control select2 @error('regency') border border-2 border-danger @enderror" tabindex="-1" style="display: none; width: 100%" name="regency">
                                <option value="" disabled selected>Pilih Kabupaten / Kota</option>
                                @foreach ($data_regency as $item)
                                    <option value="{{ $item->id }}" {{ old('regency') == $item->id ? 'selected' : '' }}>({{ $item->province->name }}) - {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('regency')
                                <div id="passwordHelpBlock" class="form-text text-danger">
                                    {{ $message }}.
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="address" label="Alamat Lengkap" required="true" type="text" name="address" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="job" label="Pekerjaan" required="true" type="text" name="job" />
                        </div>

                        <div class="col-md-12">
                            <hr>
                        </div>
                        {{-- <div class="col-md-12">
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
                        </div> --}}
                        @can('my_profile')
                            <div class="col-md-12 text-end mt-4">
                                <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                            </div>
                        @endcan
                    </form>
                </div>
            @endif
        </div>
    </div>

    @push('js-internal')
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function(e) {
                function handleNumericInput(inputElement) {
                    inputElement.addEventListener('input', function(e) {
                        var value = inputElement.value;
                        inputElement.value = value.replace(/\D/g, '');
                    });
                }
                
                handleNumericInput(nin);
                handleNumericInput(phone_number);

                const gender_select = document.getElementById('gender');
                const gender_other_form = document.getElementById('form_gender_other');

                function toggleGenderOtherField() {
                    if (gender_select.value === 'Tulis Sendiri') {
                        gender_other_form.style.display = 'block';
                    } else {
                        gender_other_form.style.display = 'none';
                    }
                }

                toggleGenderOtherField();
                gender_select.addEventListener('change', toggleGenderOtherField);
            });
            
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#photo").change(function() {
                readURL(this);
            });

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
            });
        </script>
    @endpush
</x-app-layout>