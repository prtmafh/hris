<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white"
    id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i
            data-feather="menu"></i></button>
    <!-- Navbar Brand-->
    <!-- * * Tip * * You can use text or an image for your navbar brand.-->
    <!-- * * * * * * When using an image, we recommend the SVG format.-->
    <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="index.html">TSI GROUP</a>
    <!-- Navbar Search Input-->
    <!-- * * Note: * * Visible only on and above the lg breakpoint-->
    {{-- <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control pe-0" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-text"><i data-feather="search"></i></div>
        </div>
    </form> --}}
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <li class="nav-item me-2">
            <button class="btn btn-sm btn-outline-secondary btn-theme-toggle d-inline-flex align-items-center gap-2"
                type="button" data-theme-toggle aria-label="Ganti tema">
                <i data-feather="moon"></i>
                <span class="d-none d-sm-inline" data-theme-label>Dark mode</span>
            </button>
        </li>
        <!-- Documentation Dropdown-->
        {{-- <li class="nav-item dropdown no-caret d-none d-md-block me-3">
            <a class="nav-link dropdown-toggle" id="navbarDropdownDocs" href="javascript:void(0);" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="fw-500">Documentation</div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0 me-sm-n15 me-lg-0 o-hidden animated--fade-in-up"
                aria-labelledby="navbarDropdownDocs">
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro" target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i data-feather="book"></i></div>
                    <div>
                        <div class="small text-gray-500">Documentation</div>
                        Usage instructions and reference
                    </div>
                </a>
                <div class="dropdown-divider m-0"></div>
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro/components"
                    target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i data-feather="code"></i></div>
                    <div>
                        <div class="small text-gray-500">Components</div>
                        Code snippets and reference
                    </div>
                </a>
                <div class="dropdown-divider m-0"></div>
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro/changelog"
                    target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i data-feather="file-text"></i></div>
                    <div>
                        <div class="small text-gray-500">Changelog</div>
                        Updates and changes
                    </div>
                </a>
            </div>
        </li> --}}
        <!-- Navbar Search Dropdown-->
        <!-- * * Note: * * Visible only below the lg breakpoint-->
        <li class="nav-item dropdown no-caret me-3 d-lg-none">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="searchDropdown" href="#" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="search"></i></a>
            <!-- Dropdown - Search-->
            <div class="dropdown-menu dropdown-menu-end p-3 shadow animated--fade-in-up"
                aria-labelledby="searchDropdown">
                <form class="form-inline me-auto w-100">
                    <div class="input-group input-group-joined input-group-solid">
                        <input class="form-control pe-0" type="text" placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2" />
                        <div class="input-group-text"><i data-feather="search"></i></div>
                    </div>
                </form>
            </div>
        </li>

        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage"
                href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false"><img class="img-fluid"
                    src="{{asset('sbadmin/assets/img/illustrations/profiles/profile-1.png')}}" /></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img"
                        src="{{asset('sbadmin/assets/img/illustrations/profiles/profile-1.png')}}" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">Valerie Luna</div>
                        <div class="dropdown-user-details-email">vluna@aol.com</div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#!">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                    Account
                </a>
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