<x-app-layout>
    <x-slot name="page_title"> Simulasi Jurnal </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Simulasi Jurnal'</b> berisi keseluruhan data master jurnal yang tersedia di sistem dan dapat melakukan simulasi." />
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Simulasi Jurnal', 'url' => route('simulation-journal.list')],
            ['name' => 'Daftar Jurnal', 'url' => route('simulation-journal.list')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('simulation-journal.list') }}" class="row align-items-center p-3">
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
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Topik Jurnal</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle w-50" style="background-color: #21336e;">Tujuan Jurnal</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_journal as $item)
                                <tr>
                                    <th scope="row" class="align-middle text-center fw-bold">{{ $loop->iteration + ($data_journal->currentPage() - 1) * $data_journal->perPage() }}</th>
                                    <td class="align-middle">{{ $item->topic }}</td>
                                    <td class="align-middle">{{ $item->purpose }}</td>
                                    <td class="align-middle">
                                        <div class="row g-3 justify-content-center align-items-center">
                                            @can('simulate_simulation_journal')
                                                <div class="col-12 text-center">
                                                    <x-atoms.pure-button-redirect-parameter route="{{ route('simulation-journal.simulate', $item->id) }}" class="btn-dark btn-style-light" icon="display_settings" label="Simulasikan" />
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
                        <span>Menampilkan <b>{{ number_format($data_journal->count(), 0, ',', '.') }}</b> data dari <b>{{ number_format($data_journal->total(), 0, ',', '.') }}</b> data.</span>
                    </div>
                    <div>
                        {{ $data_journal->appends(request()->query())->links('components.molecules.table-pagination') }}
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