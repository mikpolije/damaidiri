<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\QuestionnaireInterface;
use App\Interfaces\ScreeningInterface;

class QuestionnaireHistoryController extends Controller
{
    private $param;
    private $questionnaire, $screening;

    public function __construct(QuestionnaireInterface $questionnaire, ScreeningInterface $screening)
    {
        $this->questionnaire = $questionnaire;
        $this->screening = $screening;
    }

    public function list(Request $request, $id) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_screening'] = $this->screening->list($request->all(), $id);

        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);

        return view('pages.questionnaire-history.list', $this->param);
    }

    public function detail($id, $screening_id) : View
    {
        $this->param['detail_questionnaire'] = $this->questionnaire->detail($id);
        $this->param['detail_screening'] = $this->screening->detail($screening_id);

        return view('pages.questionnaire-history.detail', $this->param);
    }
}
