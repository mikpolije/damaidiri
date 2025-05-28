<x-app-layout>
    @push('css-internal')
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    @endpush
    <x-slot name="page_title"> Ubah Mitra </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Ubah Mitra'</b> berfungsi untuk memperbarui data mitra pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_partner')
            <x-atoms.pure-button-redirect route="master-partner.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Mitra', 'url' => route('master-partner.list')],
            ['name' => 'Ubah Mitra', 'url' => route('master-partner.edit', $detail_partner->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1" action="{{ route('master-partner.update', $detail_partner->id) }}">
            @method('PUT')
            @csrf
            <div class="col-md-6">
                <x-atoms.form-input id="name" label="Nama Mitra" required="true" type="text" name="name" value="{{ $detail_partner->name }}" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="phone_number">Nomor Telepon <span class="text-danger">*</span> :</label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') border border-2 border-danger @enderror" placeholder="Masukkan Nomor Telepon" aria-label="Nomor Telepon" aria-describedby="phone_number" value="{{ old('phone_number', substr($detail_partner->phone_number, 2)) }}">
                </div>
                @error('phone_number')
                    <div id="passwordHelpBlock" class="form-text text-danger">
                        {{ $message }}.
                    </div>
                @enderror
            </div>
            <div class="col-md-6">
                <x-atoms.form-input id="address" label="Alamat Lengkap" required="true" type="text" name="address" value="{{ $detail_partner->address }}" />
            </div>
            <div class="col-md-6">
                <x-atoms.form-input id="google_maps_url" label="Tautan Google Maps" required="true" type="url" name="google_maps_url" value="{{ $detail_partner->google_maps_url }}" />
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold" for="regency">Kabupaten / Kota <span class="text-danger">*</span> :</label>
                <select class="js-states form-control select2 @error('regency') border border-2 border-danger @enderror" tabindex="-1" style="display: none; width: 100%" name="regency">
                    <option value="" disabled selected>Pilih Kabupaten / Kota</option>
                    @foreach ($data_regency as $item)
                        <option value="{{ $item->id }}" {{ old('regency', $detail_partner->regency_id) == $item->id ? 'selected' : '' }}>({{ $item->province->name }}) - {{ $item->name }}</option>
                    @endforeach
                </select>
                @error('regency')
                    <div id="passwordHelpBlock" class="form-text text-danger">
                        {{ $message }}.
                    </div>
                @enderror
            </div>
            @can('create_partner')
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                </div>
            @endcan
        </form>
    </div>
    @push('js-internal')
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function(e) {
                function handleNumericInput(inputElement) {
                    inputElement.addEventListener('input', function(e) {
                        var value = inputElement.value;
                        inputElement.value = value.replace(/\D/g, '');
                    });
                }
                
                handleNumericInput(phone_number);
            });
        </script>
    @endpush
</x-app-layout>