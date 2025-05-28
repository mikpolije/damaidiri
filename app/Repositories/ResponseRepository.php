<?php

namespace App\Repositories;

use App\Interfaces\ResponseInterface;
use App\Models\Response;

use Illuminate\Support\Facades\DB;

class ResponseRepository implements ResponseInterface
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function list($request, $journal_id)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = Response::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->whereRelation('user', 'name', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->where('journal_id', $journal_id)->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function list_by_user($request, $user_id, array $relations = [])
    {
        $paginate = isset($request['paginate']) ? $request['paginate'] == '1' : true;
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';
        $date = $request['date'] ?? null;

        $qr = Response::query();
        if (!empty($relations)) {
            $qr->with($relations);
        }
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('response_code', 'like', '%'.$search.'%')
                         ->orWhereRelation('journal', 'topic', 'like', '%'.$search.'%');
            });
        })
        ->when($date, function ($query) use ($date) {
            return $query->whereYear('created_at', date('Y', strtotime($date)))
                ->whereMonth('created_at', date('m', strtotime($date)));
        })
        ->where('user_id', $user_id)->orderBy('created_at', 'desc');

        $data = $paginate ? $qr->paginate($per_page) : $qr->get(); 
        
        return $data;
    }

    public function detail($id, array $relations = [])
    {
        return $this->response->with($relations)->find($id);
    }

    public function detail_by_code($code)
    {
        return $this->response->where('response_code', $code)->first();
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $data_response = $this->response->create([
                'user_id' => $data['user_id'] ?? auth()->user()->id,
                'response_code' => $data['response_code'] ?? generate_response_code(),
                'journal_id' => $data['journal_id'] ?? '-',
                'is_simulate' => $data['is_simulate'] ?? 0,
            ]);

            DB::commit();
            return $data_response;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function all_by_user($user_id)
    {
        return $this->response->where('user_id', $user_id)->get();
    }
}
