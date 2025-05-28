<?php

namespace App\Repositories;

use App\Interfaces\ProvinceInterface;
use App\Models\Province;

use Illuminate\Support\Facades\DB;

class ProvinceRepository implements ProvinceInterface
{
    private $province;

    public function __construct(Province $province)
    {
        $this->province = $province;
    }

    public function list($request)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = Province::query();
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
        return $this->province->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->province->create([
                'region_code' => $data['region_code'] ?? null,
                'name' => $data['name'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'bps' => $data['bps'] ?? null,
                'dagri' => $data['dagri'] ?? null,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $this->province->find($id)->update([
                'region_code' => $data['region_code'],
                'name' => $data['name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'bps' => $data['bps'],
                'dagri' => $data['dagri'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->province->find($id)->delete();
    }

    public function all()
    {
        return $this->province->all();
    }
}
