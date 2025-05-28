<x-app-layout>
    <x-slot name="page_title"> Rincian Hak Akses </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Hak Akses'</b> berfungsi untuk melihat data hak akses secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_permission')
            <x-atoms.pure-button-redirect route="permission.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('update_permission')
            <x-atoms.pure-button-redirect-parameter route="{{ route('permission.edit', $detail_permission->id) }}" class="btn-primary" icon="draw" label="Ubah" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Hak Akses', 'url' => route('permission.list')],
            ['name' => 'Rincian Hak Akses', 'url' => route('permission.detail', $detail_permission->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Hak Akses</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_permission->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Ditambahkan Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_permission->created_at }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Diperbarui Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_permission->updated_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>