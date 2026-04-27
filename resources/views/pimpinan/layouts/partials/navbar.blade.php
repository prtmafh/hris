<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white"
    id="sidenavAccordion">
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle">
        <i data-feather="menu"></i>
    </button>
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="{{ route('pimpinan.dashboard') }}">TSI GROUP</a>
    <ul class="navbar-nav align-items-center ms-auto">
        <li class="nav-item me-2">
            <button class="btn btn-sm btn-outline-secondary btn-theme-toggle d-inline-flex align-items-center gap-2"
                type="button" data-theme-toggle aria-label="Ganti tema">
                <i data-feather="moon"></i>
                <span class="d-none d-sm-inline" data-theme-label>Dark mode</span>
            </button>
        </li>
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage"
                href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img class="img-fluid" src="{{ asset('sbadmin/assets/img/illustrations/profiles/profile-1.png') }}" />
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img"
                        src="{{ asset('sbadmin/assets/img/illustrations/profiles/profile-1.png') }}" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">{{ Auth::user()->karyawan->nama ?? Auth::user()->nik }}</div>
                        <div class="dropdown-user-details-email">Pimpinan</div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item" type="submit">
                        <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                        Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
