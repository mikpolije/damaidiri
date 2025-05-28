<x-app-layout>
    <x-slot name="page_title"> Kelola Hak Akses </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Kelola Hak Akses'</b> berfungsi untuk mengelola data hak akses dan membatasi peran tertentu pada sistem." />
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
            ['name' => 'Kelola Hak Akses', 'url' => route('role.detail', $detail_role->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Peran</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_role->name }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle w-25 fw-bold text-white" style="background-color: #21336e !important;">Daftar Hak Akses Saat Ini</td>
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
            <div class="col-md-12 px-3">
                <hr>
            </div>
            <div class="col-md-12">
                <form class="row px-4" method="POST" action="{{ route('role.update', $detail_role->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="col-md-12">
                        <x-atoms.pure-label label="Daftar Hak Akses" required="true" />
                    </div>
                    @foreach ($data_permission as $item)
                    <div class="col-md-3">
                        <x-atoms.pure-checkbox name="permission[]" id="{{ $item->id }}" value="{{ $item->name }}" label="{{ $item->name }}" config="{{ $detail_role->hasPermissionTo($item->name) ? 'checked' : '' }}" />
                    </div>
                    @endforeach
                    @can('update_role')
                        <div class="col-md-12 text-end mb-4">
                            <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>

</x-app-layout>