<x-app-layout>
    <x-slot name="page_title"> Cek Tes Pasien </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Cek Tes Pasien'</b> berfungsi untuk melakukan pengecekan Tes pasien." />
    </x-slot>
    <x-slot name="page_action">
        @can('check_screening')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalResult">
                <i class="material-icons">pageview</i>
                Cari Kode Tes
            </button>
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Cek Tes', 'url' => route('check-screening.form')],
            ['name' => 'Formulir', 'url' => route('check-screening.form')],
        ]" />
    </x-slot>
    
    <div class="card file-manager-group mt-4 shadow">
        <div class="card-body d-flex align-items-center">
            <i class="material-icons text-primary">image</i>
            <div class="file-manager-group-info flex-fill">
                <a href="#" class="file-manager-group-title">Informasi.</a>
                <span class="file-manager-group-about">Silahkan cari kode Tes terlebih dahulu untuk memunculkan data.</span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalResult" tabindex="-1" aria-labelledby="modalResultTitle" style="display: none;" aria-hidden="true">
        <form method="POST" action="{{ route('check-screening.result') }}" class="modal-dialog modal-dialog-centered">
            <div class="modal-content py-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResultTitle">Formulir Pengecekan Tes.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <x-atoms.form-input id="screening_code" label="Kode Tes" required="true" type="text" name="screening_code" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>