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

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('master-questionnaire.questionnaire-category.list', $detail_questionnaire->id) }}" class="row align-items-center p-3">
                    <div class="col-md-7">
                        <x-atoms.pure-input type="text" placeholder="Masukkan Kata Kunci..." name="search" id="search" value="{{ $search }}"/>
                    </div>
                    <div class="col-md-2 my-1">
                        <x-atoms.form-button type="submit" class="btn-primary w-100" icon="pageview" label="Cari"/>
                    </div>
                    <div class="col-md-3">
                        <x-atoms.pure-select-option id="show" name="show" :options="[
                            ['value' => 5, 'label' => 'Data Ditampilkan : 5 Data.', 'config' => $per_page == 5 ? 'selected' : '' ],
                            ['value' => 10, 'label' => 'Data Ditampilkan : 10 Data.', 'config' => $per_page == 10 ? 'selected' : '' ],
                            ['value' => 25, 'label' => 'Data Ditampilkan : 25 Data.', 'config' => $per_page == 25 ? 'selected' : '' ],
                            ['value' => 50, 'label' => 'Data Ditampilkan : 50 Data.', 'config' => $per_page == 50 ? 'selected' : '' ],
                            ['value' => 100, 'label' => 'Data Ditampilkan : 100 Data.', 'config' => $per_page == 100 ? 'selected' : '' ],
                        ]" />
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="table-responsive px-3">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">No</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Nama Pasien</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Nama Tes</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Kode Tes</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Skor Akumulasi</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Status Screening</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_screening as $item)
                                <tr>
                                    <th scope="row" class="align-middle text-center fw-bold">{{ $loop->iteration + ($data_screening->currentPage() - 1) * $data_screening->perPage() }}</th>
                                    <td class="align-middle text-left">{{ $item->user->name }}</td>
                                    <td class="align-middle text-center">{{ $item->questionnaire->title }}</td>
                                    <td class="align-middle text-center">{{ $item->screening_code }}</td>
                                    <td class="align-middle text-center">{{ $item->score_accumulate }}</td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-style-light rounded-pill px-4 py-2 {{ $item->is_simulate == '1' ? 'bg-warning' : 'bg-success' }}">{{ $item->is_simulate == '1' ? 'Data Simulasi' : 'Data Riil' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="widget-connection-request-actions d-flex">
                                            @can('detail_questionnaire_history')
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('master-questionnaire.questionnaire-history.detail', [$detail_questionnaire->id, $item->id]) }}" class="btn-warning btn-style-light w-100" icon="preview" label="Lihat" />
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <x-molecules.table-not-found colspan="6" title="Maaf, Data Tidak Ditemukan / Belum Ada Data." description="Silahkan masukkan kata kunci yang sesuai / tambahkan data terlebih dahulu." />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center px-3 pb-3">
                    <div>
                        <span>Menampilkan <b>{{ number_format($data_screening->count(), 0, ',', '.') }}</b> data dari <b>{{ number_format($data_screening->total(), 0, ',', '.') }}</b> data.</span>
                    </div>
                    <div>
                        {{ $data_screening->appends(request()->query())->links('components.molecules.table-pagination') }}
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