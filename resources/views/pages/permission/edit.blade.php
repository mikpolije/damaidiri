<x-app-layout>
    <x-slot name="page_title"> Ubah Hak Akses </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Ubah Hak Akses'</b> berfungsi untuk memperbarui data hak akses pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_permission')
            <x-atoms.pure-button-redirect route="permission.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Hak Akses', 'url' => route('permission.list')],
            ['name' => 'Ubah Hak Akses', 'url' => route('permission.edit', $detail_permission->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1" action="{{ route('permission.update', $detail_permission->id) }}">
            @method('PUT')
            @csrf
            <div class="col-md-12">
                <x-atoms.form-input id="name" label="Nama Hak Akses" required="true" type="text" name="name" value="{{ $detail_permission->name }}" />
            </div>
            @can('update_permission')
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                </div>
            @endcan
        </form>
    </div>
</x-app-layout>