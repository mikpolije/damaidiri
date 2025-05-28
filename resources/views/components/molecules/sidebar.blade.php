<div class="app-sidebar">
    <div class="logo">
        <a href="{{ url('/') }}" class="logo-icon"><span class="logo-text">DamaiDiri</span></a>
        <div class="sidebar-user-switcher user-activity-online">
            <a href="{{ route('redirect.auth') }}">
                <img src="{{ asset( auth()->user()->profile->photo ?? 'assets/images/avatars/avatar.png') }}" title="{{ auth()->user()->name }}">
                <span class="activity-indicator" title="Online"></span>
                <span class="user-info-text">
                    <span title="{{ auth()->user()->name }}">{{ substr(auth()->user()->name, 0, 10) }}{{ strlen(auth()->user()->name) > 10 ? '...' : '' }}</span>
                    <br>
                    <span class="user-state-info" title="{{ auth()->user()->roles()->pluck('name')[0] }}">{{ auth()->user()->roles()->pluck('name')[0] }}</span>
                </span>
            </a>
        </div>
    </div>
    <div class="app-menu">
        <ul class="accordion-menu">
            @can('dashboard_superadmin')
                <x-atoms.menu-sidebar route="dashboard.superadmin" route_active="dashboard" icon="dashboard" label="Dasbor" />
            @endcan
            @can('dashboard_admin')
                <x-atoms.menu-sidebar route="dashboard.admin" route_active="dashboard" icon="dashboard" label="Dasbor" />
            @endcan
            @can('dashboard_patient')
                <x-atoms.menu-sidebar route="dashboard.patient" route_active="dashboard" icon="dashboard" label="Dasbor" />
            @endcan
            @can('dashboard_psychologist')
                <x-atoms.menu-sidebar route="dashboard.psychologist" route_active="dashboard" icon="dashboard" label="Dasbor" />
            @endcan

            @canany(['my_account', 'list_permission', 'list_role'])
            <li class="sidebar-title">
                Konfigurasi
            </li>
            @endcanany
            @canany(['my_account', 'my_profile', 'my_password'])
                <x-atoms.menu-sidebar route="profile.my-account" route_active="profile" icon="account_circle" label="Profil Saya" />
            @endcanany
            @canany(['list_permission', 'list_role'])
                <x-atoms.menu-sidebar-dropdown icon="admin_panel_settings" label="Hak Akses & Peran"
                    :sub_menu="[
                        ['label' => 'Hak Akses', 'route_active' => 'permission', 'route' => 'permission.list', 'permission' => 'list_permission'],
                        ['label' => 'Peran', 'route_active' => 'role', 'route' => 'role.list', 'permission' => 'list_role'],
                    ]"
                />
            @endcanany
            @can('list_user')
                <x-atoms.menu-sidebar route="user.list" route_active="user" icon="manage_accounts" label="Data Pengguna" />
            @endcan

            @canany(['list_partner', 'list_blog_category', 'list_blog'])
                <li class="sidebar-title">
                    Menu CMS
                </li>
            @endcanany
            @can('list_partner')
                <x-atoms.menu-sidebar route="master-partner.list" route_active="master-partner" icon="handshake" label="Mitra" />
            @endcan
            @can('list_blog_category')
                <x-atoms.menu-sidebar route="blog-category.list" route_active="blog-category" icon="category" label="Kategori Blog" />
            @endcan
            @can('list_blog')
                <x-atoms.menu-sidebar route="blog.list" route_active="blog" icon="import_contacts" label="Blog" />
            @endcan

            @canany(['list_questionnaire', 'list_simulation_questionnaire', 'list_questionnaire_screening', 'list_history_screening', 'check_screening', 'list_journal', 'list_simulation_journal'])
                <li class="sidebar-title">
                    Menu Utama
                </li>
            @endcanany
            @can('list_questionnaire')
                <x-atoms.menu-sidebar route="master-questionnaire.list" route_active="master-questionnaire" icon="assignment" label="Master Tes" />
            @endcan
            @can('list_simulation_questionnaire')
                <x-atoms.menu-sidebar route="simulation-questionnaire.list" route_active="simulation-questionnaire" icon="compare" label="Simulasi Tes" />
            @endcan
            @can('check_screening')
                <x-atoms.menu-sidebar route="check-screening.form" route_active="check-screening" icon="content_paste_search" label="Cek Tes Pasien" />
            @endcan
            @can('list_journal')
                <x-atoms.menu-sidebar route="master-journal.list" route_active="master-journal" icon="auto_stories" label="Master Jurnal" />
            @endcan
            @can('list_simulation_journal')
                <x-atoms.menu-sidebar route="simulation-journal.list" route_active="simulation-journal" icon="compare" label="Simulasi Jurnal" />
            @endcan

            @can('list_questionnaire_screening')
                <x-atoms.menu-sidebar route="questionnaire-screening.list" route_active="questionnaire-screening" icon="assignment" label="Daftar Tes" />
            @endcan
            @can('list_journal_response')
                <x-atoms.menu-sidebar route="journal-response.list" route_active="journal-response" icon="auto_stories" label="Daftar Jurnal" />
            @endcan
            @can('list_history_screening')
                <x-atoms.menu-sidebar route="history-screening.list" route_active="history-screening" icon="pending_actions" label="Riwayat Tes" />
            @endcan
            @can('list_history_response')
                <x-atoms.menu-sidebar route="history-response.list" route_active="history-response" icon="pending_actions" label="Riwayat Jurnal" />
            @endcan

            @canany(['list_documentation_api'])
                <li class="sidebar-title">
                    Menu Lainnya
                </li>
            @endcanany
            @can('list_documentation_api')
                <x-atoms.menu-sidebar route="documentation-api.list" route_active="documentation-api" icon="terminal" label="Dokumentasi API" />
            @endcan
        </ul>
    </div>
</div>