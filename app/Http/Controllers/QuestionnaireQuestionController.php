<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\QuestionnaireCategoryInterface;
use App\Interfaces\QuestionnaireQuestionInterface;
use App\Interfaces\QuestionnaireInterface;


class QuestionnaireQuestionController extends Controller
{
    private $param;
    private $questionnaire_question, $questionnaire, $questionnaire_category;

    public function __construct(QuestionnaireQuestionInterface $questionnaire_question, QuestionnaireInterface $questionnaire, QuestionnaireCategoryInterface $questionnaire_category)
    {
        $this->questionnaire_question = $questionnaire_question;
        $this->questionnaire = $questionnaire;
        $this->questionnaire_category = $questionnaire_category;
    }

    public function list(Request $request, $id) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_questionnaire_question'] = $this->questionnaire_question->list($request->all(), $id);

        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);
        $this->param['data_questionnaire_category'] = $this->questionnaire_category->all_by_questionnaire($id);

        return view('pages.questionnaire-question.list', $this->param);
    }

    public function store(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'questionnaire_category_id' => 'required',
        ], [
            'required' => ':attribute harus diisi',
        ], [
            'name' => 'Pertanyaan',
            'questionnaire_category_id' => 'Kategori Tes',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire_question->store([
                'questionnaire_id' => $id,
                'name' => $request->name,
                'questionnaire_category_id' => $request->questionnaire_category_id,
            ]);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-question.list', $id)->with('success', 'Data berhasil disimpan');
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
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);
        $this->param['detail_questionnaire_question'] = $this->questionnaire_question->detail($question_id);
        $this->param['data_questionnaire_category'] = $this->questionnaire_category->all_by_questionnaire($id);

        return view('pages.questionnaire-question.edit', $this->param);
    }

    public function update(Request $request, $id, $question_id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'questionnaire_category_id' => 'required',
        ], [
            'required' => ':attribute harus diisi',
        ], [
            'name' => 'Nama Kategori',
            'questionnaire_category_id' => 'Kategori Tes',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire_question->update($question_id, [
                'name' => $request->name,
                'questionnaire_category_id' => $request->questionnaire_category_id,
            ]);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-question.list', $id)->with('success', 'Data berhasil diubah');
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
            $this->questionnaire_question->destroy($question_id);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-question.list', $id)->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
