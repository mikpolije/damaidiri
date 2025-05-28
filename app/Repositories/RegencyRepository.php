<?php

namespace App\Repositories;

use App\Interfaces\RegencyInterface;
use App\Models\Regency;

use Illuminate\Support\Facades\DB;

class RegencyRepository implements RegencyInterface
{
    private $regency;

    public function __construct(Regency $regency)
    {
        $this->regency = $regency;
    }

    public function list($request, array $relations = [])
    {
        $paginate = isset($request['paginate']) ? $request['paginate'] == '1' : true;
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = Regency::query();
        if (!(empty($relations))) {
            $qr->with($relations);
        }
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%');
            });
        })->orderBy('created_at', 'desc');
        $data = $paginate ? $qr->paginate($per_page) : $qr->get();
        
        return $data;
    }

    public function detail($id)
    {
        return $this->regency->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->regency->create([
                'region_code' => $data['region_code'] ?? null,
                'name' => $data['name'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'bps' => $data['bps'] ?? null,
                'dagri' => $data['dagri'] ?? null,
                'province_id' => $data['province_id'] ?? null,
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
            $this->regency->find($id)->update([
                'region_code' => $data['region_code'],
                'name' => $data['name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'bps' => $data['bps'],
                'dagri' => $data['dagri'],
                'province_id' => $data['province_id'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->regency->find($id)->delete();
    }

    public function all()
    {
        return $this->regency->all();
    }
}
