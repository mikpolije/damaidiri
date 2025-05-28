<x-app-layout>
    <x-slot name="page_title"> Simulasi Jurnal - {{ $detail_journal->title }} </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Silahkan lengkapi jurnal ({{ $detail_journal->title }}) di bawah ini untuk melakukan simulasi & hasil." />
    </x-slot>
    <x-slot name="page_breadcrumb">
        <div class="table-responsive px-3">
            <table class="table table-bordered">
                <tr>
                    <td class="w-25 fw-bold bg-light text-black">Topik Jurnal</td>
                    <td class="fw-bold text-center bg-light">:</td>
                    <td class="w-75 bg-light">{{ $detail_journal->topic ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="w-25 fw-bold bg-light text-black">Tujuan Jurnal</td>
                    <td class="fw-bold text-center bg-light">:</td>
                    <td class="w-75 bg-light">{{ $detail_journal->purpose ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="w-25 fw-bold bg-light text-black">Total Pertanyaan</td>
                    <td class="fw-bold text-center bg-light">:</td>
                    <td class="w-75 bg-light">{{ $detail_journal->journal_question->count() ?? 0 }} Pertanyaan</td>
                </tr>
            </table>
        </div>
    </x-slot>

    <div class="container-fluid bg-white rounded mt-5 shadow">
        <form method="POST" class="row g-3 p-5" action="{{ route('simulation-journal.store', $detail_journal->id) }}">
            @csrf
            <div class="col-md-12">
                <h1 style="font-weight: bold; color: #27313F; margin: 0; font-size: 24px;">Pertanyaan Jurnal ({{ $detail_journal->topic }})</h1>
                <hr>
            </div>
            @forelse ($detail_journal->journal_question as $item_question)
                <div class="col-md-12">
                    <x-atoms.pure-label label="{{ $loop->iteration }}. {{ $item_question->name }}" required="true" />
                    <textarea name="answer_question_{{ $item_question->id }}" id="answer_question_{{ $item_question->id }}" rows="5" class="form-control form-control-solid-bordered @error('answer_question_' . $item_question->id) border border-2 border-danger @enderror" placeholder="Contoh : {{ $item_question->placeholder }}.">{{ old('answer_question_' . $item_question->id) }}</textarea>
                    @error('answer_question_' . $item_question->id)
                        <div class="form-text text-danger">
                            {{ $message }}.
                        </div>
                    @enderror
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

            @if ($detail_journal->journal_question->count() > 0)
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="compare" label="Simulasikan" />
                </div>
            @endif
        </form>
    </div>
</x-app-layout>