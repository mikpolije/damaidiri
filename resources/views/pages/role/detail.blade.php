<x-app-layout>
    <x-slot name="page_title"> Rincian Peran </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Peran'</b> berfungsi untuk melihat data peran secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_role')
            <x-atoms.pure-button-redirect route="role.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Peran', 'url' => route('role.list')],
            ['name' => 'Rincian Peran', 'url' => route('role.detail', $detail_role->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="align-middle w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Peran</td>
                            <td class="align-middle fw-bold text-center bg-light">:</td>
                            <td class="align-middle w-75 bg-light">{{ $detail_role->name }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle w-25 fw-bold text-white" style="background-color: #21336e !important;">Proteksi Peran</td>
                            <td class="align-middle fw-bold text-center bg-light">:</td>
                            <td class="align-middle w-75 bg-light">{{ $detail_role->guard_name }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle w-25 fw-bold text-white" style="background-color: #21336e !important;">Ditambahkan Pada</td>
                            <td class="align-middle fw-bold text-center bg-light">:</td>
                            <td class="align-middle w-75 bg-light">{{ $detail_role->created_at }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle w-25 fw-bold text-white" style="background-color: #21336e !important;">Diperbarui Pada</td>
                            <td class="align-middle fw-bold text-center bg-light">:</td>
                            <td class="align-middle w-75 bg-light">{{ $detail_role->updated_at }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle w-25 fw-bold text-white" style="background-color: #21336e !important;">Daftar Hak Akses</td>
                            <td class="align-middle fw-bold text-center bg-light">:</td>
                            <td class="align-middle w-75 bg-light">
                                @forelse ($detail_role->permissions as $item)
                                    <span>{{ $item->name }} {{ $loop->last ? '.' : ',' }} </span>
                                @empty
                                    <span>Belum ada Hak Akses yang ditambahkan.</span>
                                @endforelse
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>