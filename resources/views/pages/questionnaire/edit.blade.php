<x-app-layout>
    <x-slot name="page_title"> Ubah Master Tes </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Ubah Master Tes'</b> berfungsi untuk memperbarui data master Tes pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_questionnaire')
            <x-atoms.pure-button-redirect route="master-questionnaire.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Master Tes', 'url' => route('master-questionnaire.list')],
            ['name' => 'Ubah Master Tes', 'url' => route('master-questionnaire.edit', $detail_questionnaire->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1" action="{{ route('master-questionnaire.update', $detail_questionnaire->id) }}">
            @method('PUT')
            @csrf
            <div class="col-md-12">
                <x-atoms.form-input id="title" label="Nama Tes" required="true" type="text" name="title" value="{{ $detail_questionnaire->title }}" />
            </div>
            <div class="col-md-12">
                <x-atoms.form-textarea id="description" label="Deskripsi" required="true" name="description" value="{{ $detail_questionnaire->description }}" />
            </div>
            @can('update_questionnaire')
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                </div>
            @endcan
        </form>
    </div>
</x-app-layout>