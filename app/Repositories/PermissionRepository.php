<?php

namespace App\Repositories;

use App\Interfaces\PermissionInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionInterface
{
    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function list($request)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = Permission::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%'); 
            });
        });
        $data = $qr->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->permission->find($id);
    }

    public function store($data)
    {
        return $this->permission->create($data);
    }

    public function update($id, $data)
    {
        return $this->permission->find($id)->update($data);
    }

    public function destroy($id)
    {
        return $this->permission->find($id)->delete();
    }

    public function all()
    {
        return $this->permission->all();
    }
}
