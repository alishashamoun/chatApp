<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from zoyothemes.com/kadso/html/auth-login by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 01 Jul 2024 10:45:01 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>

        <meta charset="utf-8" />
        <title>Log In | Kadso - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="bg-color">

        <!-- Begin page -->
        <div class="container-fluid">
            <div class="row vh-100">
                <div class="col-12">
                    <div class="p-0">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-6 col-xl-6 col-lg-6">
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="mb-0 border-0">
                                            <div class="p-0">
                                                <div class="text-center">
                                                    <div class="mb-4">
                                                        <a class='auth-logo' href='index.html'>
                                                            <img src="assets/images/logo-dark.png" alt="logo-dark" class="mx-auto" height="28" />
                                                        </a>
                                                    </div>

                                                    <div class="auth-title-section mb-3">
                                                        <h3 class="text-dark fs-20 fw-medium mb-2">Welcome back</h3>
                                                        <p class="text-dark text-capitalize fs-14 mb-0">Please enter your details.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pt-0">
                                                <form method="POST" action="{{ route('login') }}" class="my-4">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label for="emailaddress" class="form-label">Email address</label>
                                                        <input class="form-control" name="email" type="email" id="emailaddress" required="" placeholder="Enter your email">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input class="form-control" name="password" type="password" required="" id="password" placeholder="Enter your password">
                                                    </div>

                                                    <div class="form-group d-flex mb-3">
                                                        <div class="col-sm-6">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                                                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 text-end">
                                                            <a class='text-muted fs-14' href='auth-recoverpw.html'>Forgot password?</a>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-0 row">
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <button class="btn btn-primary" type="submit"> Log In </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="text-center text-muted">
                                                    <p class="mb-0">Don't have an account ?<a class='text-primary ms-2 fw-medium' href='auth-register.html'>Sign Up</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-xl-6 col-lg-6 p-0 vh-100 d-flex justify-content-center account-page-bg">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END wrapper -->

        <!-- Vendor -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>

        <!-- App js-->
        <script src="assets/js/app.js"></script>

    </body>

<!-- Mirrored from zoyothemes.com/kadso/html/auth-login by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 01 Jul 2024 10:45:01 GMT -->
</html>
