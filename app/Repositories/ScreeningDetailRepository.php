<?php

namespace App\Repositories;

use App\Interfaces\ScreeningDetailInterface;
use App\Models\ScreeningDetail;

use Illuminate\Support\Facades\DB;

class ScreeningDetailRepository implements ScreeningDetailInterface
{
    private $screening_detail;

    public function __construct(ScreeningDetail $screening_detail)
    {
        $this->screening_detail = $screening_detail;
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $data_screening_detail = $this->screening_detail->create([
                'screening_id' => $data['screening_id'] ?? '-',
                'questionnaire_question_id' => $data['questionnaire_question_id'] ?? '-',
                'questionnaire_answer_id' => $data['questionnaire_answer_id'] ?? '-',
                'score' => $data['score'] ?? '-',
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function get_by_screening($screening_id)
    {
        return $this->screening_detail->with([
                'questionnaire_question',
                'questionnaire_question.questionnaire_category'
            ])
            ->where('screening_id', $screening_id)
            ->get();
    }
}
