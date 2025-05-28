<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\ResponseDetailInterface;
use App\Interfaces\ResponseInterface;
use App\Interfaces\JournalInterface;

class SimulationJournalController extends Controller
{
    private $param;
    private $journal, $response, $response_detail;

    public function __construct(JournalInterface $journal, ResponseInterface $response, ResponseDetailInterface $response_detail)
    {
        $this->journal = $journal;
        $this->response = $response;
        $this->response_detail = $response_detail;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_journal'] = $this->journal->list($request->all());

        return view('pages.journal-simulation.list', $this->param);
    }

    public function simulate($journal) : View
    {
        $this->param['detail_journal'] = $this->journal->detail($journal);
        
        return view('pages.journal-simulation.simulation', $this->param);
    }

    public function store(Request $request, $id) : RedirectResponse
    {
        $detail_journal = $this->journal->detail($id);

        $rules = [];
        $messages = [];

        foreach ($detail_journal->journal_question as $question) {
            $fieldName = 'answer_question_' . $question->id;
            $rules[$fieldName] = 'required|string';
            $messages["$fieldName.required"] = "Jawaban untuk pertanyaan '{$question->name}' wajib diisi";
            $messages["$fieldName.string"] = "Jawaban untuk pertanyaan '{$question->name}' harus berupa teks";
        }

        $validate_data = $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $data_response = $this->response->store([
                'user_id' => auth()->user()->id,
                'journal_id' => $id,
                'is_simulate' => 1,
            ]);

            foreach ($detail_journal->journal_question as $question) {
                $answer = $validate_data["answer_question_{$question->id}"];

                $this->response_detail->store([
                    'response_id' => $data_response->id,
                    'journal_question_id' => $question->id,
                    'answer' => $answer,
                ]);
            }

            DB::commit();
            return redirect()->route('simulation-journal.list')->with('success', 'Berhasil melakukan simulasi.');
            
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
