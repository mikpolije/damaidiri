<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\QuestionnaireInterface;
use App\Interfaces\ScreeningInterface;

class HistoryScreeningController extends Controller
{
    private $param;
    private $questionnaire, $screening;

    public function __construct(ScreeningInterface $screening, QuestionnaireInterface $questionnaire)
    {
        $this->screening = $screening;
        $this->questionnaire = $questionnaire;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_screening'] = $this->screening->list_by_user($request->all(), auth()->user()->id);

        return view('pages.questionnaire-screening-history.list', $this->param);
    }

    public function detail($questionnaire, $screening) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($questionnaire);
        $this->param['detail_screening'] = $this->screening->detail($screening);

        return view('pages.questionnaire-screening-history.detail', $this->param);
    }
}
