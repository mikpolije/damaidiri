<x-app-layout>
    <x-slot name="page_title"> Konfigurasi Jurnal </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Konfigurasi Jurnal'</b> berfungsi untuk melakukan konfigurasi (melihat statistik, mengelola kategori, mengelola pertanyaan dan mengelola jawaban) pada journal di dalam sistem." />
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
            ['name' => 'Konfigurasi Jurnal.', 'url' => route('master-journal.config', $detail_journal->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-5">
            <div class="col-md-12">
                <x-molecules.tabulation :tabs="[
                    ['label' => 'Statistik', 'permission' => 'config_journal', 'route_active' => 'master-journal.config', 'route' => route('master-journal.config', $detail_journal->id)],
                    ['label' => 'Pertanyaan Jurnal', 'permission' => 'list_journal_question', 'route_active' => 'master-journal.journal-question', 'route' => route('master-journal.journal-question.list', $detail_journal->id)],
                    ['label' => 'Histori Jurnal', 'permission' => 'list_journal_history', 'route_active' => 'master-journal.journal-history', 'route' => route('master-journal.journal-history.list', $detail_journal->id)],
                ]" />
            </div>
        </div>
    </div>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="px-3 pb-3">
                    <form method="POST" class="row g-3 mt-1 align-items-center" action="{{ route('master-journal.journal-question.update', [$detail_journal->id, $detail_journal_question->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="col-md-12">
                            <x-atoms.form-input id="name" label="Pertanyaan" required="true" type="text" name="name" value="{{ $detail_journal_question->name }}" />
                        </div>
                        <div class="col-md-12">
                            <x-atoms.form-input id="placeholder" label="Contoh Isian" required="true" type="text" name="placeholder" value="{{ $detail_journal_question->placeholder ?? '-' }}" />
                        </div>
                        @can('create_journal_question')
                        <div class="col-md-12 text-end mt-4">
                            <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                        </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>