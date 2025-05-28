<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\QuestionnaireCategoryInterface;
use App\Interfaces\QuestionnaireInterface;

class QuestionnaireCategoryController extends Controller
{
    private $param;
    private $questionnaire_category, $questionnaire;

    public function __construct(QuestionnaireCategoryInterface $questionnaire_category, QuestionnaireInterface $questionnaire)
    {
        $this->questionnaire_category = $questionnaire_category;
        $this->questionnaire = $questionnaire;
    }

    public function list(Request $request, $id) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_questionnaire_category'] = $this->questionnaire_category->list($request->all(), $id);

        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);

        return view('pages.questionnaire-category.list', $this->param);
    }

    public function store(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ], [
            'required' => ':attribute harus diisi',
        ], [
            'name' => 'Nama Kategori',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire_category->store([
                'questionnaire_id' => $id,
                'name' => $request->name,
            ]);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-category.list', $id)->with('success', 'Data berhasil disimpan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function edit($id, $category_id) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);
        $this->param['detail_questionnaire_category'] = $this->questionnaire_category->detail($category_id);

        return view('pages.questionnaire-category.edit', $this->param);
    }

    public function update(Request $request, $id, $category_id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ], [
            'required' => ':attribute harus diisi',
        ], [
            'name' => 'Nama Kategori',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire_category->update($category_id, [
                'name' => $request->name,
            ]);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-category.list', $id)->with('success', 'Data berhasil diubah');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }


    public function destroy($id, $category_id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->questionnaire_category->destroy($category_id);

            DB::commit();
            return redirect()->route('master-questionnaire.questionnaire-category.list', $id)->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
