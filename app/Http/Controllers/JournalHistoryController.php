<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\JournalInterface;
use App\Interfaces\ResponseInterface;

class JournalHistoryController extends Controller
{
    private $param;
    private $journal, $response;

    public function __construct(JournalInterface $journal, ResponseInterface $response)
    {
        $this->journal = $journal;
        $this->response = $response;
    }

    public function list(Request $request, $id) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_response'] = $this->response->list($request->all(), $id);

        $this->param['detail_journal'] = $this->journal->detail($id);

        return view('pages.journal-history.list', $this->param);
    }

    public function detail($id, $response_id) : View
    {
        $this->param['detail_journal'] = $this->journal->detail($id);
        $this->param['detail_response'] = $this->response->detail($response_id);

        return view('pages.journal-history.detail', $this->param);
    }
}
