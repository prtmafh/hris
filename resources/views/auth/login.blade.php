<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="theme-color" content="#0d6efd">
    {{--
    <link rel="manifest" href="/manifest.json"> --}}
    <title>Login | TSI GROUP</title>
    <link rel="shortcut icon" href="{{ asset('') }}assets/img/logotsi.png" />
    <link href="{{ asset('sbadmin/css/styles.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/logotsi.png')}}" />
    <link rel="manifest" href="/manifest.json">

    <meta name="theme-color" content="#0d6efd">

    <link rel="apple-touch-icon" href="{{ asset('') }}assets/img/logotsi.png">
    {{--
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logotsi.png') }}" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous">
    </script>
</head>

<body class="bg-gradient-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header bg-white text-center py-4">
                                    <a href="#" class="d-block">
                                        <img alt="Logo" src="{{ asset('') }}assets/img/tsilogo.svg" class="mb-3"
                                            style="height: 50px;" />
                                    </a>
                                    <h3 class="text-primary fw-bold mb-1">Selamat Datang di HRIS</h3>
                                    <p class="text-muted small">Masuk ke Sistem HRIS untuk Admin dan Karyawan</p>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body p-5">
                                    <form action="{{ route('login.post') }}" method="post" id="kt_sign_in_form">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="form-label text-gray-600 fw-semibold" for="nik">
                                                <i class="fas fa-id-card me-2"></i>NIK
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input class="form-control form-control-solid" type="text" name="nik"
                                                    id="nik" placeholder="Masukkan NIK Anda" autocomplete="off"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label text-gray-600 fw-semibold" for="password">
                                                <i class="fas fa-lock me-2"></i>Password
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                <input class="form-control form-control-solid" type="password"
                                                    name="password" id="password" placeholder="Masukkan Password"
                                                    autocomplete="off" required />
                                            </div>
                                        </div>
                                        <div class="d-grid mt-4">
                                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary btn-lg">
                                                <span class="indicator-label">
                                                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                                                </span>
                                                <span class="indicator-progress d-none">
                                                    <i class="fas fa-spinner fa-spin me-2"></i>Silakan Tunggu...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3 bg-light">
                                    <small class="text-muted">Sistem HRIS &copy; {{ date('Y') }} TSI</small>
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
                            &copy; {{ date('Y') }} TSI HRIS
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- PWA Install Modal -->
    {{-- <div class="modal fade" id="installModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        📱 Install HRIS
                    </h5>
                </div>

                <div class="modal-body text-center">

                    <img src="{{ asset('assets/img/logotsi.png') }}" width="90" class="mb-3">

                    <h5>Install Aplikasi HRIS</h5>

                    <p class="text-muted">
                        Install HRIS agar akses lebih cepat langsung dari layar utama.
                        Anda juga akan mendapatkan pengalaman seperti aplikasi Android.
                    </p>

                </div>

                <div class="modal-footer justify-content-center">

                    <button class="btn btn-secondary" id="laterInstall">
                        Nanti
                    </button>

                    <button class="btn btn-primary" id="installAppBtn">
                        Install Sekarang
                    </button>

                </div>

            </div>
        </div>
    </div> --}}


    {{-- PWA Service Worker --}}
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
    {{-- </script> --}}
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

    {{-- <script>
        let deferredPrompt = null;
        
            window.addEventListener('beforeinstallprompt', (e)=>{
        
            e.preventDefault();
        
            deferredPrompt = e;
        
            // Jangan tampilkan jika sudah pernah ditutup
            if(localStorage.getItem("hideInstall") !== "true"){
        
                setTimeout(()=>{
        
                    let modal = new bootstrap.Modal(document.getElementById('installModal'));
        
                    modal.show();
        
                },1500);
        
            }
        
        });
        
        document.getElementById("installAppBtn").addEventListener("click", async ()=>{
        
            if(!deferredPrompt) return;
        
            deferredPrompt.prompt();
        
            const choice = await deferredPrompt.userChoice;
        
            deferredPrompt = null;
        
            bootstrap.Modal.getInstance(
                document.getElementById("installModal")
            ).hide();
        
        });
        
        document.getElementById("laterInstall").addEventListener("click",()=>{
        
        let next = Date.now() + (7*24*60*60*1000);
        
        let next = localStorage.getItem("nextInstallPopup");
        
        if(!next || Date.now() > next){
        
        let modal = new bootstrap.Modal(document.getElementById("installModal"));
        
        modal.show();
        
        }
    
        bootstrap.Modal.getInstance(
            document.getElementById("installModal")
        ).hide();
    
    });
        let next = localStorage.getItem("nextInstallPopup");
    
        if(!next || Date.now() > next){
    
        let modal = new bootstrap.Modal(document.getElementById("installModal"));
    
        modal.show();
    
    }
        
        // window.addEventListener("appinstalled",()=>{
        
        //     localStorage.setItem("hideInstall","true");
        
        // });
        
    </script> --}}
</body>

</html>