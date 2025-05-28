<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Facades\DB;

use App\Interfaces\PermissionInterface;
use App\Interfaces\RoleInterface;

class RoleController extends Controller
{
    private $param;
    private $permission;
    private $role;

    public function __construct(PermissionInterface $permission, RoleInterface $role)
    {
        $this->permission = $permission;
        $this->role = $role;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_role'] = $this->role->list($request->all());

        return view('pages.role.list', $this->param);
    }

    public function detail($id) : View
    {
        $this->param['detail_role'] = $this->role->detail($id);

        return view('pages.role.detail', $this->param);
    }

    public function edit($id) : View
    {
        $this->param['detail_role'] = $this->role->detail($id);
        $this->param['data_permission'] = $this->permission->all();

        return view('pages.role.edit', $this->param);
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'permission' => 'required|array', 
        ], [
            'required' => ':attribute harus diisi',
            'array' => ':attribute harus berupa array',
        ], [
            'name' => 'Hak Akses',
        ]);
 
        DB::beginTransaction();
        try {
            $this->role->update($id, $request->all());

            DB::commit();
            return redirect()->route('role.list')->with('success', 'Data berhasil diperbarui');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
