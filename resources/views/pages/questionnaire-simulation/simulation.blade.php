<x-app-layout>
    <x-slot name="page_title"> Simulasi Tes - {{ $detail_questionnaire->title }} </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Silahkan lengkapi Tes ({{ $detail_questionnaire->title }}) di bawah ini untuk melakukan simulasi & hasil." />
    </x-slot>
    <x-slot name="page_breadcrumb">
        <div class="table-responsive px-3">
            <table class="table table-bordered">
                <tr>
                    <td class="w-25 fw-bold bg-light text-black">Nama Tes</td>
                    <td class="fw-bold text-center bg-light">:</td>
                    <td class="w-75 bg-light">{{ $detail_questionnaire->title ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="w-25 fw-bold bg-light text-black">Deskripsi Tes</td>
                    <td class="fw-bold text-center bg-light">:</td>
                    <td class="w-75 bg-light">{{ $detail_questionnaire->description ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="w-25 fw-bold bg-light text-black">Total Pertanyaan</td>
                    <td class="fw-bold text-center bg-light">:</td>
                    <td class="w-75 bg-light">{{ $detail_questionnaire->questionnaire_question->count() ?? 0 }} Pertanyaan</td>
                </tr>
            </table>
        </div>
    </x-slot>

    <div class="container-fluid bg-white rounded mt-5 shadow">
        <form method="POST" class="row g-3 p-5" action="{{ route('simulation-questionnaire.store', $detail_questionnaire->id) }}">
            @csrf
            <div class="col-md-12">
                <h1 style="font-weight: bold; color: #27313F; margin: 0; font-size: 24px;">Pertanyaan Tes ({{ $detail_questionnaire->title }})</h1>
                <hr>
            </div>
            @forelse ($detail_questionnaire->questionnaire_question as $item_question)
                <div class="col-md-12">
                    <x-atoms.pure-label label="{{ $loop->iteration }}. {{ $item_question->name }}" required="true" />
                    <div class="row">
                        @forelse ($detail_questionnaire->questionnaire_answer as $item_answer)
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="answer_question_{{ $item_question->id }}" id="answer_question_{{ $item_question->id }}_{{ $item_answer->id }}" value="{{ $item_answer->id }}"  {{ old('answer_question_' . $item_question->id) == $item_answer->id ? 'checked' : '' }} >
                                <label class="form-check-label" for="answer_question_{{ $item_question->id }}_{{ $item_answer->id }}">
                                    {{ $item_answer->name }}
                                </label>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <div class="card file-manager-group">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="material-icons text-success">image</i>
                                        <div class="file-manager-group-info flex-fill">
                                            <a href="#" class="file-manager-group-title">Maaf, Belum Ada Data.</a>
                                            <span class="file-manager-group-about">Silahkan tambahkan data terlebih dahulu.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                        @error('answer_question_' . $item_question->id)
                            <div class="text-danger small mt-1 fw-bold">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="card file-manager-group">
                        <div class="card-body d-flex align-items-center">
                            <i class="material-icons text-success">image</i>
                            <div class="file-manager-group-info flex-fill">
                                <a href="#" class="file-manager-group-title">Maaf, Belum Ada Data.</a>
                                <span class="file-manager-group-about">Silahkan tambahkan data terlebih dahulu.</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse

            @if ($detail_questionnaire->questionnaire_question->count() > 0 && $detail_questionnaire->questionnaire_answer->count() > 0)
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="compare" label="Simulasikan" />
                </div>
            @endif
        </form>
    </div>
</x-app-layout>