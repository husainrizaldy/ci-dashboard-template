<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Dashboard management system" name="description" />
    <meta content="DataFortunaSolution" name="author" />
    <?php require_once 'template-icon-assets.php'; ?>
    <?php require_once 'template-top-assets.php'; ?>
</head>

<?php 
    $layout = users_template_settings();

    $data_layout = $layout->theme_layout;
    $data_layout_mode = $layout->theme_mode;
    $data_sidebar = '';
    $data_topbar = '';
    if ($data_layout == 'vertical') {
        $data_sidebar = 'data-sidebar="light"';
        $data_topbar = 'data-topbar="'.$layout->topbar.'"';
    }
    if ($data_layout == 'horizontal') {
        $data_sidebar = '';
        $data_topbar = 'data-topbar="'.$layout->topbar.'"';
    }
?>
<body data-layout="<?= $data_layout; ?>" data-layout-mode="<?= $data_layout_mode; ?>" <?= $data_sidebar; ?> <?= $data_topbar; ?>>

<!-- Begin page -->
<div id="layout-wrapper">
    <!-- ========== Top Bar ========== -->
    <?php require_once 'template-top-bar.php'; ?>
    <!-- Top bar end -->
    
    <!-- ========== Left Sidebar Start ========== -->
    <?php require_once 'template-menu-sidebar.php'; ?>
    <!-- Left Sidebar End -->
    
    <!-- Horizontal menu -->
    <?php require_once 'template-menu-horizontal.php'; ?>
    <!-- Horizontal menu End -->
    
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
            <?php echo $contents ?>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
    <!-- end main content-->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <?= date('Y') ?> &copy; DataFortunaSolution
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Dev <i class="mdi mdi-code-json text-danger"></i> by <a href="https://dfsolid.id" target="_blank" class="text-reset">DFSOLID</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<!-- END layout-wrapper -->

<a href="#" id="right-bar-toggle">
<div id="sidebar-setting"></div>
</a>

<script src="<?= base_url('assets/libs/metismenujs/metismenujs.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/simplebar/simplebar.min.js') ?>"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(document).on('click','#alogout',function(e) {
        e.preventDefault();
        const user = $(this).data("user");
        Swal.fire({
            title:'Konfirmasi',
            text:'anda akan keluar, lanjutkan?',
            icon:"question",
            showCancelButton:true,
            confirmButtonColor:"#00a65a",
            cancelButtonColor:"#d33",
            confirmButtonText:"Ya, keluar",
            cancelButtonText:"Batalkan"
        }).then(function(t){ 
            if(t.value) {
                $.ajax({
                    url: "<?= BASE_URL('logout') ?>",
                    method: 'GET',
                    dataType: 'JSON',
                    data: {
                        token: 'logout',
                        data: user
                    },
                }).done(function(data){
                    if (data.status) {
                        toastr.success(data.message.body,data.message.title);
                        window.setTimeout(function(){
                            window.location.href="<?php echo BASE_URL('login') ?>"
                        },1500)
                    }
                }).fail(function(jqXHR, textStatus, errorThrown){
					handleErrorCallback('toastr', null, true, "<?php echo BASE_URL('dashboard') ?>");
                });
            }
        });
    });
});
</script>
</body>
</html>
