<x-app-layout>
    <x-slot name="page_title"> Daftar Pengguna </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Daftar Pengguna'</b> berisi keseluruhan data pengguna yang tersedia di sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('create_user')
            <x-atoms.pure-button-redirect route="user.create" class="btn-primary" icon="library_add" label="Tambah Data" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Konfigurasi', 'url' => route('user.list')],
            ['name' => 'Daftar Pengguna', 'url' => route('user.list')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('user.list') }}" class="row align-items-center p-3">
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
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Nama Pengguna</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Email</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Status Akun</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Hak Akses</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $row_number = 1 + ($data_user->currentPage() - 1) * $data_user->perPage();
                            @endphp
                            @forelse ($data_user as $item)
                                @if ($item->roles->first()->name != 'superadmin' || auth()->user()->roles->first()->name == 'superadmin')
                                    <tr>
                                        <th scope="row" class="text-center fw-bold">{{ $row_number++ }}</th>
                                        <td>{{ $item->name ?? '-' }}</td>
                                        <td class="text-left">{{ mask_email($item->email) ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-style-light rounded-pill px-4 py-2 {{ $item->is_actived == '1' ? 'bg-success' : 'bg-danger' }}">{{ $item->is_actived == '1' ? 'Aktif' : 'Tidak Aktif' }}</span>
                                        </td>
                                        <td class="text-center">{{ $item->roles->first()->name ?? '-' }}</td>
                                        <td>
                                            <div class="widget-connection-request-actions d-flex">
                                                @can('update_user')
                                                    <x-atoms.pure-button-redirect-parameter route="{{ route('user.edit', $item->id) }}" class="btn-primary btn-style-light flex-grow-1 m-r-xxs" icon="draw" label="Ubah" />
                                                @endcan
                                                @can('read_user')
                                                    <x-atoms.pure-button-redirect-parameter route="{{ route('user.detail', $item->id) }}" class="btn-warning btn-style-light flex-grow-1" icon="preview" label="Lihat" />
                                                @endcan
                                                @can('delete_user')
                                                    <x-atoms.form-button-confirmation modal_name="modalDelete{{ $item->id }}" icon="delete" button_label="Hapus" class="btn-danger btn-style-light flex-grow-1 m-l-xxs" message_title="Apakah anda yakin ingin menghapus data?" message_description="Data yang sudah dihapus tidak dapat dikembalikan kembali." route="{{ route('user.destroy', $item->id) }}" method="DELETE" />
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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
                        <span>Menampilkan <b>{{ number_format($data_user->count(), 0, ',', '.') }}</b> data dari <b>{{ number_format($data_user->total(), 0, ',', '.') }}</b> data.</span>
                    </div>
                    <div>
                        {{ $data_user->appends(request()->query())->links('components.molecules.table-pagination') }}
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