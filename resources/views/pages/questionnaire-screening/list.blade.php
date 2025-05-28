<x-app-layout>
    <x-slot name="page_title">Daftar Tes</x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Daftar Tes'</b> berisi keseluruhan data Tes yang tersedia di sistem dan dapat melakukan Tes." />
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Daftar Tes', 'url' => route('questionnaire-screening.list')],
            ['name' => 'Tes', 'url' => route('questionnaire-screening.list')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('questionnaire-screening.list') }}" class="row align-items-center p-3">
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
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Deskripsi Tes</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_questionnaire as $item)
                                <tr>
                                    <th scope="row" class="align-middle text-center fw-bold">{{ $loop->iteration + ($data_questionnaire->currentPage() - 1) * $data_questionnaire->perPage() }}</th>
                                    <td class="align-middle w-25">{{ $item->title }}</td>
                                    <td class="align-middle w-50">{{ $item->description }}</td>
                                    <td class="align-middle">
                                        <div class="row g-3 justify-content-center align-items-center">
                                            @can('screening_questionnaire')
                                                <div class="col-12 text-center">
                                                    <x-atoms.pure-button-redirect-parameter route="{{ route('questionnaire-screening.screening', $item->id) }}" class="btn-dark btn-style-light" icon="display_settings" label="Isi Tes" />
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