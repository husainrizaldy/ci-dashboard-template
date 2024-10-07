<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Error 404</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Dashboard management system" name="description" />
        <meta content="DataFortunaSolution" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= BASE_URL('assets/favicons') ?>/favicon.ico">
        <!-- Bootstrap Css -->
        <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?= base_url('assets/css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?= base_url('assets/css/app.min.css') ?>" id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body data-layout="horizontal" data-topbar="dark">
    <div class="authentication-bg min-vh-100" style="background: url(./assets/images/auth-bg.jpg) bottom;" >
        <div class="bg-overlay bg-light"></div>
        <div class="container">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <div class="home-wrapper text-center">
                            <div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-9">
                                        <div class="error-img">
                                            <img src="<?= base_url() ?>assets/images/404-img.png" alt="" class="img-fluid mx-auto d-block">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="text-center">
                                <a class="btn btn-primary waves-effect waves-light" href="<?= BASE_URL() ?>">Back to Dashboard</a>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end authentication section -->

        <!-- JAVASCRIPT -->
    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/metismenujs/metismenujs.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/simplebar/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/feather-icons/feather.min.js') ?>"></script>
    </body>
</html>
