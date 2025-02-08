<!doctype html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- App favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ $generaleSetting?->favicon ?? asset('assets/favicon.png') }}" />

    <!-- App title -->
    <title>{{ $generaleSetting?->title ?? config('app.name', 'Laravel') }} Admin Login</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>

<body style="background: url({{ asset('assets/images/admin-bg.svg') }})">

    <section class="login-section">

        <div class="thumbnail">
            <img src="{{ asset('assets/images/login.png') }}" alt="thumbnail" width="100%">
        </div>

        <!-- Login Card -->
        <div class="card loginCard">
            <div class="card-body">
                @if (app()->isLocal())
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <span class="powerBy">Powered by <a class="text-primary text-decoration-none"
                                href="https://razinsoft.com/" target="_blank">RazinSoft</a>
                            ©{{ date('Y') }}</span>
                        <span class="version fw-bold">v{{ config('app.version') }}</span>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <img src="{{ $generaleSetting?->logo ?? asset('assets/logo.png') }}" alt="" height="80"
                        style="max-width: 100%">
                </div>

                <div class="page-content text-center mb-4">
                    <p class="pagePera my-3">
                        Welcome to
                        <span class="fw-bold text-primary">{{ $generaleSetting?->name ?? config('app.name', 'Laravel') }}</span>
                    </p>

                    <h2 class="pageTitle">Login To Admin</h2>
                </div>

                <hr>

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email">Enter Address</label>
                        <input type="email" value="{{ old('email') }}" name="email" id="email"
                            class="form-control" placeholder="Enter Email Address">

                        @error('email')
                            <span class="text text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password">Password</label>
                        <div class="position-relative passwordInput">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter Password">
                            <span class="eye" onclick="showHidePassword()">
                                <i class="fa fa-eye-slash fa-eye" id="togglePassword"></i>
                            </span>
                        </div>
                        @error('password')
                            <span class="text text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    </div>

                    <button class="btn loginButton" type="submit">Login</button>

                    @if (app()->isLocal())
                        <div class="credentials-section">
                            <div class="item">
                                <div class="header">Admin Credentials</div>
                                <div class="content">
                                    <div class="credentials">
                                        <span>Email: root@readyecommerce.com</span>
                                        <span>Password: secret</span>
                                    </div>
                                    <div class="copyBtn" onclick="loginAdmin()">
                                        <i class="fa-regular fa-copy"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="header">Shop Credentials</div>
                                <div class="content">
                                    <div class="credentials">
                                        <span>Email: shop@readyecommerce.com</span>
                                        <span>Password: secret</span>
                                    </div>
                                    <div class="copyBtn" onclick="gotoShopLogin()">
                                        <i class="fa-regular fa-copy"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
    <script src="{{ asset('assets/scripts/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var themeColor = "{{ $generaleSetting?->primary_color ?? '#EE456B' }}";
            document.documentElement.style.setProperty('--theme_color', themeColor);
        });

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function showHidePassword() {
            const toggle = document.getElementById("togglePassword");
            const password = document.getElementById("password");

            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            toggle.classList.toggle("fa-eye");
        }

        var email = document.getElementById("email");
        var password = document.getElementById("password");

        function loginAdmin() {
            email.value = 'root@readyecommerce.com';
            password.value = 'secret';

            if (!sessionStorage.getItem('fromShop')) {
                Toast.fire({
                    icon: 'success',
                    title: 'Admin Credentials Copied'
                });
            }
        }

        const checkSession = () => {
            if (sessionStorage.getItem('fromShop')) {
                loginAdmin();
                sessionStorage.removeItem('fromShop');
            }
        }

        const gotoShopLogin = () => {
            sessionStorage.setItem('fromAdmin', true);
            window.open("{{ route('shop.login') }}", '_blank');
        }

        checkSession();
    </script>

</body>

</html>
