<?php

namespace App\Repositories;

use App\Interfaces\QuestionnaireQuestionInterface;
use App\Models\QuestionnaireQuestion;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuestionnaireQuestionRepository implements QuestionnaireQuestionInterface
{
    private $questionnaire_question;

    public function __construct(QuestionnaireQuestion $questionnaire_question)
    {
        $this->questionnaire_question = $questionnaire_question;
    }

    public function list($request, $questionnaire_id, array $relations = [])
    {
        $paginate = $request['paginate'] ?? true;
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';
        $questionnaire_category_id = $request['questionnaire_category_id'] ?? null;

        $qr = QuestionnaireQuestion::query();
        if (!empty($relations)) {
            $qr->with($relations);
        }
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhereRelation('questionnaire_category', 'name', 'like', '%'.$search.'%');
            });
        })
        ->when($questionnaire_category_id, function ($query) use ($questionnaire_category_id) {
            return $query->where('questionnaire_category_id', $questionnaire_category_id);
        });
        $data = $qr->where('questionnaire_id', $questionnaire_id)->orderBy('created_at', 'desc'); 
        if ($paginate) {
            $data = $qr->paginate($per_page);
        }
        else {
            $data = $qr->get();
        }
        
        return $data;
    }

    public function detail($id)
    {
        return $this->questionnaire_question->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->questionnaire_question->create([
                'questionnaire_id' => $data['questionnaire_id'],
                'questionnaire_category_id' => $data['questionnaire_category_id'],
                'name' => $data['name'],
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
            $this->questionnaire_question->find($id)->update([
                'questionnaire_category_id' => $data['questionnaire_category_id'],
                'name' => $data['name'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->questionnaire_question->find($id)->delete();
    }
}
