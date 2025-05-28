<x-app-layout>
    <x-slot name="page_title"> Tambah Tes </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Tambah Tes'</b> berfungsi untuk menambahkan data baru Tes pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_questionnaire')
            <x-atoms.pure-button-redirect route="master-questionnaire.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('create_questionnaire')
            <x-atoms.pure-button-redirect route="master-questionnaire.create" class="btn-warning" icon="restart_alt" label="Atur Ulang" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Tes', 'url' => route('master-questionnaire.list')],
            ['name' => 'Tambah Tes', 'url' => route('master-questionnaire.create')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1" action="{{ route('master-questionnaire.store') }}">
            @csrf
            <div class="col-md-12">
                <x-atoms.form-input id="title" label="Nama Tes" required="true" type="text" name="title" />
            </div>
            <div class="col-md-12">
                <x-atoms.form-textarea id="description" label="Deskripsi" required="true" name="description" />
            </div>
            @can('create_questionnaire')
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                </div>
            @endcan
        </form>
    </div>
</x-app-layout>