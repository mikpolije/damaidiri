<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\QuestionnaireDetailCategoryInterface;
use App\Interfaces\QuestionnaireCategoryInterface;
use App\Interfaces\QuestionnaireInterface;

class QuestionnaireDetailCategoryController extends Controller
{
    private $param;
    private $questionnaire_detail_category, $questionnaire, $questionnaire_category;

    public function __construct(QuestionnaireDetailCategoryInterface $questionnaire_detail_category, QuestionnaireInterface $questionnaire, QuestionnaireCategoryInterface $questionnaire_category)
    {
        $this->questionnaire_detail_category = $questionnaire_detail_category;
        $this->questionnaire = $questionnaire;
        $this->questionnaire_category = $questionnaire_category;
    }

    public function list(Request $request, $id) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_questionnaire_detail_category'] = $this->questionnaire_detail_category->list($request->all(), $id);

        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);
        $this->param['data_questionnaire_category'] = $this->questionnaire_category->all_by_questionnaire($id);

        return view('pages.questionnaire-detail-category.list', $this->param);
    }

    public function store(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'questionnaire_category_id' => 'required',
            'level' => 'required',
            'minimum_score' => 'required|numeric',
            'maximum_score' => 'nullable|numeric',
        ], [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
        ], [
            'questionnaire_category_id' => 'Kategori Tes',
            'level' => 'Skala',
            'minimum_score' => 'Bobot Minimal',
            'maximum_score' => 'Bobot Maksimal',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire_detail_category->store([
                'questionnaire_category_id' => $request->questionnaire_category_id,
                'level' => $request->level,
                'minimum_score' => $request->minimum_score,
                'maximum_score' => $request->maximum_score,
            ]);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-detail-category.list', $id)->with('success', 'Data berhasil disimpan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function edit($id, $detail_category_id) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);
        $this->param['detail_questionnaire_detail_category'] = $this->questionnaire_detail_category->detail($detail_category_id);
        $this->param['data_questionnaire_category'] = $this->questionnaire_category->all_by_questionnaire($id);

        return view('pages.questionnaire-detail-category.edit', $this->param);
    }

    public function update(Request $request, $id, $detail_category_id) : RedirectResponse
    {
        $request->validate([
            'questionnaire_category_id' => 'required',
            'level' => 'required',
            'minimum_score' => 'required|numeric',
            'maximum_score' => 'nullable|numeric',
        ], [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
        ], [
            'questionnaire_category_id' => 'Kategori Tes',
            'level' => 'Skala',
            'minimum_score' => 'Bobot Minimal',
            'maximum_score' => 'Bobot Maksimal',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire_detail_category->update($detail_category_id, [
                'questionnaire_category_id' => $request->questionnaire_category_id,
                'level' => $request->level,
                'minimum_score' => $request->minimum_score,
                'maximum_score' => $request->maximum_score,
            ]);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-detail-category.list', $id)->with('success', 'Data berhasil diubah');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function destroy($id, $detail_category_id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->questionnaire_detail_category->destroy($detail_category_id);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-detail-category.list', $id)->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
