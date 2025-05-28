<x-app-layout>
    <x-slot name="page_title"> Rincian Pengguna </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Pengguna'</b> berfungsi untuk melihat data pengguna secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_user')
            <x-atoms.pure-button-redirect route="user.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('update_user')
            <x-atoms.pure-button-redirect-parameter route="{{ route('user.edit', $detail_user->id) }}" class="btn-primary" icon="draw" label="Ubah" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Data Pengguna', 'url' => route('user.list')],
            ['name' => 'Rincian Pengguna', 'url' => route('user.detail', $detail_user->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Pengguna</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Email</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ mask_email($detail_user->email) ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Status Akun</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">
                                <span class="badge badge-style-light rounded-pill px-4 py-2 {{ $detail_user->is_actived == '1' ? 'bg-success' : 'bg-danger' }}">{{ $detail_user->is_actived == '1' ? 'Aktif' : 'Tidak Aktif' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Ditambahkan Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_user->created_at ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Diperbarui Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_user->updated_at ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @if ($detail_user->roles->first()->name == 'patient')
                <div class="col-md-12">
                    <div class="px-3">
                        <hr>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive px-3 pt-3">
                        <table class="table table-bordered">
                            @if ($detail_user->profile != null)
                                <tr>
                                    <td class="w-75 bg-ligh text-center" colspan="3">
                                        <img src="{{ asset($detail_user->profile->photo ?? 'assets/images/avatars/avatar.png') }}" alt="photo" class="img-fluid p-2" width="150" height="150"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nomor Induk Kependudukan (NIK)</td>
                                    <td class="fw-bold text-center bg-light">:</td>
                                    <td class="w-75 bg-light">{{ mask_text(2, 4, $detail_user->profile->nin) ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Jenis Kelamin</td>
                                    <td class="fw-bold text-center bg-light">:</td>
                                    <td class="w-75 bg-light">{{ $detail_user->profile->gender ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Alamat Lengkap</td>
                                    <td class="fw-bold text-center bg-light">:</td>
                                    <td class="w-75 bg-light">{{ $detail_user->profile->address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Pekerjaan</td>
                                    <td class="fw-bold text-center bg-light">:</td>
                                    <td class="w-75 bg-light">{{ $detail_user->profile->job ?? '-' }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="w-75 bg-light text-center fw-bold fst-italic" colspan="3">
                                        Data Pasien Belum Dilengkapi
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            @endif

            @if ($detail_user->roles->first()->name == 'psychologist')
                <div class="col-md-12">
                    <div class="px-3">
                        <hr>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive px-3 pt-3">
                        <table class="table table-bordered">
                            @if ($detail_user->psychologist != null)
                                <tr>
                                    <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nomor Induk Kependudukan (NIK)</td>
                                    <td class="fw-bold text-center bg-light">:</td>
                                    <td class="w-75 bg-light">{{ mask_text(2, 4, $detail_user->psychologist->nin) ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Jenis Kelamin</td>
                                    <td class="fw-bold text-center bg-light">:</td>
                                    <td class="w-75 bg-light">{{ $detail_user->psychologist->gender ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Alamat Lengkap</td>
                                    <td class="fw-bold text-center bg-light">:</td>
                                    <td class="w-75 bg-light">{{ $detail_user->psychologist->address ?? '-' }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="w-75 bg-light text-center fw-bold fst-italic" colspan="3">
                                        Data Psikologi Belum Dilengkapi
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            @endif

            @if ($detail_user->roles->first()->name != 'superadmin')
                <div class="col-md-12">
                    <div class="p-3 text-center d-flex gap-3 items-center justify-content-center">
                        @if ($detail_user->is_actived == '1')
                            @can('deactivated_user')
                                <x-atoms.form-button-confirmation modal_name="modalDeactivated" icon="lock" button_label="Nonaktifkan Akun" class="btn-danger px-5" message_title="Apakah anda yakin ingin menonaktifkan akun?" message_description="Pengguna tidak dapat melakukan aktivitas pada sistem sampai akun diaktifkan kembali." route="{{ route('user.deactivated', $detail_user->id) }}" method="PUT" />
                            @endcan
                        @else
                            @can('activated_user')
                                <x-atoms.form-button-confirmation modal_name="modalActivated" icon="lock_open" button_label="Aktifkan Akun" class="btn-success px-5" message_title="Apakah anda yakin ingin mengaktifkan kembali akun?" message_description="Pengguna akan dapat melakukan aktivitas pada sistem." route="{{ route('user.activated', $detail_user->id) }}" method="PUT" class_button_submit="btn-success" />
                            @endcan
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>