<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\QuestionnaireAnswerInterface;
use App\Interfaces\ScreeningDetailInterface;
use App\Interfaces\QuestionnaireInterface;
use App\Interfaces\ScreeningInterface;

class SimulationQuestionnaireController extends Controller
{
    private $param;
    private $questionnaire, $screening, $screening_detail, $questionnaire_answer;

    public function __construct(QuestionnaireInterface $questionnaire, ScreeningInterface $screening, ScreeningDetailInterface $screening_detail, QuestionnaireAnswerInterface $questionnaire_answer)
    {
        $this->questionnaire = $questionnaire;
        $this->screening = $screening;
        $this->screening_detail = $screening_detail;
        $this->questionnaire_answer = $questionnaire_answer;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_questionnaire'] = $this->questionnaire->list($request->all());

        return view('pages.questionnaire-simulation.list', $this->param);
    }

    public function simulate($questionnaire) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($questionnaire);
        
        return view('pages.questionnaire-simulation.simulation', $this->param);
    }

    public function store(Request $request, $id) : RedirectResponse
    {
        $detail_questionnaire = $this->questionnaire->detail($id);

        $rules = [];
        $messages = [];
        $loop_iteration = 1;

        foreach ($detail_questionnaire->questionnaire_question as $question) {
            $rules["answer_question_{$question->id}"] = 'required|exists:questionnaire_answers,id';

            $messages["answer_question_{$question->id}.required"] = "Pertanyaan nomor {$loop_iteration} wajib diisi.";
            $messages["answer_question_{$question->id}.exists"] = "Jawaban pada pertanyaan nomor {$loop_iteration} tidak valid.";
            $loop_iteration++;
        }

        $validate_data = $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $score_accumulate = 0;

            foreach ($detail_questionnaire->questionnaire_question as $question) {
                $answer_id = $validate_data["answer_question_{$question->id}"];
                $score = $this->questionnaire_answer->detail($answer_id)->score ?? 0;
                $score_accumulate += $score;
            }

            $data_screening = $this->screening->store([
                'user_id' => auth()->user()->id,
                'questionnaire_id' => $id,
                'score_accumulate' => $score_accumulate,
                'is_simulate' => 1,
            ]);

            foreach ($detail_questionnaire->questionnaire_question as $question) {
                $answer_id = $validate_data["answer_question_{$question->id}"];

                $this->screening_detail->store([
                    'screening_id' => $data_screening->id,
                    'questionnaire_question_id' => $question->id,
                    'questionnaire_answer_id' => $answer_id,
                    'score' => $this->questionnaire_answer->detail($answer_id)->score,
                ]);
            }

            DB::commit();
            return redirect()->route('simulation-questionnaire.list')->with('success', 'Berhasil melakukan simulasi.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
