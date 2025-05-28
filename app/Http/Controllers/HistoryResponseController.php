<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\JournalInterface;
use App\Interfaces\ResponseInterface;

class HistoryResponseController extends Controller
{
    private $param;
    private $journal, $response;

    public function __construct(ResponseInterface $response, JournalInterface $journal)
    {
        $this->response = $response;
        $this->journal = $journal;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_response'] = $this->response->list_by_user($request->all(), auth()->user()->id);

        return view('pages.journal-response-history.list', $this->param);
    }

    public function detail($journal, $response) : View
    {
        $this->param['detail_journal'] = $this->journal->detail($journal);
        $this->param['detail_response'] = $this->response->detail($response);

        return view('pages.journal-response-history.detail', $this->param);
    }
}
