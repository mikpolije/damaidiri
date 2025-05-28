<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Interfaces\QuestionnaireInterface;
use App\Interfaces\ResponseInterface;
use App\Interfaces\ScreeningInterface;
use App\Interfaces\JournalInterface;
use App\Interfaces\PartnerInterface;
use App\Interfaces\OverviewInterface;

class DashboardController extends Controller
{
    private $param;
    private $questionnaire, $journal, $screening, $reponse, $partner, $overview;

    public function __construct(QuestionnaireInterface $questionnaire, JournalInterface $journal, ScreeningInterface $screening, ResponseInterface $reponse, PartnerInterface $partner, OverviewInterface $overview)
    {
        $this->questionnaire = $questionnaire;
        $this->journal = $journal;
        $this->screening = $screening;
        $this->reponse = $reponse;
        $this->partner = $partner;
        $this->overview = $overview;
    }

    public function dashboard_superadmin() : View
    {
        return view('pages.dashboard.dashboard-superadmin');
    }
    
    public function dashboard_admin() : View
    {
        return view('pages.dashboard.dashboard-admin');
    }

    public function dashboard_patient() : View
    {
        $this->param['count_questionnaire'] = $this->questionnaire->all()->count();
        $this->param['count_journal'] = $this->journal->all()->count();
        $this->param['count_history_questionnaire'] = $this->screening->all_by_user(auth()->user()->id)->count();
        $this->param['count_history_journal'] = $this->reponse->all_by_user(auth()->user()->id)->count();

        $this->param['data_partner'] = $this->partner->all();
        $this->param['data_overview'] = $this->overview->overview(auth()->user()->id);

        return view('pages.dashboard.dashboard-patient', $this->param);
    }

    public function dashboard_psychologist() : View
    {
        return view('pages.dashboard.dashboard-psychologist');
    }
}
