<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\JournalInterface;

class JournalController extends Controller
{
    private $param;
    private $journal;

    public function __construct(JournalInterface $journal)
    {
        $this->journal = $journal;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_journal'] = $this->journal->list($request->all());

        return view('pages.journal.list', $this->param);
    }

    public function create() : View
    {
        return view('pages.journal.add');
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
           'topic' => 'required|unique:journals,topic',
           'purpose' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'topic' => 'Topik Jurnal',
            'purpose' => 'Tujuan',
        ]);

        DB::beginTransaction();
        try {
            $this->journal->store($request->all());

            DB::commit();
            return redirect()->route('master-journal.list')->with('success', 'Data berhasil ditambahkan');
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
        $this->param['detail_journal'] = $this->journal->detail($id);

        return view('pages.journal.detail', $this->param);
    }

    public function edit($id) : View
    {
        $this->param['detail_journal'] = $this->journal->detail($id);

        return view('pages.journal.edit', $this->param);
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'topic' => 'required|unique:journals,topic,'.$id,
            'purpose' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'topic' => 'Topik Jurnal',
            'purpose' => 'Tujuan',
        ]);

        DB::beginTransaction();
        try {
            $this->journal->update($id, $request->all());

            DB::commit();
            return redirect()->route('master-journal.list')->with('success', 'Data berhasil diperbarui');
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
            $this->journal->destroy($id);

            DB::commit();
            return redirect()->route('master-journal.list')->with('success', 'Data berhasil dihapus');
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
        $this->param['detail_journal'] = $this->journal->detail($id);

        return view('pages.journal.config', $this->param);
    }
}
