<?php

namespace App\Repositories;

use App\Interfaces\QuestionnaireCategoryInterface;
use App\Models\QuestionnaireCategory;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuestionnaireCategoryRepository implements QuestionnaireCategoryInterface
{
    private $questionnaire_category;

    public function __construct(QuestionnaireCategory $questionnaire_category)
    {
        $this->questionnaire_category = $questionnaire_category;
    }

    public function list($request, $questionnaire_id, array $relations = [])
    {
        $paginate = $request['paginate'] ?? true;
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = QuestionnaireCategory::query();
        if (!empty($relations)) {
            $qr->with($relations);
        }

        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->where('questionnaire_id', $questionnaire_id)->orderBy('created_at', 'desc'); 
        if ($paginate) {
            $data = $data->paginate($per_page);
        }
        else {
            $data = $data->get();
        }
        
        return $data;
    }

    public function detail($id)
    {
        return $this->questionnaire_category->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->questionnaire_category->create([
                'questionnaire_id' => $data['questionnaire_id'],
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
            $this->questionnaire_category->find($id)->update([
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
        return $this->questionnaire_category->find($id)->delete();
    }

    public function all_by_questionnaire($questionnaire_id, array $relations = [])
    {
        return $this->questionnaire_category
                ->when($relations, function ($query) use ($relations) {
                    if (count($relations) > 0) {
                        $query->with($relations);
                    }
                })
                ->where('questionnaire_id', $questionnaire_id)
                ->orderBy('created_at', 'desc')->get();
    }
}
