<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title') - TSI GROUP</title>
    <script>
        (function() {
            var savedTheme = localStorage.getItem('sb-theme') || 'light';
            document.documentElement.setAttribute('data-sb-theme', savedTheme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="{{asset('sbadmin/css/styles.css')}}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{asset('sbadmin/assets/img/favicon.png')}}" />
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous">
    </script>
    <style>
        html[data-sb-theme="dark"] {
            color-scheme: dark;
            --sb-dark-bg: #162033;
            --sb-dark-surface: #1e2b3f;
            --sb-dark-surface-soft: #24324a;
            --sb-dark-border: #334155;
            --sb-dark-text: #e2e8f0;
            --sb-dark-muted: #94a3b8;
            --sb-dark-hover: #2b3b55;
            --sb-dark-input: #1b273a;
        }

        html[data-sb-theme="dark"] body,
        html[data-sb-theme="dark"] #layoutSidenav_content {
            background-color: var(--sb-dark-bg) !important;
            color: var(--sb-dark-text);
        }

        html[data-sb-theme="dark"] .topnav,
        html[data-sb-theme="dark"] .footer-admin,
        html[data-sb-theme="dark"] .card,
        html[data-sb-theme="dark"] .dropdown-menu,
        html[data-sb-theme="dark"] .modal-content,
        html[data-sb-theme="dark"] .list-group-item,
        html[data-sb-theme="dark"] .input-group-text,
        html[data-sb-theme="dark"] .dataTable-container,
        html[data-sb-theme="dark"] .dataTable-top,
        html[data-sb-theme="dark"] .dataTable-bottom,
        html[data-sb-theme="dark"] .litepicker .container__months,
        html[data-sb-theme="dark"] .litepicker[data-plugins=ranges] .container__main,
        html[data-sb-theme="dark"] .bg-light,
        html[data-sb-theme="dark"] .bg-white,
        html[data-sb-theme="dark"] .page-header-light {
            background-color: var(--sb-dark-surface) !important;
            color: var(--sb-dark-text) !important;
            border-color: var(--sb-dark-border) !important;
        }

        html[data-sb-theme="dark"] .sidenav-light {
            background-color: var(--sb-dark-surface-soft) !important;
        }

        html[data-sb-theme="dark"] .card,
        html[data-sb-theme="dark"] .dropdown-menu,
        html[data-sb-theme="dark"] .modal-content,
        html[data-sb-theme="dark"] .list-group-item,
        html[data-sb-theme="dark"] .input-group-text,
        html[data-sb-theme="dark"] .dataTable-container,
        html[data-sb-theme="dark"] .page-header-light,
        html[data-sb-theme="dark"] .footer-admin,
        html[data-sb-theme="dark"] .topnav,
        html[data-sb-theme="dark"] .table {
            box-shadow: none !important;
        }

        html[data-sb-theme="dark"] .card,
        html[data-sb-theme="dark"] .card-header,
        html[data-sb-theme="dark"] .card-body,
        html[data-sb-theme="dark"] .card-footer,
        html[data-sb-theme="dark"] .dropdown-item,
        html[data-sb-theme="dark"] .navbar-light .navbar-brand,
        html[data-sb-theme="dark"] .navbar-light .navbar-nav .nav-link,
        html[data-sb-theme="dark"] .page-header-light .page-header-title,
        html[data-sb-theme="dark"] .page-header-light .page-header-subtitle,
        html[data-sb-theme="dark"] .breadcrumb-item,
        html[data-sb-theme="dark"] .breadcrumb-item a,
        html[data-sb-theme="dark"] .form-label,
        html[data-sb-theme="dark"] .table,
        html[data-sb-theme="dark"] .table th,
        html[data-sb-theme="dark"] .table td,
        html[data-sb-theme="dark"] .dataTable-info,
        html[data-sb-theme="dark"] .dataTable-selector,
        html[data-sb-theme="dark"] .dataTable-pagination a,
        html[data-sb-theme="dark"] .sidenav-footer-title,
        html[data-sb-theme="dark"] .sidenav-footer-subtitle {
            color: var(--sb-dark-text) !important;
        }

        html[data-sb-theme="dark"] .text-muted,
        html[data-sb-theme="dark"] .sidenav-menu-heading,
        html[data-sb-theme="dark"] .page-header-light,
        html[data-sb-theme="dark"] .page-header-light .breadcrumb-item+.breadcrumb-item::before,
        html[data-sb-theme="dark"] .sidenav-footer-subtitle {
            color: var(--sb-dark-muted) !important;
        }

        html[data-sb-theme="dark"] .sidenav-light .sidenav-menu .nav-link,
        html[data-sb-theme="dark"] .sidenav-light .sidenav-menu .nav-link .nav-link-icon {
            color: #cbd5e1 !important;
        }

        html[data-sb-theme="dark"] .sidenav-light .sidenav-menu .nav-link.active,
        html[data-sb-theme="dark"] .sidenav-light .sidenav-menu .nav-link:hover,
        html[data-sb-theme="dark"] .dropdown-item:hover,
        html[data-sb-theme="dark"] .dropdown-item:focus,
        html[data-sb-theme="dark"] .table-hover tbody tr:hover {
            background-color: var(--sb-dark-hover) !important;
            color: #f8fafc !important;
        }

        html[data-sb-theme="dark"] .sidenav-light .sidenav-menu .nav-link.active {
            color: #ffffff !important;
        }

        html[data-sb-theme="dark"] .sidenav-footer {
            background-color: rgba(15, 23, 42, 0.35) !important;
            border-top: 1px solid var(--sb-dark-border) !important;
        }

        html[data-sb-theme="dark"] .form-control,
        html[data-sb-theme="dark"] .form-select,
        html[data-sb-theme="dark"] textarea.form-control {
            background-color: var(--sb-dark-input) !important;
            color: var(--sb-dark-text) !important;
            border-color: var(--sb-dark-border) !important;
        }

        html[data-sb-theme="dark"] .form-control:focus,
        html[data-sb-theme="dark"] .form-select:focus,
        html[data-sb-theme="dark"] textarea.form-control:focus {
            background-color: var(--sb-dark-input) !important;
            color: var(--sb-dark-text) !important;
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 0.25rem rgba(96, 165, 250, 0.2) !important;
        }

        html[data-sb-theme="dark"] .form-control::placeholder,
        html[data-sb-theme="dark"] textarea.form-control::placeholder {
            color: var(--sb-dark-muted) !important;
        }

        html[data-sb-theme="dark"] .border,
        html[data-sb-theme="dark"] .card,
        html[data-sb-theme="dark"] .card-header,
        html[data-sb-theme="dark"] .card-footer,
        html[data-sb-theme="dark"] .table> :not(caption)>*>*,
        html[data-sb-theme="dark"] .dropdown-divider,
        html[data-sb-theme="dark"] .list-group-item,
        html[data-sb-theme="dark"] .input-group-text,
        html[data-sb-theme="dark"] .modal-header,
        html[data-sb-theme="dark"] .modal-footer,
        html[data-sb-theme="dark"] .dataTable-container,
        html[data-sb-theme="dark"] .dataTable-top,
        html[data-sb-theme="dark"] .dataTable-bottom,
        html[data-sb-theme="dark"] .page-header-light,
        html[data-sb-theme="dark"] .footer-admin {
            border-color: var(--sb-dark-border) !important;
        }

        html[data-sb-theme="dark"] .table thead,
        html[data-sb-theme="dark"] .table-light,
        html[data-sb-theme="dark"] .table-light th,
        html[data-sb-theme="dark"] .table-light td {
            background-color: var(--sb-dark-surface-soft) !important;
            color: var(--sb-dark-text) !important;
        }

        html[data-sb-theme="dark"] .dropdown-item.active,
        html[data-sb-theme="dark"] .dropdown-item:active {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        html[data-sb-theme="dark"] .btn-light {
            background-color: var(--sb-dark-surface-soft) !important;
            border-color: var(--sb-dark-border) !important;
            color: var(--sb-dark-text) !important;
        }

        html[data-sb-theme="dark"] .btn-theme-toggle {
            border-color: var(--sb-dark-border);
            color: var(--sb-dark-text);
            background-color: var(--sb-dark-surface);
        }

        html[data-sb-theme="light"] .btn-theme-toggle {
            background-color: #ffffff;
        }
    </style>
    @stack('styles')
</head>

<body class="nav-fixed">
    @include('admin.layouts.partials.navbar')
    <div id="layoutSidenav">
        @include('admin.layouts.partials.sidebar')
        <div id="layoutSidenav_content">
            @yield('content')
            <footer class="footer-admin mt-auto footer-light">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-md-6 small">Copyright &copy; {{ now()->year }} TSI Admin</div>
                        <div class="col-md-6 text-md-end small">
                            <a href="#!">Privacy Policy</a>
                            &middot;
                            <a href="#!">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('sbadmin/js/scripts.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
    {{-- <script src="{{asset('sbadmin/assets/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('sbadmin/assets/demo/chart-bar-demo.js')}}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="{{asset('sbadmin/js/datatables/datatables-simple-demo.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
    <script src="{{asset('sbadmin/js/litepicker.js')}}"></script>
    @if(session('success'))
    <script>
        Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary' // 🔵 gunakan tombol biru bawaan Metronic
                },  
                buttonsStyling: false // ⛔️ supaya SweetAlert tidak override style Bootstrap
            });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
    </script>
    @endif
    @if ($errors->has('nik'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'NIK sudah digunakan',
            text: '{{ $errors->first('nik') }}',
            confirmButtonText: 'OK'
        });
    </script>
    @endif
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
    
                // 🔵 gunakan style Bootstrap / Metronic
                customClass: {
                    confirmButton: 'btn btn-primary', // tombol biru
                    cancelButton: 'btn btn-light'      // tombol abu-abu terang
                },
                buttonsStyling: false, // penting supaya style di atas tidak ditimpa
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>

</html>