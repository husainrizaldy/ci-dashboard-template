    <div class="vertical-menu">

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
        <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
            <i class="fa fa-fw fa-bars"></i>
        </button>
        <!-- START - Sidebar-menu -->
        <div data-simplebar class="sidebar-menu-scroll">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">

                    <li>
                        <a href="<?= BASE_URL('dashboard'); ?>">
                            <i class="bx bx-tachometer icon nav-icon"></i>
                            <span class="menu-item" data-key="t-dashboards">Dashboard</span>
                        </a>
                    </li>

                    <?php 
                        $role = $this->session->userdata('userSession')['user_role'];
                        $qmenu = "SELECT * FROM app_menu_path 
                                    WHERE id IN (SELECT 
                                            DISTINCT a.id_path 
                                            FROM app_menu_content AS a
                                            JOIN app_roles_access AS b ON a.id=b.id_menu
                                            WHERE b.id_role = $role)";
                        $path = $this->db->query($qmenu)->result();
                    ?>
                    <!-- START - first layer -->
                    <?php foreach($path as $p) : ?>
                        <li class="menu-title" data-key="<?= $p->data_key ?>"><?= $p->path_name ?></li>
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
                                <li>
                                    <a href="<?= base_url($p->path_key.'/'.$s->menu_route) ?>">
                                        <i class="<?= $s->data_icon ?> icon nav-icon"></i>
                                        <span class="menu-item" data-key="<?= $s->mp_key ?>"> <?= $s->menu_name ?></span>
                                    </a>
                                </li>
                            <?php else: ?>

                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        <i class="<?= $s->data_icon ?> icon nav-icon"></i>
                                        <span class="menu-item" data-key="<?= $s->mp_key ?>"><?= $s->menu_name ?></span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <?php
                                            $third = $this->db->select("tp.id, tp.id_menu, tp.sub_name, tp.sub_route, tp.data_key as ms_key")
                                                                ->from("app_menu_sub AS tp")
                                                                ->where("tp.id_menu", $second_id)
                                                                ->get()
                                                                ->result();
                                        ?>
                                        <!-- third layer -->
                                        <?php foreach ($third as $t) : ?>
                                            <li><a href="<?= base_url($p->path_key.'/'.$s->menu_key.'/'.$t->sub_route) ?>" data-key="<?= $t->ms_key ?>"><?= $t->sub_name ?></a></li>
                                        <?php endforeach; ?>
                                        <!-- third layer -->
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <!-- end check -->
                        <?php endforeach; ?>
                        <!-- END - second layer -->
                    <?php endforeach; ?>
                    <!-- END - first layer -->
                    

                </ul>
                <!-- END - Left Menu Start -->
            </div>
            <!-- END - Sidebar -->
        </div>
        <!-- END - Sidebar-menu -->
    </div>
