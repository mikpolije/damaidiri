<?php

namespace App\Repositories;

use App\Interfaces\QuestionnaireDetailCategoryInterface;
use App\Models\QuestionnaireCategoryDetail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuestionnaireDetailCategoryRepository implements QuestionnaireDetailCategoryInterface
{
    private $questionnaire_detail_category;

    public function __construct(QuestionnaireCategoryDetail $questionnaire_detail_category)
    {
        $this->questionnaire_detail_category = $questionnaire_detail_category;
    }

    public function list($request, $questionnaire_id)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = QuestionnaireCategoryDetail::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhereRelation('questionnaire_category', 'name', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->whereRelation('questionnaire_category', 'questionnaire_id', $questionnaire_id)->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->questionnaire_detail_category->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->questionnaire_detail_category->create([
                'questionnaire_category_id' => $data['questionnaire_category_id'],
                'level' => $data['level'],
                'minimum_score' => $data['minimum_score'],
                'maximum_score' => $data['maximum_score'],
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
            $this->questionnaire_detail_category->find($id)->update([
                'questionnaire_category_id' => $data['questionnaire_category_id'],
                'level' => $data['level'],
                'minimum_score' => $data['minimum_score'],
                'maximum_score' => $data['maximum_score'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->questionnaire_detail_category->find($id)->delete();
    }

    public function find_by_range($questionnaire_category_id, $score)
    {
        return $this->questionnaire_detail_category->with('questionnaire_category')
                ->where('questionnaire_category_id', $questionnaire_category_id)
                ->where('minimum_score', '<=', $score)
                ->where('maximum_score', '>=', $score)
                ->latest()
                ->first();
    }

    public function count_by_questionnaire($questionnaire_id)
    {
        return $this->questionnaire_detail_category->whereRelation('questionnaire_category', 'questionnaire_id', $questionnaire_id)->count();
    }
}
