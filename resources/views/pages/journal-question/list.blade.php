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

    @can('create_journal_question')
        <div class="container-fluid bg-white rounded mt-4 shadow">
            <div class="row p-3">
                <div class="col-md-12">
                    <div class="px-3 pb-3">
                        <form method="POST" class="row g-3 mt-1 align-items-center" action="{{ route('master-journal.journal-question.store', $detail_journal->id) }}">
                            @csrf
                            <div class="col-md-12">
                                <x-atoms.form-input id="name" label="Pertanyaan" required="true" type="text" name="name" />
                            </div>
                            <div class="col-md-12">
                                <x-atoms.form-input id="placeholder" label="Contoh Isian" required="true" type="text" name="placeholder" />
                            </div>
                            <div class="col-md-12 text-end mt-4">
                                <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('master-journal.journal-question.list', $detail_journal->id) }}" class="row align-items-center p-3">
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
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Pertanyaan</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Contoh Isian</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Ditambahkan Pada</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Diperbarui Pada</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_journal_question as $item)
                                <tr>
                                    <th scope="row" class="align-middle text-center fw-bold">{{ $loop->iteration + ($data_journal_question->currentPage() - 1) * $data_journal_question->perPage() }}</th>
                                    <td class="align-middle text-left w-25">{{ $item->name }}</td>
                                    <td class="align-middle text-left w-25">{{ $item->placeholder ?? '-' }}</td>
                                    <td class="align-middle text-center">{{ $item->created_at }}</td>
                                    <td class="align-middle text-center">{{ $item->updated_at }}</td>
                                    <td class="align-middle">
                                        <div class="widget-connection-request-actions d-flex">
                                            @can('update_journal_question')
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('master-journal.journal-question.edit', [$detail_journal->id, $item->id]) }}" class="btn-primary btn-style-light flex-grow-1 m-r-xxs" icon="draw" label="Ubah" />
                                            @endcan
                                            @can('delete_journal_question')
                                                <x-atoms.form-button-confirmation modal_name="modalDelete{{ $item->id }}" icon="delete" button_label="Hapus" class="btn-danger btn-style-light flex-grow-1 m-l-xxs" message_title="Apakah anda yakin ingin menghapus data?" message_description="Data yang sudah dihapus tidak dapat dikembalikan kembali." route="{{ route('master-journal.journal-question.destroy', [$detail_journal->id, $item->id]) }}" method="DELETE" />
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <x-molecules.table-not-found colspan="5" title="Maaf, Data Tidak Ditemukan / Belum Ada Data." description="Silahkan masukkan kata kunci yang sesuai / tambahkan data terlebih dahulu." />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center px-3 pb-3">
                    <div>
                        <span>Menampilkan <b>{{ number_format($data_journal_question->count(), 0, ',', '.') }}</b> data dari <b>{{ number_format($data_journal_question->total(), 0, ',', '.') }}</b> data.</span>
                    </div>
                    <div>
                        {{ $data_journal_question->appends(request()->query())->links('components.molecules.table-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js-internal')
        <script>
            document.addEventListener("DOMContentLoaded", function(e) {
                document.getElementById('show').addEventListener('change', function() {
                    var show = this.value;
                    var url = new URL(window.location.href);
                    url.searchParams.set('show', show);
                    window.location.href = url.toString();
                });
            });

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