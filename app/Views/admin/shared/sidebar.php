<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="<?= site_url('admin/dashboard') ?>" class="brand-link navbar-primary text-center p-2">
	  	<span class="brand-text font-weight-light "><img src="<?php echo base_url()?>/adm/img/logo_easymerx.png" alt="Logo" width="188" ></span>
	</a>
	<!-- Sidebar -->
	<div class="sidebar">
	  	<!-- Sidebar user panel (optional) -->
	  	<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
		  		<img src="<?php echo base_url()?>/adm/img/avatar.png" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
		  		<a href="#" class="d-block"><?= $currentUser->first_name ?></a>
			</div>
	  	</div>

	  	<!-- Sidebar Menu -->
	  	<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
				<li class="nav-item">
					<a href="<?= site_url('admin/dashboard') ?>" class="nav-link <?= ($currentAdminSubMenu == 'dashboard') ? 'active' : '' ?>">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Dashboard</p>
					</a>
				</li>
				<li class="nav-item has-treeview <?= ($currentAdminMenu == 'events') ? 'menu-open' : '' ?>">
					<a href="#" class="nav-link">
						 <i class="nav-icon fas fa-users"></i>
						<p>Eventos <i class="right fas fa-angle-left"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= site_url('admin/artists') ?>" class="nav-link  <?= ($currentAdminSubMenu == 'artists') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Artistas</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/tours') ?>" class="nav-link  <?= ($currentAdminSubMenu == 'tours') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Tours</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/event') ?>?s_status=1" class="nav-link  <?= ($currentAdminSubMenu == 'event') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Eventos</p>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item has-treeview <?= ($currentAdminMenu == 'products') ? 'menu-open' : '' ?>">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-chalkboard-teacher"></i>
						<p>Gestor productos <i class="right fas fa-angle-left"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= site_url('admin/products') ?>" class="nav-link  <?= ($currentAdminSubMenu == 'products') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Productos</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/brand') ?>" class="nav-link <?= ($currentAdminSubMenu == 'brands') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Marcas</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/category') ?>" class="nav-link <?= ($currentAdminSubMenu == 'categories') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Categorias</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/feature') ?>" class="nav-link <?= ($currentAdminSubMenu == 'feature') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Grupo características</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/featurevalue') ?>" class="nav-link <?= ($currentAdminSubMenu == 'featurevalue') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Valor características</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/attributes') ?>" class="nav-link <?= ($currentAdminSubMenu == 'attributes') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Grupo atributos</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/attributesvalue') ?>" class="nav-link <?= ($currentAdminSubMenu == 'attributesvalue') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Valor atributos</p>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item has-treeview <?= ($currentAdminMenu == 'contents') ? 'menu-open' : '' ?>">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-chalkboard-teacher"></i>
						<p>Gestor contenidos <i class="right fas fa-angle-left"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= site_url('admin/page') ?>" class="nav-link <?= ($currentAdminSubMenu == 'pages') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Contenidos fijos</p>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item has-treeview <?= ($currentAdminMenu == 'ecommerce') ? 'menu-open' : '' ?>">
					<a href="#" class="nav-link">
						 <i class="nav-icon fas fa-users"></i>
						<p>Ecommerce <i class="right fas fa-angle-left"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= site_url('admin/order') ?>" class="nav-link  <?= ($currentAdminSubMenu == 'order') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Ventas</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/customer') ?>" class="nav-link  <?= ($currentAdminSubMenu == 'customer') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Clientes</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/contact') ?>" class="nav-link  <?= ($currentAdminSubMenu == 'contact') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Contacto</p>
							</a>
						</li>
					</ul>
				</li>
				
				<li class="nav-item has-treeview  <?= ($currentAdminMenu == 'user-role') ? 'menu-open' : '' ?>">
					<a href="#" class="nav-link">
						<i class="nav-icon  fas fa-users-cog"></i>
						<p>Administración <i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= site_url('admin/users') ?>" class="nav-link <?= ($currentAdminSubMenu == 'user') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Usuarios</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/configuration') ?>" class="nav-link <?= ($currentAdminSubMenu == 'configuration') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Configuración</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/templateemail') ?>" class="nav-link <?= ($currentAdminSubMenu == 'templateemail') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Template emails</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('admin/language') ?>" class="nav-link <?= ($currentAdminSubMenu == 'language') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?= admin_translate('Sidebar', 'language_listing') ?></p>
							</a>
						</li>
						
						<li class="nav-item">
							<a href="<?= base_url(route_to('admin_translation_listing')); ?>" class="nav-link <?= ($currentAdminSubMenu == 'translation') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?= admin_translate('Sidebar', 'translate') ?></p>
							</a>
						</li>

					</ul>
				</li>
			
				
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>