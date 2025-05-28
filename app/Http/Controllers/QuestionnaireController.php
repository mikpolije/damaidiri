<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\QuestionnaireDetailCategoryInterface;
use App\Interfaces\QuestionnaireInterface;

class QuestionnaireController extends Controller
{
    private $param;
    private $questionnaire, $questionnaire_category_detail;

    public function __construct(QuestionnaireInterface $questionnaire, QuestionnaireDetailCategoryInterface $questionnaire_category_detail)
    {
        $this->questionnaire = $questionnaire;
        $this->questionnaire_category_detail = $questionnaire_category_detail;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_questionnaire'] = $this->questionnaire->list($request->all());

        return view('pages.questionnaire.list', $this->param);
    }

    public function create() : View
    {
        return view('pages.questionnaire.add');
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
           'title' => 'required|unique:questionnaires,title',
           'description' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'title' => 'Nama Tes',
            'description' => 'Deskripsi',
        ]);

        DB::beginTransaction();
        try {
            $request->merge(['slug' => Str::slug($request->title)]);
            $this->questionnaire->store($request->all());

            DB::commit();
            return redirect()->route('master-questionnaire.list')->with('success', 'Data berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function detail($id) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);

        return view('pages.questionnaire.detail', $this->param);
    }

    public function edit($id) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);

        return view('pages.questionnaire.edit', $this->param);
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'title' => 'required|unique:questionnaires,title,'.$id,
            'description' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'title' => 'Nama Tes',
            'description' => 'Deskripsi',
        ]);

        DB::beginTransaction();
        try {
            $this->questionnaire->update($id, $request->all());

            DB::commit();
            return redirect()->route('master-questionnaire.list')->with('success', 'Data berhasil diperbarui');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function destroy($id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->questionnaire->destroy($id);

            DB::commit();
            return redirect()->route('master-questionnaire.list')->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function config($id) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);
        $this->param['count_detail_category'] = $this->questionnaire_category_detail->count_by_questionnaire($id);

        return view('pages.questionnaire.config', $this->param);
    }
}
