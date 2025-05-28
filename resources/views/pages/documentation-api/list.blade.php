<x-app-layout>
    <x-slot name="page_title"> Daftar Dokumentasi API </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Daftar Dokumentasi API'</b> berisi keseluruhan data dokumentasi API yang tersedia di sistem." />
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Dokumentasi API', 'url' => route('documentation-api.list')],
            ['name' => 'Daftar Dokumentasi API', 'url' => route('documentation-api.list')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12 pt-3">
                <div class="table-responsive px-3">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">No</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Nama API</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Tipe API</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Endpoint</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_api as $item)
                                <tr>
                                    <th scope="row" class="text-center fw-bold">{{ $loop->iteration }}</th>
                                    <td class="text-left">{{ $item['name'] }}</td>
                                    <td class="text-center">{{ $item['type'] }}</td>
                                    <td class="text-left">{{ $item['endpoint'] }}</td>
                                </tr>
                            @empty
                                <x-molecules.table-not-found colspan="4" title="Maaf, Data Tidak Ditemukan / Belum Ada Data." description="Silahkan masukkan kata kunci yang sesuai / tambahkan data terlebih dahulu." />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center px-3 pb-3">
                    <div>
                        <span>Menampilkan <b>{{ count($data_api) }}</b> data.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>