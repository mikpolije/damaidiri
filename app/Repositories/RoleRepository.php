<?php

namespace App\Repositories;

use App\Interfaces\RoleInterface;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleInterface
{
    private $permission;
    private $role;

    public function __construct(Permission $permission, Role $role)
    {
        $this->permission = $permission;
        $this->role = $role;
    }

    public function list($request)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = Role::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('guard_name', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->role->find($id);
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $role = $this->role->find($id);
            $role->syncPermissions($data['permission']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function all()
    {
        return $this->role->orderBy('created_at', 'desc')->get();
    }
}