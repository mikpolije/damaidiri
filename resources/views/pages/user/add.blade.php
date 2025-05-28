<x-app-layout>
    @push('css-internal')
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    @endpush

    <x-slot name="page_title"> Tambah Pengguna </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Tambah Pengguna'</b> berfungsi untuk menambahkan data baru pengguna pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_user')
            <x-atoms.pure-button-redirect route="user.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('create_user')
            <x-atoms.pure-button-redirect route="user.create" class="btn-warning" icon="restart_alt" label="Atur Ulang" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Data Pengguna', 'url' => route('user.list')],
            ['name' => 'Tambah Pengguna', 'url' => route('user.create')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1" action="{{ route('user.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <x-atoms.form-input id="name" label="Nama Pengguna" required="true" type="text" name="name" />
            </div>
            <div class="col-md-6">
                <x-atoms.form-input id="email" label="Email" required="true" type="email" name="email" />
            </div>
            <div class="col-md-6">
                <x-atoms.form-input id="password" label="Kata Sandi" required="true" type="password" name="password" />
            </div>
            <div class="col-md-6">
                <x-atoms.form-input id="password_confirmation" label="Konfirmasi Kata Sandi" required="true" type="password" name="password_confirmation" />
            </div>
            <div class="col-md-12">
                <x-atoms.form-select-option id="role_id" name="role_id" label="Hak Akses" class="select2" :options="array_merge(
                    $data_role->map(function($item) {
                        return [
                            'value' => $item->name, 
                            'label' => $item->name, 
                            'config' => old('role_id') == $item->name ? 'selected' : ''
                        ];
                    })->toArray()
                )" />
            </div>
            <div class="col-md-12" id="extend-form-patient" style="display: hidden;">
                <hr>
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <x-atoms.form-input id="nin" label="Nomor Induk Kependudukan (NIK)" required="true" type="text" name="nin" />
                    </div>
                    <div class="col-md-6">
                        <x-atoms.form-select-option id="gender" name="gender" label="Jenis Kelamin" class="select2" :options="
                            [
                                ['value' => 'Laki-laki', 'label' => 'Laki-laki', 'config' => old('gender') == 'Laki-laki' ? 'selected' : ''],
                                ['value' => 'Perempuan', 'label' => 'Perempuan', 'config' => old('gender') == 'Perempuan' ? 'selected' : ''],
                            ]
                        " />
                    </div>
                    <div class="col-md-6">
                        <x-atoms.form-input id="address" label="Alamat Lengkap" required="true" type="text" name="address" />
                    </div>
                    <div class="col-md-6">
                        <x-atoms.form-input id="job" label="Pekerjaan" required="true" type="text" name="job" />
                    </div>
                    <div class="col-md-2 text-center">
                        <img src="{{ asset('assets/images/avatars/avatar.png') }}" class="w-100 border rounded p-3" alt="avatar" id="preview">
                    </div>
                    <div class="col-md-10">
                        <x-atoms.form-input id="photo" label="Foto" required="true" type="file" name="photo" />
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="extend-form-psychologist" style="display: hidden;">
                <hr>
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <x-atoms.form-input id="nin" label="Nomor Induk Kependudukan (NIK)" required="true" type="text" name="nin" />
                    </div>
                    <div class="col-md-6">
                        <x-atoms.form-select-option id="gender" name="gender" label="Jenis Kelamin" class="select2" :options="
                            [
                                ['value' => 'Laki-laki', 'label' => 'Laki-laki', 'config' => old('gender') == 'Laki-laki' ? 'selected' : ''],
                                ['value' => 'Perempuan', 'label' => 'Perempuan', 'config' => old('gender') == 'Perempuan' ? 'selected' : ''],
                            ]
                        " />
                    </div>
                    <div class="col-md-6">
                        <x-atoms.form-input id="address" label="Alamat Lengkap" required="true" type="text" name="address" />
                    </div>
                </div>
            </div>
            @can('create_user')
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                </div>
            @endcan
        </form>
    </div>

    @push('js-internal')
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function(e) {
                function handleNumericInput(inputElement) {
                    inputElement.addEventListener('input', function(e) {
                        var value = inputElement.value;
                        inputElement.value = value.replace(/\D/g, '');
                    });
                }
                
                handleNumericInput(nin);
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

            $(document).ready(function() {
                $('.select2').select2();

                function togglePatientForm() {
                    let selectedRole = $("#role_id").val();
                    if (selectedRole === "patient") {
                        $("#extend-form-patient").slideDown();
                        $("#extend-form-psychologist").slideUp();
                    } else if (selectedRole === "psychologist") {
                        $("#extend-form-psychologist").slideDown();
                        $("#extend-form-patient").slideUp();
                    } else {
                        $("#extend-form-patient").slideUp();
                        $("#extend-form-psychologist").slideUp();
                    }
                }

                togglePatientForm();
                $("#role_id").change(function () {
                    togglePatientForm();
                });
            });
        </script>
    @endpush
</x-app-layout>