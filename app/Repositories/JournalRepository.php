<?php

namespace App\Repositories;

use App\Interfaces\JournalInterface;
use App\Models\Journal;

use Illuminate\Support\Facades\DB;

class JournalRepository implements JournalInterface
{
    private $journal;

    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
    }

    public function list($request)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = Journal::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('topic', 'like', '%'.$search.'%')
                        ->orWhere('purpose', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->journal->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->journal->create([
                'topic' => $data['topic'],
                'purpose' => $data['purpose'],
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
            $this->journal->find($id)->update([
                'topic' => $data['topic'],
                'purpose' => $data['purpose'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->journal->find($id)->delete();
    }

    public function all()
    {
        return $this->journal->all();
    }
}
