<?php

namespace App\Repositories;

use App\Interfaces\QuestionnaireAnswerInterface;
use App\Models\QuestionnaireAnswer;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuestionnaireAnswerRepository implements QuestionnaireAnswerInterface
{
    private $questionnaire_answer;

    public function __construct(QuestionnaireAnswer $questionnaire_answer)
    {
        $this->questionnaire_answer = $questionnaire_answer;
    }

    public function list($request, $questionnaire_id)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = QuestionnaireAnswer::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('score', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->where('questionnaire_id', $questionnaire_id)->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->questionnaire_answer->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->questionnaire_answer->create([
                'questionnaire_id' => $data['questionnaire_id'],
                'name' => $data['name'],
                'score' => $data['score'],
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
            $this->questionnaire_answer->find($id)->update([
                'name' => $data['name'],
                'score' => $data['score'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->questionnaire_answer->find($id)->delete();
    }

    public function all_by_questionnaire($questionnaire_id)
    {
        return $this->questionnaire_answer
            ->where('questionnaire_id', $questionnaire_id)
            ->get();
    }
}
