<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\JournalQuestionInterface;
use App\Interfaces\JournalInterface;

class JournalQuestionController extends Controller
{
    private $param;
    private $journal_question, $journal;

    public function __construct(JournalQuestionInterface $journal_question, JournalInterface $journal)
    {
        $this->journal_question = $journal_question;
        $this->journal = $journal;
    }

    public function list(Request $request, $id) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_journal_question'] = $this->journal_question->list($request->all(), $id);

        $this->param['detail_journal'] = $this->journal->detail($id);

        return view('pages.journal-question.list', $this->param);
    }

    public function store(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'placeholder' => 'required',
        ], [
            'required' => ':attribute harus diisi',
        ], [
            'name' => 'Pertanyaan',
            'placeholder' => 'Contoh Isian',
        ]);

        DB::beginTransaction();
        try {
            $this->journal_question->store([
                'journal_id' => $id,
                'name' => $request->name,
                'placeholder' => $request->placeholder,
            ]);

            DB::commit();
            return redirect()->route('master-journal.journal-question.list', $id)->with('success', 'Data berhasil disimpan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function edit($id, $question_id) : View
    {
        $this->param['detail_journal'] = $this->journal->detail($id);
        $this->param['detail_journal_question'] = $this->journal_question->detail($question_id);

        return view('pages.journal-question.edit', $this->param);
    }

    public function update(Request $request, $id, $question_id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'placeholder' => 'required',
        ], [
            'required' => ':attribute harus diisi',
        ], [
            'name' => 'Nama Kategori',
            'placeholder' => 'Contoh Isian',
        ]);

        DB::beginTransaction();
        try {
            $this->journal_question->update($question_id, [
                'name' => $request->name,
                'placeholder' => $request->placeholder,
            ]);

            DB::commit();
            return redirect()->route('master-journal.journal-question.list', $id)->with('success', 'Data berhasil diubah');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function destroy($id, $question_id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->journal_question->destroy($question_id);

            DB::commit();
            return redirect()->route('master-journal.journal-question.list', $id)->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
