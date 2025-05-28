<x-app-layout>
    <x-slot name="page_title"> Ubah Master Jurnal </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Ubah Master Jurnal'</b> berfungsi untuk memperbarui data master jurnal pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_journal')
            <x-atoms.pure-button-redirect route="master-journal.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Master Jurnal', 'url' => route('master-journal.list')],
            ['name' => 'Ubah Master Jurnal', 'url' => route('master-journal.edit', $detail_journal->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1" action="{{ route('master-journal.update', $detail_journal->id) }}">
            @method('PUT')
            @csrf
            <div class="col-md-12">
                <x-atoms.form-input id="topic" label="Topik Jurnal" required="true" type="text" name="topic" value="{{ $detail_journal->topic }}" />
            </div>
            <div class="col-md-12">
                <x-atoms.form-textarea id="purpose" label="Tujuan" required="true" name="purpose" value="{{ $detail_journal->purpose }}" />
            </div>
            @can('update_journal')
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                </div>
            @endcan
        </form>
    </div>
</x-app-layout>