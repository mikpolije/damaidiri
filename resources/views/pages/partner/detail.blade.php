<x-app-layout>
    <x-slot name="page_title"> Rincian Mitra </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Mitra'</b> berfungsi untuk melihat data mitra secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_partner')
            <x-atoms.pure-button-redirect route="master-partner.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('update_partner')
            <x-atoms.pure-button-redirect-parameter route="{{ route('master-partner.edit', $detail_partner->id) }}" class="btn-primary" icon="draw" label="Ubah" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Mitra', 'url' => route('master-partner.list')],
            ['name' => 'Rincian Mitra', 'url' => route('master-partner.detail', $detail_partner->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Kategori</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_partner->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nomor Telepon</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ "+".$detail_partner->phone_number }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Alamat</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_partner->address }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Kabupaten / Kota</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">({{ $detail_partner->regency->province->name }}) {{ $detail_partner->regency->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Google Map</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">
                                <a href="{{ $detail_partner->google_maps_url }}" target="_blank">
                                    {{ $detail_partner->google_maps_url }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Ditambahkan Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_partner->created_at }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Diperbarui Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_partner->updated_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>