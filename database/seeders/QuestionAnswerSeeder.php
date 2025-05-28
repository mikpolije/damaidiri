<?php

namespace Database\Seeders;

use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::beginTransaction();
        try {
            // Define the answers
            $answers = [
                0 => 'Tidak ada atau tidak pernah',
                1 => 'Sesuai dengan yang dialami sampai tingkat tertentu, atau kadang-kadang',
                2 => 'Sering',
                3 => 'Sangat sesuai dengan yang dialami, atau hampir setiap saat'
            ];
            // Load questionnaire data
            $questionnaire = Questionnaire::first();
            if (!$questionnaire) {
                throw new \Exception('Questionnaire data not found');
            }
            foreach ($answers as $key => $item) {
                QuestionnaireAnswer::create([
                    'questionnaire_id' => $questionnaire->id,
                    'name' => $item,
                    'score' => $key
                ]);
            }
            \DB::commit();
            \Log::info("Successfully create a question answer data's");
        }
        catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Failed to create a question answer data's");
            \Log::error($e);
        }
    }
}
