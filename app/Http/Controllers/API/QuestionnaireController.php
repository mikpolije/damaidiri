<?php

namespace App\Http\Controllers\API;

use App\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\QuestionnaireAnswerInterface;
use App\Interfaces\QuestionnaireQuestionInterface;
use App\Interfaces\QuestionnaireDetailCategoryInterface;
use App\Interfaces\QuestionnaireCategoryInterface;
use App\Interfaces\QuestionnaireInterface;
use App\Interfaces\ScreeningDetailInterface;
use App\Interfaces\ScreeningInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionnaireController extends Controller
{
    use ApiTrait;

    public function __construct(
        protected QuestionnaireInterface $questionnaire, protected QuestionnaireCategoryInterface $questionnaire_category,
        protected QuestionnaireDetailCategoryInterface $questionnaire_category_detail,
        protected QuestionnaireQuestionInterface $questionnaire_question,
        protected ScreeningInterface $screening, protected ScreeningDetailInterface $screening_detail,
        protected QuestionnaireAnswerInterface $questionnaire_answer) {}
    
    public function list(Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $relations = [
                'questionnaire_category:id,questionnaire_id,name',
                'questionnaire_category.questionnaire_category_detail:id,questionnaire_category_id,level,minimum_score,maximum_score',
            ];
            $data = $this->questionnaire->list($request->all(), $relations)->items();
            $status = 'success';
            $message = 'Successfully retrieving the questionnaires';
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

    public function listCategories($questionnaireId, Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;

        try {
            $params = [
                'paginate' => false,
                'search' => $request->get('search')
            ];
            $relations = [
                'questionnaire_category_detail:id,questionnaire_category_id,level,minimum_score,maximum_score'
            ];
            $data = $this->questionnaire_category->list($params, $questionnaireId, $relations);
            $status = 'success';
            $message = 'Successfully retrieving the categories questionnaires';
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

    public function listQuestions(Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $questionnaireId = $request->get('questionnaire_id');
            $screeningId = $request->get('screening_id');
            if (!$questionnaireId) {
                $status = 'failed';
                $message = 'questionnaire_id field is required';
                $status_code = 422;
            }
            
            $params = [
                'paginate' => false,
                'questionnaire_category_id' => $request->get('questionnaire_category_id') ?? null
            ];
            $relations = [];
            if ($screeningId) {
                $relations += [
                    'screening_answer' => function ($query) use ($screeningId) {
                        $query->select('id', 'screening_id', 'questionnaire_question_id', 'questionnaire_answer_id', 'score')
                        ->with('questionnaire_answer:id,questionnaire_id,name')
                        ->where('screening_id', $screeningId);
                    }
                ];
            }
            $data = $this->questionnaire_question->list($params, $questionnaireId, $relations);
            $status = 'success';
            $message = 'Successfully retrieving the questionnaire questions';
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

    public function listQuestionAnswers($questionnaire_id)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $data = $this->questionnaire_answer->all_by_questionnaire($questionnaire_id);
            $status = 'success';
            $message = 'Successfully retrieving the questionnaire answers';
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
