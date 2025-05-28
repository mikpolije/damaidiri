<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;

use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();

        $role_superadmin = Role::create(['name' => User::SUPERADMIN_ROLE, 'guard_name' => 'web']);
        $role_admin = Role::create(['name' => User::ADMIN_ROLE, 'guard_name' => 'web']);
        $role_patient = Role::create(['name' => User::PATIENT_ROLE, 'guard_name' => 'web']);
        $role_psychologist = Role::create(['name' => User::PSYCHOLOGIST_ROLE, 'guard_name' => 'web']);

        $permission_superadmin = [
            // Dashboard
            'dashboard_superadmin',

            // Management Permission and Role
            'list_permission', 'create_permission', 'read_permission', 'update_permission', 'delete_permission',
            'list_role', 'create_role', 'read_role', 'update_role',

            // Management User
            'list_user','create_user', 'read_user', 'update_user', 'delete_user', 'deactivated_user', 'activated_user',

            // Management Profile
            'my_account', 'my_password',

            // Management Partner
            'list_partner', 'create_partner', 'read_partner', 'update_partner', 'delete_partner',

            // Management Blog Category
            'list_blog_category', 'create_blog_category', 'read_blog_category', 'update_blog_category', 'delete_blog_category',

            // Management Blog
            'list_blog', 'create_blog', 'read_blog', 'update_blog', 'delete_blog', 'list_blog_gallery', 'create_blog_gallery', 'update_blog_gallery', 'delete_blog_gallery',

            // Management Questionnaire
            'list_questionnaire', 'create_questionnaire', 'read_questionnaire', 'update_questionnaire', 'delete_questionnaire', 'config_questionnaire',

            // Management Questionnaire Category
            'list_questionnaire_category', 'create_questionnaire_category', 'update_questionnaire_category', 'delete_questionnaire_category',
            
            // Management Questionnaire Detail Category
            'list_questionnaire_detail_category', 'create_questionnaire_detail_category', 'update_questionnaire_detail_category', 'delete_questionnaire_detail_category',

            // Management Questionnaire Question
            'list_questionnaire_question', 'create_questionnaire_question', 'update_questionnaire_question', 'delete_questionnaire_question',

            // Management Questionnaire Answer
            'list_questionnaire_answer', 'create_questionnaire_answer', 'update_questionnaire_answer', 'delete_questionnaire_answer',

            // Management Questionnaire History
            'list_questionnaire_history', 'detail_questionnaire_history',

            // Management Simulation Questionnaire
            'list_simulation_questionnaire', 'simulate_simulation_questionnaire',

            // Management Journal
            'list_journal', 'create_journal', 'read_journal', 'update_journal', 'delete_journal', 'config_journal',

            // Management Journal Question
            'list_journal_question', 'create_journal_question', 'update_journal_question', 'delete_journal_question',

            // Management Journal History
            'list_journal_history', 'detail_journal_history',

            // Management Simulation Journal
            'list_simulation_journal', 'simulate_simulation_journal',

            // Management Documentation API
            'list_documentation_api',
        ];

        $permission_admin = [
            // Dashboard
            'dashboard_admin',

            // Management User
            'list_user', 'read_user',
            
            // Management Profile
            'my_account', 'my_password',

            // Management Partner
            'list_partner', 'create_partner', 'read_partner', 'update_partner',

            // Management Blog Category
            'list_blog_category', 'create_blog_category', 'read_blog_category', 'update_blog_category', 'delete_blog_category',

            // Management Blog
            'list_blog', 'create_blog', 'read_blog', 'update_blog', 'delete_blog', 'list_blog_gallery', 'create_blog_gallery', 'update_blog_gallery', 'delete_blog_gallery',

            // Management Questionnaire
            'list_questionnaire', 'create_questionnaire', 'read_questionnaire', 'update_questionnaire', 'config_questionnaire',

            // Management Questionnaire Category
            'list_questionnaire_category', 'create_questionnaire_category', 'update_questionnaire_category',

            // Management Questionnaire Detail Category
            'list_questionnaire_detail_category', 'create_questionnaire_detail_category', 'update_questionnaire_detail_category',

            // Management Questionnaire Question
            'list_questionnaire_question', 'create_questionnaire_question', 'update_questionnaire_question',

            // Management Questionnaire Answer
            'list_questionnaire_answer', 'create_questionnaire_answer', 'update_questionnaire_answer',

            // Management Questionnaire History
            'list_questionnaire_history', 'detail_questionnaire_history',

            // Management Simulation Questionnaire
            'list_simulation_questionnaire', 'simulate_simulation_questionnaire',

            // Management Journal
            'list_journal', 'create_journal', 'read_journal', 'update_journal', 'config_journal',

            // Management Journal Question
            'list_journal_question', 'create_journal_question', 'update_journal_question',

            // Management Journal History
            'list_journal_history', 'detail_journal_history',
            
            // Management Simulation Journal
            'list_simulation_journal', 'simulate_simulation_journal',

            // Management Documentation API
            'list_documentation_api',
        ];

        $permission_patient = [
            // Dashboard
            'dashboard_patient',

            // Management Profile
            'my_account', 'my_profile', 'my_password',

            // Management Questionnaire Screening
            'list_questionnaire_screening', 'screening_questionnaire',

            // Management History Screening
            'list_history_screening', 'detail_history_screening',

            // Management Journal Response
            'list_journal_response', 'response_journal',

            // Management History Response
            'list_history_response', 'detail_history_response',
        ];

        $permission_psychologist = [
            // Dashboard
            'dashboard_psychologist',
            
            // Management Profile
            'my_account', 'my_profile_psychologist', 'my_password',

            // Management Check Screening
            'check_screening',
        ];

        $role_superadmin->givePermissionTo($permission_superadmin);
        $role_admin->givePermissionTo($permission_admin);
        $role_patient->givePermissionTo($permission_patient);
        $role_psychologist->givePermissionTo($permission_psychologist);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
