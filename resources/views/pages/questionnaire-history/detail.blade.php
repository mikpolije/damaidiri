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
                        <h3 class="fw-bold">Rincian Screening</h3>
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

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-5">
            <div class="col-md-12">
                <h1 style="font-weight: bold; color: #27313F; margin: 0; font-size: 24px;">Hasil Screening</h1>
                <hr />
            </div>

            <div class="col-md-12">
                <div class="table-responsive mt-3" id="content_detail">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Pasien</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_screening->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Skor Akumulasi</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_screening->score_accumulate }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Status Screening</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">
                                <span class="badge badge-style-light rounded-pill px-4 py-2 {{ $detail_screening->is_simulate == '1' ? 'bg-warning' : 'bg-success' }}">{{ $detail_screening->is_simulate == '1' ? 'Data Simulasi' : 'Data Riil' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                @foreach ($detail_screening->questionnaire->questionnaire_category as $item_category)
                                    <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">{{ $item_category->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="fw-bold">
                                @foreach ($detail_screening->questionnaire->questionnaire_category as $item_calculate)
                                    @php
                                        $totalScore = $detail_screening->screening_detail
                                            ->filter(fn($detail) => $detail->questionnaire_question->questionnaire_category_id == $item_calculate->id)
                                            ->sum('score');
                            
                                        $level = $item_calculate->questionnaire_category_detail
                                            ->first(function ($detail) use ($totalScore) {
                                                return $detail->minimum_score <= $totalScore &&
                                                    ($detail->maximum_score === null || $totalScore <= $detail->maximum_score);
                                            });
                                    @endphp
                                    <td class="align-middle text-center">
                                        {{ $level->level ?? 'Tidak Diketahui' }} ({{ $totalScore }})
                                    </td>
                                @endforeach
                            </tr>                            
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <hr />
                @forelse ($detail_questionnaire->questionnaire_question as $item_question)
                    <div class="col-md-12">
                        <x-atoms.pure-label label="{{ $loop->iteration }}. {{ $item_question->name }}" required="true" />
                        <div class="row">
                            @forelse ($detail_questionnaire->questionnaire_answer as $item_answer)
                                <div class="form-check col">
                                    <input class="form-check-input" type="radio" name="answer_question_{{ $item_question->id }}" id="answer_question_{{ $item_question->id }}_{{ $item_answer->id }}" value="{{ $item_answer->id }}" {{ $detail_screening->screening_detail->where('questionnaire_question_id', $item_question->id)->first()->questionnaire_answer_id == $item_answer->id ? 'checked' : '' }} onclick="return false;" >
                                    <label class="form-check-label" for="answer_question_{{ $item_question->id }}_{{ $item_answer->id }}">
                                        {{ $item_answer->name }}
                                    </label>
                                </div>
                            @empty
                                <div class="col-md-12">
                                    <div class="card file-manager-group">
                                        <div class="card-body d-flex align-items-center">
                                            <i class="material-icons text-success">image</i>
                                            <div class="file-manager-group-info flex-fill">
                                                <a href="#" class="file-manager-group-title">Maaf, Belum Ada Data.</a>
                                                <span class="file-manager-group-about">Silahkan tambahkan data terlebih dahulu.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                            @error('answer_question_' . $item_question->id)
                                <div class="text-danger small mt-1 fw-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="card file-manager-group">
                            <div class="card-body d-flex align-items-center">
                                <i class="material-icons text-success">image</i>
                                <div class="file-manager-group-info flex-fill">
                                    <a href="#" class="file-manager-group-title">Maaf, Belum Ada Data.</a>
                                    <span class="file-manager-group-about">Silahkan tambahkan data terlebih dahulu.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
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