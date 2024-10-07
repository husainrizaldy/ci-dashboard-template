<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Dashboard management system" name="description" />
    <meta content="DataFortunaSolution" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= BASE_URL() ?>assets/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="<?= BASE_URL() ?>assets/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= BASE_URL() ?>assets/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= BASE_URL() ?>assets/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= BASE_URL() ?>assets/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= BASE_URL() ?>assets/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= BASE_URL() ?>assets/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= BASE_URL() ?>assets/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= BASE_URL() ?>assets/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL() ?>assets/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?= BASE_URL() ?>assets/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL() ?>assets/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= BASE_URL() ?>assets/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL() ?>assets/favicons/favicon-16x16.png">
    <link rel="manifest" href="<?= BASE_URL() ?>manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?= BASE_URL() ?>assets/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Bootstrap Css -->
    <link href="<?= BASE_URL('assets/css/bootstrap.min.css') ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= BASE_URL('assets/css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= BASE_URL('assets/css/app.min.css') ?>" id="app-style" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" type="text/css" />
    <!-- JAVASCRIPT -->
    <script src="<?= BASE_URL('assets/libs/') ?>jQuery-3.6.0/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="<?= BASE_URL('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= BASE_URL('assets/libs/metismenujs/metismenujs.min.js') ?>"></script>
    <script src="<?= BASE_URL('assets/libs/simplebar/simplebar.min.js') ?>"></script>
    <script src="<?= BASE_URL('assets/libs/feather-icons/feather.min.js') ?>"></script>
    <script src="<?= BASE_URL('assets/js/custom.js') ?>"></script>
    
</head>

<body data-layout="vertical" data-layout-mode="light" data-topbar="light" data-sidebar="dark">
    <div class="authentication-bg min-vh-100">
        <div class="bg-overlay"></div>
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">
                            <div class="card-body p-4"> 
                                <div class="text-center mt-2">
                                    <img src="<?php echo BASE_URL('assets/images/logo/main-logo.png') ?>" height="100" alt="Agungtent">
                                    <div class="mb-3"></div>
                                    <p class="text-muted">Silahkan Login untuk melanjutkan</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form action="" id="form-login" autocomplete="off">

                                        <input type="hidden" name="secret" id="secret" value="<?php echo $this->session->xtoken; ?>">
                                        <div class="mb-3 formGroup">
                                            <label class="form-label" for="useremail">Email</label>
                                            <input type="email" class="form-control validate_email" value="dev.master@agt.id" id="useremail" name="email" placeholder="Enter email" autocomplete="off">
                                        </div>
                
                                        <div class="mb-3 formGroup">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <input type="password" class="form-control validate_password" id="userpassword" name="password" value="123qweasd" placeholder="Enter password" autocomplete="off">
                                        </div>
                                        
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">LOGIN</button>
                                        </div>
                                    </form>
                                </div>
            
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center text-muted p-4">
                            <p class="text-white-50">© <?php echo date('Y') ?> DFSOLID</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div><!-- end container -->
    </div>
    <!-- end authentication section -->
    <script type="text/javascript">
    $(document).ready(function(){
        $(document).on('submit', '#form-login', function(e){
            e.preventDefault();
            let s = $('#form-login');
            const o = s.find('input[name="secret"]').val();
            $.ajax({
                url: "<?php echo BASE_URL('logaction') ?>",
                method: 'POST',
                dataType: 'JSON',
                data: {
                    token: 'login',
                    xtoken: o,
                    datalog: s.serialize()
                }
            }).done(function(res){
                if (res.status) {
                    let user = `<span class="fw-bold">${res.user}</span>`;
                    toastr.success(`autentikasi berhasil`,'Berhasil!');
                    window.setTimeout(function(){
                        toastr.info(`Selamt datang ${user}`,'Hallo!');
                    },500)
                    window.setTimeout(function(){
                        window.location.href="<?php echo BASE_URL('dashboard') ?>"
                    },1500)
                } else {
                    if (res.status_error == 'error-validation') {
                        formElementValidationWithTimeout(res, s, 'validate_', 'formGroup', 'feedback');
                    } else if(res.status_error == 'failed'){
                        toastr.warning(res.message.body,res.message.title);
                        resetValue();
                    }
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                toastr.error(errorThrown,textStatus);
                resetValue();
                window.setTimeout(function(){
                    window.location.href="<?php echo BASE_URL('login') ?>"
                },1500)
            });
        });
        function resetValue() {
            let f = $('#form-login');
            f.find('input[name="email"]').val('');
            f.find('input[name="password"]').val('');
        }
    });
    </script>
</body>
</html>
