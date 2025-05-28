<x-app-layout>
    <x-slot name="page_title"> Daftar Peran </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Daftar Peran'</b> berisi keseluruhan data peran yang tersedia di sistem." />
    </x-slot>
    @can('list_permission')
    <x-slot name="page_action">
        <x-atoms.pure-button-redirect route="permission.list" class="btn-primary" icon="dvr" label="Daftar Hak Akses" />
    </x-slot>
    @endcan
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Peran', 'url' => route('role.list')],
            ['name' => 'Daftar Peran', 'url' => route('role.list')],
        ]" />
    </x-slot>
    
    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('role.list') }}" class="row align-items-center p-3">
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
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Nama Peran</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Proteksi Peran</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Ditambahkan Pada</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Diperbarui Pada</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_role as $item)
                                <tr>
                                    <th scope="row" class="text-center fw-bold">{{ $loop->iteration + ($data_role->currentPage() - 1) * $data_role->perPage() }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->guard_name }}</td>
                                    <td class="text-center">{{ $item->created_at }}</td>
                                    <td class="text-center">{{ $item->updated_at }}</td>
                                    <td>
                                        <div class="widget-connection-request-actions d-flex">
                                            @can('update_role')
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('role.edit', $item->id) }}" class="btn-primary btn-style-light flex-grow-1 m-r-xxs" icon="manage_accounts" label="Kelola Hak Akses" />
                                            @endcan
                                            @can('read_role')
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('role.detail', $item->id) }}" class="btn-warning btn-style-light flex-grow-1" icon="preview" label="Lihat" />
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
                        <span>Menampilkan <b>{{ number_format($data_role->count(), 0, ',', '.') }}</b> data dari <b>{{ number_format($data_role->total(), 0, ',', '.') }}</b> data.</span>
                    </div>
                    <div>
                        {{ $data_role->appends(request()->query())->links('components.molecules.table-pagination') }}
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