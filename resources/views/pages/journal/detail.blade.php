<x-app-layout>
    <x-slot name="page_title"> Rincian Master Jurnal </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Master Jurnal'</b> berfungsi untuk melihat data master jurnal secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_journal')
            <x-atoms.pure-button-redirect route="master-journal.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('update_journal')
            <x-atoms.pure-button-redirect-parameter route="{{ route('master-journal.edit', $detail_journal->id) }}" class="btn-primary" icon="draw" label="Ubah" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Master Jurnal', 'url' => route('master-journal.list')],
            ['name' => 'Rincian Master Jurnal', 'url' => route('master-journal.detail', $detail_journal->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Topik Jurnal</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_journal->topic }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Tujuan</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light" style="text-align: justify;">{{ $detail_journal->purpose }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Total Pertanyaan</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light" style="text-align: justify;">{{ $detail_journal->journal_question->count() }} Pertanyaan</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Ditambahkan Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_journal->created_at }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Diperbarui Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_journal->updated_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>