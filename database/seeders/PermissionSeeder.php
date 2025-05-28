<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();

        Permission::create(['name' => 'dashboard_superadmin']);
        Permission::create(['name' => 'dashboard_admin']);
        Permission::create(['name' => 'dashboard_patient']);
        Permission::create(['name' => 'dashboard_psychologist']);

        Permission::create(['name' => 'list_permission']);
        Permission::create(['name' => 'create_permission']);
        Permission::create(['name' => 'read_permission']);
        Permission::create(['name' => 'update_permission']);
        Permission::create(['name' => 'delete_permission']);

        Permission::create(['name' => 'list_role']);
        Permission::create(['name' => 'create_role']);
        Permission::create(['name' => 'read_role']);
        Permission::create(['name' => 'update_role']);
        Permission::create(['name' => 'delete_role']);

        Permission::create(['name' => 'list_user']);
        Permission::create(['name' => 'create_user']);
        Permission::create(['name' => 'read_user']);
        Permission::create(['name' => 'update_user']);
        Permission::create(['name' => 'delete_user']);
        Permission::create(['name' => 'deactivated_user']);
        Permission::create(['name' => 'activated_user']);

        Permission::create(['name' => 'my_account']);
        Permission::create(['name' => 'my_profile']);
        Permission::create(['name' => 'my_profile_psychologist']);
        Permission::create(['name' => 'my_password']);

        Permission::create(['name' => 'list_partner']);
        Permission::create(['name' => 'create_partner']);
        Permission::create(['name' => 'read_partner']);
        Permission::create(['name' => 'update_partner']);
        Permission::create(['name' => 'delete_partner']);

        Permission::create(['name' => 'list_blog_category']);
        Permission::create(['name' => 'create_blog_category']);
        Permission::create(['name' => 'read_blog_category']);
        Permission::create(['name' => 'update_blog_category']);
        Permission::create(['name' => 'delete_blog_category']);

        Permission::create(['name' => 'list_blog']);
        Permission::create(['name' => 'create_blog']);
        Permission::create(['name' => 'read_blog']);
        Permission::create(['name' => 'update_blog']);
        Permission::create(['name' => 'delete_blog']);

        Permission::create(['name' => 'list_blog_gallery']);
        Permission::create(['name' => 'create_blog_gallery']);
        Permission::create(['name' => 'update_blog_gallery']);
        Permission::create(['name' => 'delete_blog_gallery']);

        Permission::create(['name' => 'list_questionnaire']);
        Permission::create(['name' => 'create_questionnaire']);
        Permission::create(['name' => 'read_questionnaire']);
        Permission::create(['name' => 'update_questionnaire']);
        Permission::create(['name' => 'delete_questionnaire']);
        Permission::create(['name' => 'config_questionnaire']);

        Permission::create(['name' => 'list_questionnaire_category']);
        Permission::create(['name' => 'create_questionnaire_category']);
        Permission::create(['name' => 'update_questionnaire_category']);
        Permission::create(['name' => 'delete_questionnaire_category']);

        Permission::create(['name' => 'list_questionnaire_detail_category']);
        Permission::create(['name' => 'create_questionnaire_detail_category']);
        Permission::create(['name' => 'update_questionnaire_detail_category']);
        Permission::create(['name' => 'delete_questionnaire_detail_category']);

        Permission::create(['name' => 'list_questionnaire_question']);
        Permission::create(['name' => 'create_questionnaire_question']);
        Permission::create(['name' => 'update_questionnaire_question']);
        Permission::create(['name' => 'delete_questionnaire_question']);

        Permission::create(['name' => 'list_questionnaire_answer']);
        Permission::create(['name' => 'create_questionnaire_answer']);
        Permission::create(['name' => 'update_questionnaire_answer']);
        Permission::create(['name' => 'delete_questionnaire_answer']);

        Permission::create(['name' => 'list_questionnaire_history']);
        Permission::create(['name' => 'detail_questionnaire_history']);

        Permission::create(['name' => 'list_simulation_questionnaire']);
        Permission::create(['name' => 'simulate_simulation_questionnaire']);

        Permission::create(['name' => 'list_questionnaire_screening']);
        Permission::create(['name' => 'screening_questionnaire']);

        Permission::create(['name' => 'list_history_screening']);
        Permission::create(['name' => 'detail_history_screening']);

        Permission::create(['name' => 'check_screening']);

        Permission::create(['name' => 'list_journal']);
        Permission::create(['name' => 'create_journal']);
        Permission::create(['name' => 'read_journal']);
        Permission::create(['name' => 'update_journal']);
        Permission::create(['name' => 'delete_journal']);
        Permission::create(['name' => 'config_journal']);

        Permission::create(['name' => 'list_journal_question']);
        Permission::create(['name' => 'create_journal_question']);
        Permission::create(['name' => 'update_journal_question']);
        Permission::create(['name' => 'delete_journal_question']);

        Permission::create(['name' => 'list_journal_history']);
        Permission::create(['name' => 'detail_journal_history']);

        Permission::create(['name' => 'list_simulation_journal']);
        Permission::create(['name' => 'simulate_simulation_journal']);

        Permission::create(['name' => 'list_journal_response']);
        Permission::create(['name' => 'response_journal']);

        Permission::create(['name' => 'list_history_response']);
        Permission::create(['name' => 'detail_history_response']);
        
        Permission::create(['name' => 'list_documentation_api']);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
