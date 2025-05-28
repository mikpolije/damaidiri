<x-app-layout>
    <x-slot name="page_title"> Daftar Mitra </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Daftar Mitra'</b> berisi keseluruhan data mitra yang tersedia di sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('create_partner')
            <x-atoms.pure-button-redirect route="master-partner.create" class="btn-primary" icon="library_add" label="Tambah Data" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Mitra', 'url' => route('master-partner.list')],
            ['name' => 'Daftar Mitra', 'url' => route('master-partner.list')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('master-partner.list') }}" class="row align-items-center p-3">
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
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Nama Mitra</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Nomor Telepon</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Alamat</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle w-25" style="background-color: #21336e;">Google Map</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle w-25" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_partner as $item)
                                <tr>
                                    <th scope="row" class="text-center fw-bold align-middle">{{ $loop->iteration + ($data_partner->currentPage() - 1) * $data_partner->perPage() }}</th>
                                    <td class="align-middle">{{ $item->name }}</td>
                                    <td class="align-middle">{{ "+".$item->phone_number }}</td>
                                    <td class="align-middle">{{ $item->address }}</td>
                                    <td class="align-middle text-center">
                                        <a href="{{ $item->google_maps_url }}" target="_blank" class="btn btn-primary">
                                            <i class="material-icons">draw</i>
                                            Lihat Google Map
                                        </a>
                                    </td>
                                    <td>
                                        <div class="widget-connection-request-actions d-flex">
                                            @can('update_partner')
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('master-partner.edit', $item->id) }}" class="btn-primary btn-style-light flex-grow-1 m-r-xxs" icon="draw" label="Ubah" />
                                            @endcan
                                            @can('read_partner')
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('master-partner.detail', $item->id) }}" class="btn-warning btn-style-light flex-grow-1" icon="preview" label="Lihat" />
                                            @endcan
                                            @can('delete_partner')
                                                <x-atoms.form-button-confirmation modal_name="modalDelete{{ $item->id }}" icon="delete" button_label="Hapus" class="btn-danger btn-style-light flex-grow-1 m-l-xxs" message_title="Apakah anda yakin ingin menghapus data?" message_description="Data yang sudah dihapus tidak dapat dikembalikan kembali." route="{{ route('master-partner.destroy', $item->id) }}" method="DELETE" />
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
                        <span>Menampilkan <b>{{ number_format($data_partner->count(), 0, ',', '.') }}</b> data dari <b>{{ number_format($data_partner->total(), 0, ',', '.') }}</b> data.</span>
                    </div>
                    <div>
                        {{ $data_partner->appends(request()->query())->links('components.molecules.table-pagination') }}
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