<?php

namespace App\Repositories;

use App\Interfaces\PartnerInterface;
use App\Models\Partner;

use Illuminate\Support\Facades\DB;

class PartnerRepository implements PartnerInterface
{
    private $partner;

    public function __construct(Partner $partner)
    {
        $this->partner = $partner;
    }

    public function list($request, array $relations = [])
    {
        $paginate = isset($request['paginate']) ? $request['paginate'] == '1' : true;
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';
        $regency_id = $request['regency_id'] ?? null;

        $qr = Partner::query();
        if (!(empty($relations))) {
            $qr->with($relations);
        }
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%');
            });
        })
        ->when($regency_id, function ($query) use ($regency_id) {
            return $query->where('regency_id', $regency_id);
        })
        ->orderBy('created_at', 'desc');
        $data = $paginate ? $qr->paginate($per_page) : $qr->get(); 

        return $data;
    }

    public function detail($id, array $relations = [])
    {
        if (!(empty($relations))) {
            return $this->partner->with($relations)->find($id);
        }
        return $this->partner->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->partner->create([
                'name' => $data['name'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'address' => $data['address'] ?? null,
                'google_maps_url' => $data['google_maps_url'] ?? null,
                'regency_id' => $data['regency_id'] ?? null,
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
            $this->partner->find($id)->update([
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'google_maps_url' => $data['google_maps_url'],
                'regency_id' => $data['regency_id'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->partner->find($id)->delete();
    }

    public function all()
    {
        return $this->partner->all();
    }
}
