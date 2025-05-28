<?php

namespace App\Http\Controllers\API;

use App\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\JournalInterface;
use App\Interfaces\JournalQuestionInterface;
use App\Interfaces\ResponseDetailInterface;
use App\Interfaces\ResponseInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JournalController extends Controller
{
    use ApiTrait;

    public function __construct(
        protected JournalInterface $journal,
        protected JournalQuestionInterface $journal_question,
        protected ResponseInterface $response,
        protected ResponseDetailInterface $response_detail) {}
    
    public function list(Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $data = $this->journal->list($request->all())->items();
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

    public function listQuestion(Request $request, $id)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $data = $this->journal_question->list($request->all(), $id);
            if ($request->get('paginate') == null || $request->get('paginate') == 'true') {
                $data = $data->items();
            }
            $status = 'success';
            $message = 'Successfully retrieving the journal question';
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

    public function store(Request $request, $id)
    {
        $status = 'failed';
        $message = '';
        $validation_error = null;
        $status_code = HttpStatus::ERROR;
        
        \DB::beginTransaction();
        try {
            $detail_journal = $this->journal->detail($id);
    
            $rules = [];
            $messages = [];
    
            foreach ($detail_journal->journal_question as $question) {
                $fieldName = 'answer_question_' . $question->id;
                $rules[$fieldName] = 'required';
                $messages["$fieldName.required"] = "Jawaban untuk pertanyaan '{$question->name}' wajib diisi";
                $messages["$fieldName.string"] = "Jawaban untuk pertanyaan '{$question->name}' harus berupa teks";
            }
    
            $validate_data = $request->validate($rules, $messages);
            $data_response = $this->response->store([
                'user_id' => auth()->user()->id,
                'journal_id' => $id
            ]);

            foreach ($detail_journal->journal_question as $question) {
                $answer = $validate_data["answer_question_{$question->id}"];

                $this->response_detail->store([
                    'response_id' => $data_response->id,
                    'journal_question_id' => $question->id,
                    'answer' => $answer,
                ]);
            }

            \DB::commit();
            $status = 'success';
            $message = 'Successufully submit the journal';
            $status_code =HttpStatus::CREATED;
            
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            $message = $e->getMessage();
            $status_code =HttpStatus::ERROR;
        } catch (ValidationException $e) {
            \DB::rollBack();
            $message = $e->getMessage();
            $validation_error = $e->errors();
            $status_code =HttpStatus::VALIDATION_FAILED;
        } catch (\Exception $e) {
            \DB::rollBack();
            $message = $e->getMessage();
            $status_code =HttpStatus::ERROR;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
                'validation_error' => $validation_error
            ];
            return response()->json($response, $status_code);
        }
    }

    public function listResponse(Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $relations = [
                'journal:id,topic,purpose',
                'user:id,email,name'
            ];
            $paginate = $request->has('paginate') ? $request->get('paginate') == '1' : true;
            $data = $this->response->list_by_user($request->all(), auth()->user()->id, $relations);
            if ($paginate) {
                $data = $data->items();
            }
            $status = 'success';
            $message = 'Successfully retrieving the journal response';
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

    public function detailResponse($id)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $relations = [
                'journal:id,topic,purpose',
                'user:id,email,name',
                'response_detail:id,response_id,journal_question_id,answer',
                'response_detail.journal_question:id,journal_id,name'
            ];
            $data = $this->response->detail($id, $relations);
            $status = 'success';
            $message = 'Successfully retrieving the detail journal response';
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
