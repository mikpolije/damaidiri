<x-app-layout>
    @push('css-internal')
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    @endpush

    <x-slot name="page_title"> Konfigurasi Tes </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Konfigurasi Tes'</b> berfungsi untuk melakukan konfigurasi (melihat statistik, mengelola kategori, mengelola pertanyaan dan mengelola jawaban) pada Tes di dalam sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_questionnaire')
            <x-atoms.pure-button-redirect route="master-questionnaire.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Master Tes', 'url' => route('master-questionnaire.list')],
            ['name' => 'Konfigurasi Tes.', 'url' => route('master-questionnaire.config', $detail_questionnaire->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-5">
            <div class="col-md-12">
                <x-molecules.tabulation :tabs="[
                    ['label' => 'Statistik', 'permission' => 'config_questionnaire', 'route_active' => 'master-questionnaire.config', 'route' => route('master-questionnaire.config', $detail_questionnaire->id)],
                    ['label' => 'Kategori Tes', 'permission' => 'list_questionnaire_category', 'route_active' => 'master-questionnaire.questionnaire-category', 'route' => route('master-questionnaire.questionnaire-category.list', $detail_questionnaire->id)],
                    ['label' => 'Rincian Kategori Tes', 'permission' => 'list_questionnaire_detail_category', 'route_active' => 'master-questionnaire.questionnaire-detail-category', 'route' => route('master-questionnaire.questionnaire-detail-category.list', $detail_questionnaire->id)],
                    ['label' => 'Pertanyaan Tes', 'permission' => 'list_questionnaire_question', 'route_active' => 'master-questionnaire.questionnaire-question', 'route' => route('master-questionnaire.questionnaire-question.list', $detail_questionnaire->id)],
                    ['label' => 'Jawaban Tes', 'permission' => 'list_questionnaire_answer', 'route_active' => 'master-questionnaire.questionnaire-answer', 'route' => route('master-questionnaire.questionnaire-answer.list', $detail_questionnaire->id)],
                    ['label' => 'Histori Tes', 'permission' => 'list_questionnaire_history', 'route_active' => 'master-questionnaire.questionnaire-history', 'route' => route('master-questionnaire.questionnaire-history.list', $detail_questionnaire->id)],
                ]" />
            </div>
        </div>
    </div>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="px-3 pb-3">
                    <form method="POST" class="row g-3 mt-1 align-items-center" action="{{ route('master-questionnaire.questionnaire-detail-category.update', [$detail_questionnaire->id, $detail_questionnaire_detail_category->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="col-md-12">
                            <x-atoms.form-select-option id="questionnaire_category_id" name="questionnaire_category_id" label="Kategori Tes" class="select2" :options="array_merge(
                                $data_questionnaire_category->map(function($item) use ($detail_questionnaire_detail_category) {
                                    return [
                                        'value' => $item->id, 
                                        'label' => $item->name, 
                                        'config' => old('questionnaire_category_id', $detail_questionnaire_detail_category->questionnaire_category_id) == $item->id ? 'selected' : ''
                                    ];
                                })->toArray()
                            )" />
                        </div>
                        <div class="col-md-12">
                            <x-atoms.form-input id="level" label="Skala" required="true" type="text" name="level" value="{{ $detail_questionnaire_detail_category->level }}" />
                        </div>
                        <div class="col-md-12">
                            <x-atoms.form-input id="minimum_score" label="Bobot Minimum" required="true" type="text" name="minimum_score" value="{{ $detail_questionnaire_detail_category->minimum_score }}" />
                        </div>
                        <div class="col-md-12">
                            <x-atoms.form-input id="maximum_score" label="Bobot Maximum" required="true" type="text" name="maximum_score" value="{{ $detail_questionnaire_detail_category->maximum_score }}" />
                        </div>
                        @can('create_questionnaire_detail_category')
                        <div class="col-md-12 text-end mt-4">
                            <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                        </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js-internal')
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>
    @endpush
</x-app-layout>