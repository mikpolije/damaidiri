<x-app-layout>
    <x-slot name="page_title">Rincian Riwayat Jurnal</x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Riwayat Jurnal'</b> berfungsi untuk melihat data riwayat jurnal secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_history_response')
            <x-atoms.pure-button-redirect route="history-response.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Riwayat Jurnal', 'url' => route('history-response.list')],
            ['name' => 'Rincian Riwayat Jurnal.', 'url' => route('history-response.detail', [$detail_journal->id, $detail_response->id])],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-5">
            <div class="col-md-12">
                <h1 style="font-weight: bold; color: #27313F; margin: 0; font-size: 24px;">Hasil Jurnal</h1>
                <hr />
            </div>

            <div class="col-md-12">
                <div class="table-responsive mt-3" id="content_detail">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Topik Jurnal</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_journal->topic }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Tujuan</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_journal->purpose }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Pasien</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_response->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Tanggal Pengisian</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ \Carbon\Carbon::parse($detail_response->created_at)->translatedFormat('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <hr />
                @forelse ($detail_journal->journal_question as $item_question)
                    <div class="col-md-12 mb-3">
                        <x-atoms.pure-label label="{{ $loop->iteration }}. {{ $item_question->name }}" required="true" />
                        <textarea name="answer_question_{{ $item_question->id }}" id="answer_question_{{ $item_question->id }}" rows="5" class="form-control form-control-solid-bordered @error('answer_question_' . $item_question->id) border border-2 border-danger @enderror" placeholder="Masukkan Jawaban..." readonly>{{ $detail_response->response_detail->where('journal_question_id', $item_question->id)->first()->answer }}</textarea>
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
            </div>
        </div>
    </div>
</x-app-layout>