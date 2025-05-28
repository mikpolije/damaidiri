<x-app-layout>
    <x-slot name="page_title"> Konfigurasi Tes </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Konfigurasi Tes'</b> berfungsi untuk melakukan konfigurasi (melihat statistik, mengelola kategori, mengelola pertanyaan dan mengelola jawaban) pada Tes di dalam sistem." />
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
            ['name' => 'Konfigurasi Tes.', 'url' => route('master-questionnaire.config', $detail_questionnaire->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="p-3">
                    <div class="px-1 d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold">Rincian Master Tes</h3>
                        <x-atoms.form-button type="button" id="hide" class="btn-warning py-2" icon="visibility_off" label="Tutup" />
                    </div>
                    <div class="table-responsive mt-3" id="content_detail">
                        <table class="table table-bordered">
                            <tr>
                                <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Tes</td>
                                <td class="fw-bold text-center bg-light">:</td>
                                <td class="w-75 bg-light">{{ $detail_questionnaire->title }}</td>
                            </tr>
                            <tr>
                                <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Deskripsi</td>
                                <td class="fw-bold text-center bg-light">:</td>
                                <td class="w-75 bg-light">{{ $detail_questionnaire->description }}</td>
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
                    ['label' => 'Statistik', 'permission' => 'config_questionnaire', 'route_active' => 'master-questionnaire.config', 'route' => route('master-questionnaire.config', $detail_questionnaire->id)],
                    ['label' => 'Kategori Tes', 'permission' => 'list_questionnaire_category', 'route_active' => 'master-questionnaire.questionnaire-category', 'route' => route('master-questionnaire.questionnaire-category.list', $detail_questionnaire->id)],
                    ['label' => 'Rincian Kategori Tes', 'permission' => 'list_questionnaire_detail_category', 'route_active' => 'master-questionnaire.questionnaire-detail-category', 'route' => route('master-questionnaire.questionnaire-detail-category.list', $detail_questionnaire->id)],
                    ['label' => 'Pertanyaan Tes', 'permission' => 'list_questionnaire_question', 'route_active' => 'master-questionnaire.questionnaire-question', 'route' => route('master-questionnaire.questionnaire-question.list', $detail_questionnaire->id)],
                    ['label' => 'Jawaban Tes', 'permission' => 'list_questionnaire_answer', 'route_active' => 'master-questionnaire.questionnaire-answer', 'route' => route('master-questionnaire.questionnaire-answer.list', $detail_questionnaire->id)],
                    ['label' => 'Histori Tes', 'permission' => 'list_questionnaire_history', 'route_active' => 'master-questionnaire.questionnaire-history', 'route' => route('master-questionnaire.questionnaire-history.list', $detail_questionnaire->id)],
                ]" />
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card file-manager-group shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="material-icons text-primary">category</i>
                    <div class="file-manager-group-info flex-fill">
                        <a href="#" class="file-manager-group-title">Kategori Tes</a>
                        <span class="file-manager-group-about">Total : {{ $detail_questionnaire->questionnaire_category->count() }} Kategori</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card file-manager-group shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="material-icons text-info">interests</i>
                    <div class="file-manager-group-info flex-fill">
                        <a href="#" class="file-manager-group-title">Rincian Kategori Tes</a>
                        <span class="file-manager-group-about">Total : {{ $count_detail_category }} Rincian Kategori</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card file-manager-group shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="material-icons text-warning">contact_support</i>
                    <div class="file-manager-group-info flex-fill">
                        <a href="#" class="file-manager-group-title">Pertanyaan Tes</a>
                        <span class="file-manager-group-about">Total : {{ $detail_questionnaire->questionnaire_question->count() }} Pertanyaan</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card file-manager-group shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="material-icons text-success">recommend</i>
                    <div class="file-manager-group-info flex-fill">
                        <a href="#" class="file-manager-group-title">Jawaban Tes</a>
                        <span class="file-manager-group-about">Total : {{ $detail_questionnaire->questionnaire_answer->count() }} Jawaban</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card file-manager-group shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="material-icons text-danger">work_history</i>
                    <div class="file-manager-group-info flex-fill">
                        <a href="#" class="file-manager-group-title">Histori Tes</a>
                        <span class="file-manager-group-about">Total : {{ $detail_questionnaire->screening->count() }} Jawaban</span>
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