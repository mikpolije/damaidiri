<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Facades\DB;

use App\Interfaces\PermissionInterface;

class PermissionController extends Controller
{
    private $param;
    private $permission;

    public function __construct(PermissionInterface $permission)
    {
        $this->permission = $permission;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_permission'] = $this->permission->list($request->all());

        return view('pages.permission.list', $this->param);
    }

    public function create() : View
    {
        return view('pages.permission.add');
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
           'name' => 'required|unique:permissions,name', 
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Hak Akses',
        ]);

        DB::beginTransaction();
        try {
            $this->permission->store($request->all());

            DB::commit();
            return redirect()->route('permission.list')->with('success', 'Data berhasil ditambahkan');
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
        $this->param['detail_permission'] = $this->permission->detail($id);

        return view('pages.permission.detail', $this->param);
    }

    public function edit($id) : View
    {
        $this->param['detail_permission'] = $this->permission->detail($id);

        return view('pages.permission.edit', $this->param);
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$id, 
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Hak Akses',
        ]);
 
        DB::beginTransaction();
        try {
            $this->permission->update($id, $request->all());

            DB::commit();
            return redirect()->route('permission.list')->with('success', 'Data berhasil diperbarui');
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
            $this->permission->destroy($id);

            DB::commit();
            return redirect()->route('permission.list')->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}