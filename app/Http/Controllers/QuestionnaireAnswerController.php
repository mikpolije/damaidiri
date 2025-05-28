<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\QuestionnaireAnswerInterface;
use App\Interfaces\QuestionnaireInterface;

class QuestionnaireAnswerController extends Controller
{
    private $param;
    private $questionnaire_answer, $questionnaire;

    public function __construct(QuestionnaireAnswerInterface $questionnaire_answer, QuestionnaireInterface $questionnaire)
    {
        $this->questionnaire_answer = $questionnaire_answer;
        $this->questionnaire = $questionnaire;
    }

    public function list(Request $request, $id) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_questionnaire_answer'] = $this->questionnaire_answer->list($request->all(), $id);

        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);

        return view('pages.questionnaire-answer.list', $this->param);
    }

    public function store(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'score' => 'required|numeric',
        ], [
            'required' => ':attribute harus diisi',
        ], [
            'name' => 'Jawaban',
            'score' => 'Bobot',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire_answer->store([
                'questionnaire_id' => $id,
                'name' => $request->name,
                'score' => $request->score,
            ]);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-answer.list', $id)->with('success', 'Data berhasil disimpan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function edit($id, $answer_id) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);
        $this->param['detail_questionnaire_answer'] = $this->questionnaire_answer->detail($answer_id);

        return view('pages.questionnaire-answer.edit', $this->param);
    }

    public function update(Request $request, $id, $answer_id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'score' => 'required|numeric',
        ], [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
        ], [
            'name' => 'Nama Kategori',
            'score' => 'Bobot',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire_answer->update($answer_id, [
                'name' => $request->name,
                'score' => $request->score,
            ]);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-answer.list', $id)->with('success', 'Data berhasil diubah');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }


    public function destroy($id, $answer_id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->questionnaire_answer->destroy($answer_id);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-answer.list', $id)->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
