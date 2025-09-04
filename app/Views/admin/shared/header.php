<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
	<!-- Left navbar links -->
	<ul class="navbar-nav">
	  <li class="nav-item">
		  <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
	  </li>
	</ul>
	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
	  <!-- User Dropdown Menu -->
	  <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <img src="<?php echo base_url()?>/adm/img/avatar.png" class="user-image img-circle elevation-2" alt="User Image">
          <span class="d-none d-md-inline">Mi cuenta</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="<?php echo base_url()?>/adm/img/avatar.png" class="img-circle elevation-2" alt="User Image">
            <p>
			        <?= $currentUser->first_name ?> <?= $currentUser->last_name ?>              <small>---</small>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="<?= site_url('admin/users/edit/'. $currentUser->id) ?>" class="btn btn-default btn-flat">Tu perfil</a>
            <a href="<?php echo site_url('auth/logout') ?>" class="btn btn-default btn-flat float-right"> Cerrar sesión</a>
          </li>
        </ul>
      </li>

      <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="/uploads/languages/<?= $lang; ?>.svg" class="mr-1" width="25">
              <div class="d-sm-none d-lg-inline-block"><?= $lang; ?></div></a>
          <div class="dropdown-menu dropdown-menu-right">
              <?php foreach ($languages['codes'] as $lang) { ?>
                  <a href="<?= base_url(route_to('admin_language_change', $lang['code'])); ?>" class="dropdown-item has-icon">
                      <img width="25" src="/uploads/languages/<?= $lang['img']; ?>" alt=""> <?= $lang['name']; ?>
                  </a>
              <?php } ?>
          </div>
      </li>

	</ul>
  </nav>
  <!-- /.navbar -->