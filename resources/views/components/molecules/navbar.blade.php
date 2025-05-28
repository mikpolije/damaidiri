
<div class="app-header">
    <nav class="navbar navbar-light navbar-expand-lg">
        <div class="d-flex justify-content-between w-100 h-100" style="padding-top: 12px;">
            <div class="navbar-nav" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link hide-sidebar-toggle-button" href="#"><i class="material-icons-two-tone">first_page</i></a>
                    </li>
                </ul>
            </div>
            <div class="d-flex">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-end" id="notificationsDropDown" href="#" data-bs-toggle="dropdown" style="margin-right: 6px;">
                            <i class="material-icons-two-tone">settings</i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end notifications-dropdown w-25" style="margin-top: -15px !important;" aria-labelledby="notificationsDropDown">
                            <h6 class="dropdown-header">Pengaturan</h6>
                            <div class="notifications-dropdown-list">
                                @canany(['my_account', 'my_profile', 'my_password'])
                                    <form action="{{ route('profile.my-account') }}" method="GET">
                                        <button type="submit" class="bg-transparent w-100 border-0">
                                            <div class="notifications-dropdown-item">
                                                <div class="notifications-dropdown-item-image">
                                                    <span class="notifications-badge bg-info text-white">
                                                        <i class="material-icons-outlined">account_circle</i>
                                                    </span>
                                                </div>
                                                <div class="notifications-dropdown-item-text">
                                                    <p class="bold-notifications-text text-start" style="padding-top: 12px;">Profil</p>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                @endcanany
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-transparent w-100 border-0">
                                        <div class="notifications-dropdown-item">
                                            <div class="notifications-dropdown-item-image">
                                                <span class="notifications-badge bg-danger text-white">
                                                    <i class="material-icons-outlined">logout</i>
                                                </span>
                                            </div>
                                            <div class="notifications-dropdown-item-text">
                                                <p class="bold-notifications-text text-start" style="padding-top: 12px;">Keluar</p>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>