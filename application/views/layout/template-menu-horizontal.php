<header id="page-topbar" class="ishorizontal-topbar">
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
                    <span class="logo-lg">
                        <img src="<?php echo base_url('assets/images/logo/logo.svg'); ?>" alt="" height="22"> <span class="logo-txt">DFS</span>
                    </span>
                    <span class="logo-sm">
                        <img src="<?php echo base_url('assets/images/logo/logo.svg'); ?>" alt="" height="22">
                    </span>
                </a>
            </div>
            <!-- END - LOGO -->

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div class="topnav">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <!-- START - navbar-nav -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link dropdown-toggle arrow-none" href="<?= BASE_URL() ?>" id="topnav-dashboard" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class='bx bxs-dashboard'></i>
                                    <span data-key="t-dashboards">Dashboard</span>
                                </a>
                            </li>
                            <?php 
                                $role = $this->session->userdata('userSession')['user_role'];
                                $qmenu = "SELECT * FROM app_menu_path 
                                            WHERE status=1 AND id IN (SELECT 
                                                    DISTINCT a.id_path 
                                                    FROM app_menu_content AS a
                                                    JOIN app_roles_access AS b ON a.id=b.id_menu
                                                    WHERE b.id_role = $role)";
                                $path = $this->db->query($qmenu)->result();
                            ?>
                            <?php foreach ($path as $p) : ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-<?= $p->path_key ?>" role="button">
                                        <i class='<?= $p->data_icon ?>'></i>
                                        <span data-key="<?= $p->data_key ?>"><?= $p->path_name ?></span> <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-<?= $p->path_key ?>">
                                        <?php 
                                            $path_id = $p->id;
                                            $second = $this->db->select("a.id,a.id_path,b.id_role, a.menu_name, a.menu_key, a.menu_route, a.data_icon, a.data_key as mp_key, a.status")
                                                                ->from("app_menu_content AS a")
                                                                ->join("app_roles_access AS b","a.id = b.id_menu")
                                                                ->where("a.id_path", $path_id)
                                                                ->where("b.id_role", $role)
                                                                ->where("a.status", 1)
                                                                ->order_by("a.id","ASC")
                                                                ->get()
                                                                ->result();
                                        ?>
                                        <!-- START - second layer -->
                                        <?php foreach($second as $s) : ?>
                                            <?php 
                                                $second_id = $s->id;
                                                $check = $this->db->get_where('app_menu_sub', ['id_menu' => $second_id]);
                                            ?>
                                            <!-- check second layer have child on thrid layer -->
                                            <?php if ($check->num_rows() < 1) : ?>
                                                <a href="<?= base_url($p->path_key.'/'.$s->menu_route) ?>" class="dropdown-item" data-key="<?= $s->mp_key ?>"><?= $s->menu_name ?></a>
                                            <?php else: ?>
                                                <div class="dropdown">
                                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-<?= $s->mp_key ?>"
                                                        role="button">
                                                        <span data-key="<?= $s->mp_key ?>"><?= $s->menu_name ?></span> <div class="arrow-down"></div>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="topnav-<?= $s->mp_key ?>">
                                                    <?php
                                                        $third = $this->db->select("tp.id, tp.id_menu, tp.sub_name, tp.sub_route, tp.data_key as ms_key")
                                                                            ->from("app_menu_sub AS tp")
                                                                            ->where("tp.id_menu", $second_id)
                                                                            ->get()
                                                                            ->result();
                                                    ?>
                                                    <!-- third layer -->
                                                    <?php foreach ($third as $t) : ?>
                                                        <a href="<?= base_url($p->path_key.'/'.$s->menu_key.'/'.$t->sub_route) ?>" class="dropdown-item" data-key="<?= $t->ms_key ?>"><?= $t->sub_name ?></a>
                                                    <?php endforeach; ?>
                                                    <!-- third layer -->
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <!-- end check -->
                                        <?php endforeach; ?>
                                        <!-- END - second layer -->


                                        

                                    </div>
                                </li>
                            <?php endforeach; ?>

                        </ul>
                        <!-- END - navbar-nav -->
                    </div>
                </nav>
            </div>

        </div>
        <div class="d-flex">
            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item light-dark" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-sm layout-mode-dark "></i>
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
