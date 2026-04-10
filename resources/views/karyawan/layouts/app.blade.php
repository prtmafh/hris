<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>@yield('title') - TSI GROUP</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="{{ asset('sbadmin/css/styles.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('sbadmin/assets/img/favicon.png') }}" />
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous">
    </script>
    @stack('styles')
</head>

<body class="nav-fixed">
    @include('karyawan.layouts.partials.navbar')
    <div id="layoutSidenav">
        @include('karyawan.layouts.partials.sidebar')
        <div id="layoutSidenav_content">
            @yield('content')
            <footer class="footer-admin mt-auto footer-light">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-md-6 small">Copyright &copy; {{ now()->year }} TSI Karyawan</div>
                        <div class="col-md-6 text-md-end small">
                            <span>Portal Absensi TSI GROUP</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('sbadmin/js/scripts.js') }}"></script>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary'
            }
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
    @stack('scripts')
</body>

</html>
