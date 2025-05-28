<?php

namespace App\Http\Controllers\API;

use App\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\QuestionnaireAnswerInterface;
use App\Interfaces\QuestionnaireDetailCategoryInterface;
use App\Interfaces\QuestionnaireInterface;
use App\Interfaces\QuestionnaireQuestionInterface;
use App\Interfaces\ScreeningDetailInterface;
use App\Interfaces\ScreeningInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    use ApiTrait;

    public function __construct(
        protected QuestionnaireInterface $questionnaire,
        protected QuestionnaireQuestionInterface $questionnaire_question,
        protected ScreeningInterface $screening,
        protected ScreeningDetailInterface $screening_detail,
        protected QuestionnaireAnswerInterface $questionnaire_answer,
        protected QuestionnaireDetailCategoryInterface $questionnaire_detail_category_interface
    ) {}
    
    public function store(Request $request, $id)
    {
        $status = 'failed';
        $message = '';
        $result_score = null;
        $result_code = null;
        $questionnaire_categories = [];
        $status_code = HttpStatus::ERROR;

        \DB::beginTransaction();
        try {
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
            $score_accumulate = 0;

            foreach ($detail_questionnaire->questionnaire_question as $question) {
                $answer_id = $validate_data["answer_question_{$question->id}"];
                $score = $this->questionnaire_answer->detail($answer_id)->score ?? 0;
                $questionnaire_categories[$question->questionnaire_category_id][] = $score;
                $score_accumulate += $score;
            }

            $result_score = $score_accumulate;

            foreach ($questionnaire_categories as $questionnaire_category_id => $score) {
                $accumulation_score_per_category = array_sum($score);
                $score_per_category_obj =  $this->questionnaire_detail_category_interface->find_by_range($questionnaire_category_id, $score);
                $result = [
                    'category' => $score_per_category_obj->questionnaire_category->name ?? '',
                    'level' => $score_per_category_obj?->level,
                    'score' => $accumulation_score_per_category
                ];
                $questionnaire_categories[$questionnaire_category_id] = $result;
            }
            ksort($questionnaire_categories); // order by key ASC
            
            $data_screening = $this->screening->store([
                'user_id' => auth()->user()->id,
                'questionnaire_id' => $id,
                'score_accumulate' => $score_accumulate
            ]);
            $result_code = $data_screening->screening_code;

            foreach ($detail_questionnaire->questionnaire_question as $question) {
                $answer_id = $validate_data["answer_question_{$question->id}"];

                $this->screening_detail->store([
                    'screening_id' => $data_screening->id,
                    'questionnaire_question_id' => $question->id,
                    'questionnaire_answer_id' => $answer_id,
                    'score' => $this->questionnaire_answer->detail($answer_id)->score,
                ]);
            }

            \DB::commit();
            $status = 'success';
            $message = 'Successufully submit the screening';
            $status_code =HttpStatus::CREATED;
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            $message = $e->getMessage();
            $status_code =HttpStatus::VALIDATION_FAILED;
        } catch (\Exception $e) {
            \DB::rollBack();
            $message = $e->getMessage();
            $status_code =HttpStatus::ERROR;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
                'score' => $result_score,
                'code' => $result_code,
                'result' => array_values($questionnaire_categories)
            ];
            return response()->json($response, $status_code);
        }
    }

    public function listHistory(Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;

        try {
            $relations = [
                'questionnaire:id,title,slug,description',
            ];
            $paginate = $request->has('paginate') ? $request->get('paginate') == '1' : true;
            $data = $this->screening->list_by_user($request->all(), auth()->user()->id, $relations);
            if ($paginate) {
                $data = $data->items();
            }
            $status = 'success';
            $message = 'Successfully retrieving the screening history';
            $status_code = HttpStatus::SUCCESS;
        }
        catch (\Exception $e) {
            $status = 'failed';
            $message = $e->getMessage();
        }
        finally {
            return $this->apiResponse($status, $message, $data, $status_code);
        }
    }

    public function detailHistory($screeningId)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        try {
            $relations = [
                'user:id,name,email',
                'questionnaire:id,title,slug,description',
                'questionnaire.questionnaire_category:id,questionnaire_id,name'
            ];
            $data = $this->screening->detail($screeningId, $relations);
            $screening_detail = $data->screening_detail;
            $results = [];
            foreach ($screening_detail as $key => $value) {
                $question = $value->questionnaire_question;
                if ($question->questionnaire_category) {
                    $results[$question->questionnaire_category->id][] = $value->score;
                }
            }
            foreach ($results as $questionnaire_category_id => $score) {
                $accumulation_score_per_category = array_sum($score);
                $score_per_category_obj =  $this->questionnaire_detail_category_interface->find_by_range($questionnaire_category_id, $score);
                $result = [
                    'category' => $score_per_category_obj->questionnaire_category->name ?? '',
                    'level' => $score_per_category_obj?->level,
                    'score' => $accumulation_score_per_category
                ];
                $final_result[$questionnaire_category_id] = $result;
            }
            ksort($final_result); // order by key ASC
            $data->final_result = array_values($final_result);
            $status = 'success';
            $message = 'Successfully retrieving the detail screening history';
            $status_code = HttpStatus::SUCCESS;
        }
        catch (\Exception $e) {
            $status = 'failed';
            $message = $e->getMessage();
        }
        finally {
            return $this->apiResponse($status, $message, $data, $status_code);
        }
    }

    public function availabilityCheck(Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        try {
            if (!$request->get('questionnaire_id')) {
                throw new \Exception('Harap pilih jenis tes terlebih dahulu!', 500);
            }
            $questionnaireId = $request->get('questionnaire_id');
            $data = $this->screening->availability_check(auth()->user()->id, $questionnaireId);
            $status = 'success';
            $message = 'Successfully checking availability for test';
            $status_code = HttpStatus::SUCCESS;
        }
        catch (\Exception $e) {
            $status = 'failed';
            $message = $e->getMessage();
        }
        finally {
            return $this->apiResponse($status, $message, $data, $status_code);
        }
    }
}
