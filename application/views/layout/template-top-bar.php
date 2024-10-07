    <header id="page-topbar" class="isvertical-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="<?= base_url() ?>" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="<?php echo base_url('assets/images/logo/logo.svg'); ?>" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="<?php echo base_url('assets/images/logo/logo.svg'); ?>" alt="" height="22"> <span class="logo-txt">DFS</span>
                        </span>
                    </a>

                    <a href="<?= base_url() ?>" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="<?php echo base_url('assets/images/logo/logo.svg'); ?>" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="<?php echo base_url('assets/images/logo/logo.svg'); ?>" alt="" height="22"> <span class="logo-txt">DFS</span>
                        </span>
                    </a>
                    
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>

                
                
            </div>

            <div class="d-flex">

                <?php include 'template-notification.php'; ?>

                <div class="dropdown d-none d-sm-inline-block">
                    <button type="button" class="btn header-item light-dark" id="mode-setting-btn">
                        <i data-feather="moon" class="icon-sm layout-mode-dark"></i>
                        <i data-feather="sun" class="icon-sm layout-mode-light"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="<?php echo IMG_PROFILE . currentDataUserSession('picture');  ?>"
                        alt="Header Avatar">
                    </button>
                    <div class="dropdown-menu dropdown-menu-end pt-0">
                        <a class="dropdown-item" href="<?= BASE_URL('apps/profile') ?>"><i class='bx bx-user-circle text-muted font-size-18 align-middle me-1'></i> <span class="align-middle">Profile</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="alogout" data-user="<?= currentDataUserSession('uid') ?>"><i class='bx bx-log-out text-muted font-size-18 align-middle me-1'></i> <span class="align-middle">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </header>
