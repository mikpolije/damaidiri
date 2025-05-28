<?php

namespace App\Providers;

use App\Interfaces\OverviewInterface;
use App\Repositories\OverviewRepository;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->load_repository_interface();
        $this->load_helper();
    }

    public function boot(): void
    {
        $this->load_locale();
    }

    protected function load_repository_interface(): void
    {
        $this->app->bind(\App\Interfaces\QuestionnaireDetailCategoryInterface::class, \App\Repositories\QuestionnaireDetailCategoryRepository::class);
        $this->app->bind(\App\Interfaces\QuestionnaireCategoryInterface::class, \App\Repositories\QuestionnaireCategoryRepository::class);
        $this->app->bind(\App\Interfaces\QuestionnaireQuestionInterface::class, \App\Repositories\QuestionnaireQuestionRepository::class);
        $this->app->bind(\App\Interfaces\QuestionnaireAnswerInterface::class, \App\Repositories\QuestionnaireAnswerRepository::class);
        $this->app->bind(\App\Interfaces\JournalQuestionInterface::class, \App\Repositories\JournalQuestionRepository::class);
        $this->app->bind(\App\Interfaces\ScreeningDetailInterface::class, \App\Repositories\ScreeningDetailRepository::class);
        $this->app->bind(\App\Interfaces\ResponseDetailInterface::class, \App\Repositories\ResponseDetailRepository::class);
        $this->app->bind(\App\Interfaces\QuestionnaireInterface::class, \App\Repositories\QuestionnaireRepository::class);
        $this->app->bind(\App\Interfaces\BlogCategoryInterface::class, \App\Repositories\BlogCategoryRepository::class);
        $this->app->bind(\App\Interfaces\PsychologistInterface::class, \App\Repositories\PsychologistRepository::class);
        $this->app->bind(\App\Interfaces\BlogGalleryInterface::class, \App\Repositories\BlogGalleryRepository::class);
        $this->app->bind(\App\Interfaces\PermissionInterface::class, \App\Repositories\PermissionRepository::class);
        $this->app->bind(\App\Interfaces\ScreeningInterface::class, \App\Repositories\ScreeningRepository::class);
        $this->app->bind(\App\Interfaces\OverviewInterface::class, \App\Repositories\OverviewRepository::class);
        $this->app->bind(\App\Interfaces\ProvinceInterface::class, \App\Repositories\ProvinceRepository::class);
        $this->app->bind(\App\Interfaces\ResponseInterface::class, \App\Repositories\ResponseRepository::class);
        $this->app->bind(\App\Interfaces\JournalInterface::class, \App\Repositories\JournalRepository::class);
        $this->app->bind(\App\Interfaces\PartnerInterface::class, \App\Repositories\PartnerRepository::class);
        $this->app->bind(\App\Interfaces\PatientInterface::class, \App\Repositories\PatientRepository::class);
        $this->app->bind(\App\Interfaces\ProfileInterface::class, \App\Repositories\ProfileRepository::class);
        $this->app->bind(\App\Interfaces\RegencyInterface::class, \App\Repositories\RegencyRepository::class);
        $this->app->bind(\App\Interfaces\BlogInterface::class, \App\Repositories\BlogRepository::class);
        $this->app->bind(\App\Interfaces\RoleInterface::class, \App\Repositories\RoleRepository::class);
        $this->app->bind(\App\Interfaces\UserInterface::class, \App\Repositories\UserRepository::class);
    }

    protected function load_helper(): void
    {
        foreach(Glob(App_path('Helpers/*.php')) As $Filename){
            Require_once $Filename;
        }
    }

    protected function load_locale(): void
    {
        Carbon::setLocale('id');
    }
}
