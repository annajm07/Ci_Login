 <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
          <i class="fas fa-code"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Albupy Site</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider">


      <!-- QUERY MENU -->
      <?php  
      $role_id = $this->session->userdata('role_id');

      $querymenu = "SELECT `user_menu`.`id`, `menu` FROM `user_menu` JOIN `user_access_menu` ON `user_menu`. `id` = `user_access_menu`. `menu_id` WHERE `role_id` = $role_id ORDER BY `menu_id` ASC";
      $menu = $this->db->query($querymenu)->result_array();
      ?>

      <!-- Nav Item - Dashboard -->

      <!-- looping level -->
      <?php foreach($menu as $m) :?>
      <div class="sidebar-heading">
        <?= $m['menu']; ?>
      </div>

      <!-- looping menu -->
      <?php 
      $menu_id = $m['id'];
      $submenu = "SELECT * FROM `user_menu` JOIN `user_sub_menu` ON `user_menu`. `id` = `user_sub_menu`. `menu_id` WHERE `menu_id` = $menu_id AND `is_active` = 1";

      $result_sub_menu = $this->db->query($submenu)->result_array();
       ?>

         <?php foreach($result_sub_menu as $sm) :?>
          <?php if($sm['title'] == $title): ?>
            <li class="nav-item active">
            <?php else: ?>
            <li class="nav-item">
          <?php endif; ?>
              <a class="nav-link pb-0" href="<?= base_url($sm['url']); ?>">
                <i class="<?= $sm['icon']; ?>"></i>
                <span><?= $sm['title']; ?></span></a>
            </li>
         <?php endforeach; ?>
          <hr class="sidebar-divider mt-3">
    <?php endforeach; ?>

      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('logout'); ?>">
          <i class="fas fa-fw fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->