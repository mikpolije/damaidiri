<?php

namespace App\Repositories;

use App\Interfaces\JournalQuestionInterface;
use App\Models\JournalQuestion;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JournalQuestionRepository implements JournalQuestionInterface
{
    private $journal_question;

    public function __construct(JournalQuestion $journal_question)
    {
        $this->journal_question = $journal_question;
    }

    public function list($request, $journal_id)
    {
        $paginate = $request['paginate'] ?? true;
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = JournalQuestion::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%');
            });
        })
        ->where('journal_id', $journal_id)
        ->orderBy('created_at'); 
        if ($paginate == 'true') {
            return $qr->paginate($per_page);
        }
        return $qr->get();
    }

    public function detail($id)
    {
        return $this->journal_question->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->journal_question->create([
                'journal_id' => $data['journal_id'],
                'name' => $data['name'],
                'placeholder' => $data['placeholder'],
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
            $this->journal_question->find($id)->update([
                'name' => $data['name'],
                'placeholder' => $data['placeholder'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->journal_question->find($id)->delete();
    }
}
