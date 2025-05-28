<?php

namespace App\Repositories;

use App\Interfaces\ResponseDetailInterface;
use App\Models\ResponseDetail;

use Illuminate\Support\Facades\DB;

class ResponseDetailRepository implements ResponseDetailInterface
{
    private $response_detail;

    public function __construct(ResponseDetail $response_detail)
    {
        $this->response_detail = $response_detail;
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $data_response_detail = $this->response_detail->create([
                'response_id' => $data['response_id'] ?? '-',
                'journal_question_id' => $data['journal_question_id'] ?? '-',
                'answer' => $data['answer'] ?? '-',
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
