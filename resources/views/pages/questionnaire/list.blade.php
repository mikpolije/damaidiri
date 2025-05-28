<x-app-layout>
    <x-slot name="page_title"> Daftar Master Tes </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Daftar Master Tes'</b> berisi keseluruhan data master Tes yang tersedia di sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('create_questionnaire')
            <x-atoms.pure-button-redirect route="master-questionnaire.create" class="btn-primary" icon="library_add" label="Tambah Data" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Master Tes', 'url' => route('master-questionnaire.list')],
            ['name' => 'Daftar Master Tes', 'url' => route('master-questionnaire.list')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('master-questionnaire.list') }}" class="row align-items-center p-3">
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
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Nama Tes</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Total Kategori</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Total Pertanyaan</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Total Jawaban</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_questionnaire as $item)
                                <tr>
                                    <th scope="row" class="align-middle text-center fw-bold">{{ $loop->iteration + ($data_questionnaire->currentPage() - 1) * $data_questionnaire->perPage() }}</th>
                                    <td class="align-middle">{{ $item->title }}</td>
                                    <td class="align-middle text-center">{{ $item->questionnaire_category->count() }} Kategori</td>
                                    </td>
                                    <td class="align-middle text-center">{{ $item->questionnaire_question->count() }} Pertanyaan</td>
                                    <td class="align-middle text-center">{{ $item->questionnaire_answer->count() }} Jawaban</td>
                                    <td class="align-middle">
                                        <div class="row g-3">
                                            @can('config_questionnaire')
                                                <div class="col-6">
                                                    <x-atoms.pure-button-redirect-parameter route="{{ route('master-questionnaire.config', $item->id) }}" class="btn-dark btn-style-light w-100" icon="display_settings" label="Konfigurasi" />
                                                </div>
                                            @endcan
                                            @can('update_questionnaire')
                                                <div class="col-6">
                                                    <x-atoms.pure-button-redirect-parameter route="{{ route('master-questionnaire.edit', $item->id) }}" class="btn-primary btn-style-light w-100" icon="draw" label="Ubah" />
                                                </div>
                                            @endcan
                                            @can('read_questionnaire')
                                                <div class="col-6">
                                                    <x-atoms.pure-button-redirect-parameter route="{{ route('master-questionnaire.detail', $item->id) }}" class="btn-warning btn-style-light w-100" icon="preview" label="Lihat" />
                                                </div>
                                            @endcan
                                            @can('delete_questionnaire')
                                                <div class="col-6">
                                                    <x-atoms.form-button-confirmation modal_name="modalDelete{{ $item->id }}" icon="delete" button_label="Hapus" class="btn-danger btn-style-light w-100" message_title="Apakah anda yakin ingin menghapus data?" message_description="Data yang sudah dihapus tidak dapat dikembalikan kembali." route="{{ route('master-questionnaire.destroy', $item->id) }}" method="DELETE" />
                                                </div>
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
                        <span>Menampilkan <b>{{ number_format($data_questionnaire->count(), 0, ',', '.') }}</b> data dari <b>{{ number_format($data_questionnaire->total(), 0, ',', '.') }}</b> data.</span>
                    </div>
                    <div>
                        {{ $data_questionnaire->appends(request()->query())->links('components.molecules.table-pagination') }}
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
        </script>
    @endpush
</x-app-layout>