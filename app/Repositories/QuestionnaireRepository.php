<?php

namespace App\Repositories;

use App\Interfaces\QuestionnaireInterface;
use App\Models\Questionnaire;

use Illuminate\Support\Facades\DB;

class QuestionnaireRepository implements QuestionnaireInterface
{
    private $questionnaire;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function list($request, array $relations = [])
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = Questionnaire::query();
        if (!empty($relations)) {
            $qr->with($relations);
        }
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('title', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->questionnaire->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->questionnaire->create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'],
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
            $this->questionnaire->find($id)->update([
                'title' => $data['title'],
                'description' => $data['description'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->questionnaire->find($id)->delete();
    }

    public function all()
    {
        return $this->questionnaire->all();
    }
}
