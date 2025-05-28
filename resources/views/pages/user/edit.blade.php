<x-app-layout>
    @push('css-internal')
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    @endpush

    <x-slot name="page_title"> Ubah Pengguna </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Ubah Pengguna'</b> berfungsi untuk memperbarui data pengguna pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_user')
            <x-atoms.pure-button-redirect route="user.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Data Pengguna', 'url' => route('user.list')],
            ['name' => 'Ubah Pengguna', 'url' => route('user.edit', $detail_user->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1" action="{{ route('user.update', $detail_user->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="col-md-6">
                <x-atoms.form-input id="name" label="Nama Pengguna" required="true" type="text" name="name" value="{{ $detail_user->name }}" />
            </div>
            <div class="col-md-6">
                <x-atoms.form-input id="email" label="Email" required="true" type="email" name="email" value="{{ $detail_user->email }}" />
            </div>
            <div class="col-md-6">
                <x-atoms.form-input id="status" class="fw-bold {{ $detail_user->is_actived == 1 ? 'text-success' : 'text-danger' }}" label="Status Akun" required="true" type="text" name="status" value="{{ $detail_user->is_actived == 1 ? 'Aktif' : 'Tidak Aktif' }}" readonly="true" />
            </div>
            <div class="col-md-6">
                <x-atoms.form-input id="role_id" label="Hak Akses" required="true" type="text" name="role_id" value="{{ $detail_user->roles->first()->name }}" readonly="true" />
            </div>
            @if ($detail_user->roles->first()->name == 'patient')
                <div class="col-md-12" id="extend-form-patient" style="display: hidden;">
                    <hr>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <x-atoms.form-input id="nin" label="Nomor Induk Kependudukan (NIK)" required="true" type="text" name="nin" value="{{ $detail_user->profile->nin ?? '' }}" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-select-option id="gender" name="gender" label="Jenis Kelamin" class="select2" :options="
                                [
                                    ['value' => 'Laki-laki', 'label' => 'Laki-laki', 'config' => old('gender', $detail_user->profile->gender ?? '') == 'Laki-laki' ? 'selected' : ''],
                                    ['value' => 'Perempuan', 'label' => 'Perempuan', 'config' => old('gender', $detail_user->profile->gender ?? '') == 'Perempuan' ? 'selected' : ''],
                                ]
                            " />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="address" label="Alamat Lengkap" required="true" type="text" name="address" value="{{ $detail_user->profile->address ?? '' }}" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="job" label="Pekerjaan" required="true" type="text" name="job" value="{{ $detail_user->profile->job ?? '' }}" />
                        </div>
                        <div class="col-md-2 text-center">
                            <img src="{{ asset( $detail_user->profile->photo ?? 'assets/images/avatars/avatar.png') }}" class="w-100 border rounded p-3" alt="avatar" id="preview">
                        </div>
                        <div class="col-md-10">
                            <x-atoms.form-input id="photo" label="Foto" required="true" type="file" name="photo" />
                        </div>
                    </div>
                </div>
            @endif

            @if ($detail_user->roles->first()->name == 'psychologist')
                <div class="col-md-12" id="extend-form-patient" style="display: hidden;">
                    <hr>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <x-atoms.form-input id="nin" label="Nomor Induk Kependudukan (NIK)" required="true" type="text" name="nin" value="{{ $detail_user->psychologist->nin ?? '' }}" />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-select-option id="gender" name="gender" label="Jenis Kelamin" class="select2" :options="
                                [
                                    ['value' => 'Laki-laki', 'label' => 'Laki-laki', 'config' => old('gender', $detail_user->psychologist->gender ?? '') == 'Laki-laki' ? 'selected' : ''],
                                    ['value' => 'Perempuan', 'label' => 'Perempuan', 'config' => old('gender', $detail_user->psychologist->gender ?? '') == 'Perempuan' ? 'selected' : ''],
                                ]
                            " />
                        </div>
                        <div class="col-md-6">
                            <x-atoms.form-input id="address" label="Alamat Lengkap" required="true" type="text" name="address" value="{{ $detail_user->psychologist->address ?? '' }}" />
                        </div>
                    </div>
                </div>
            @endif

            @can('update_user')
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
            });
        </script>
    @endpush
</x-app-layout>