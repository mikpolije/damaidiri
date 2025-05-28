<x-app-layout>
    <x-slot name="page_title"> Rincian Master Tes </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Master Tes'</b> berfungsi untuk melihat data master Tes secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_questionnaire')
            <x-atoms.pure-button-redirect route="master-questionnaire.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('update_questionnaire')
            <x-atoms.pure-button-redirect-parameter route="{{ route('master-questionnaire.edit', $detail_questionnaire->id) }}" class="btn-primary" icon="draw" label="Ubah" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Master Tes', 'url' => route('master-questionnaire.list')],
            ['name' => 'Rincian Master Tes', 'url' => route('master-questionnaire.detail', $detail_questionnaire->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Tes</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_questionnaire->title }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Deskripsi</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light" style="text-align: justify;">{{ $detail_questionnaire->description }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Total Kategori</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light" style="text-align: justify;">{{ $detail_questionnaire->questionnaire_category->count() }} Kategori</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Total Pertanyaan</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light" style="text-align: justify;">{{ $detail_questionnaire->questionnaire_question->count() }} Pertanyaan</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Total Jawaban</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light" style="text-align: justify;">{{ $detail_questionnaire->questionnaire_answer->count() }} Jawaban</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Ditambahkan Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_questionnaire->created_at }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Diperbarui Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_questionnaire->updated_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>