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
        <div class="row p-3">
            <div class="col-md-12">
                <div class="p-3">
                    <div class="px-1 d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold">Rincian Master Jurnal</h3>
                        <x-atoms.form-button type="button" id="hide" class="btn-warning py-2" icon="visibility_off" label="Tutup" />
                    </div>
                    <div class="table-responsive mt-3" id="content_detail">
                        <table class="table table-bordered">
                            <tr>
                                <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Topik Jurnal</td>
                                <td class="fw-bold text-center bg-light">:</td>
                                <td class="w-75 bg-light">{{ $detail_journal->topic }}</td>
                            </tr>
                            <tr>
                                <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Tujuan</td>
                                <td class="fw-bold text-center bg-light">:</td>
                                <td class="w-75 bg-light">{{ $detail_journal->purpose }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card file-manager-group shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="material-icons text-warning">contact_support</i>
                    <div class="file-manager-group-info flex-fill">
                        <a href="#" class="file-manager-group-title">Pertanyaan Jurnal</a>
                        <span class="file-manager-group-about">Total : {{ $detail_journal->journal_question->count() }} Pertanyaan</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card file-manager-group shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="material-icons text-danger">work_history</i>
                    <div class="file-manager-group-info flex-fill">
                        <a href="#" class="file-manager-group-title">Histori Jurnal</a>
                        <span class="file-manager-group-about">Total : {{ $detail_journal->response->count() }} Jawaban</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js-internal')
        <script>
            const toggleButton = document.getElementById("hide");
            const contentDetail = document.getElementById("content_detail");

            contentDetail.style.display = "none";
            toggleButton.innerHTML = '<i class="material-icons">visibility</i> Buka';

            toggleButton.addEventListener("click", function () {
                if (contentDetail.style.display === "none") {
                    contentDetail.style.display = "block";
                    toggleButton.innerHTML = '<i class="material-icons">visibility_off</i> Tutup';
                } else {
                    contentDetail.style.display = "none";
                    toggleButton.innerHTML = '<i class="material-icons">visibility</i> Buka';
                }
            });
        </script>
    @endpush
</x-app-layout>