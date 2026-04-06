<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="theme-color" content="#0d6efd">
    <link rel="manifest" href="/manifest.json">
    <title>Login | Absensi</title>
    <link rel="shortcut icon" href="{{ asset('') }}assets/img/logotsi.png" />
    <link href="{{ asset('sbadmin/css/styles.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('sbadmin/assets/img/favicon.png') }}" />
    {{--
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logotsi.png') }}" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous">
    </script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                            <div class="card my-5">
                                <!-- Logo -->
                                <div class="card-body p-5 text-center">
                                    <a href="#">
                                        <img alt="Logo" src="{{ asset('') }}assets/img/tsilogo.svg" class="mb-2"
                                            style="height: 40px;" />
                                    </a>
                                    <div class="h3 fw-light mb-0">Masuk ke Akun Anda</div>
                                    <div class="text-muted small mt-1">Silakan login untuk melanjutkan</div>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body p-5">
                                    <!-- Login form -->
                                    <form action="{{ route('login.post') }}" method="post" id="kt_sign_in_form">
                                        @csrf
                                        <!-- Form Group (username) -->
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="username">Username</label>
                                            <input class="form-control form-control-solid" type="text" name="username"
                                                id="username" placeholder="" autocomplete="off" />
                                        </div>
                                        <!-- Form Group (password) -->
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="password">Password</label>
                                            <input class="form-control form-control-solid" type="password"
                                                name="password" id="password" placeholder="" autocomplete="off" />
                                        </div>
                                        <!-- Submit button -->
                                        <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary w-100">
                                                <span class="indicator-label">Masuk</span>
                                                <span class="indicator-progress d-none">
                                                    Silahkan Tunggu...
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="footer-admin mt-auto footer-dark">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-md-12 text-center small">
                            &copy; {{ date('Y') }} TSI Absensi
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('sbadmin/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Loading state on submit --}}
    <script>
        document.getElementById('kt_sign_in_form').addEventListener('submit', function () {
            const label = document.querySelector('#kt_sign_in_submit .indicator-label');
            const progress = document.querySelector('#kt_sign_in_submit .indicator-progress');
            label.classList.add('d-none');
            progress.classList.remove('d-none');
            document.getElementById('kt_sign_in_submit').disabled = true;
        });
    </script>

    {{-- SweetAlert: Success --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    @endif

    {{-- SweetAlert: Error --}}
    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ $errors->first('error') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    @endif

    {{-- PWA Service Worker --}}
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>

</html>