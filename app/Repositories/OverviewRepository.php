<?php

namespace App\Repositories;

use App\Interfaces\OverviewInterface;
use App\Models\Questionnaire;
use App\Models\Response;
use App\Models\Screening;

class OverviewRepository implements OverviewInterface
{
    public function overview($user_id)
    {
        $year = date('Y');
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = $i;
        }
        $questionnaire = Questionnaire::orderBy('id')->get();
        foreach ($questionnaire as $item) {
            $eachMonths = [];
            foreach ($months as $month) {
                $avg = Screening::where('questionnaire_id', $item->id)
                                ->whereYear('created_at', $year)
                                ->whereMonth('created_at', $month)
                                ->where('user_id', $user_id)
                                ->avg('score_accumulate') ?? 0.00;
                if ($avg) {
                    $avg = round($avg, 2);
                }
                $eachMonths[] = $avg;
            }
            $item->screening_avg = $eachMonths;
        }
        return $questionnaire;
    }
}