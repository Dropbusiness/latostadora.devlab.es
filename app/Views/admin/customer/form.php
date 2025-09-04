<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Customer</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Customer</li>
			</ol>
		  </div>
		</div>
	  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<!-- /.row -->
		<div class="row">
		  	<div class="col-md-12">
			  <div class="card">
				<div class="card-header">
					<h3 class="card-title"><?= isset($data) ? 'Actualizar' : 'Nueva' ?> Customer</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<?php if (!empty($data)): ?>
					<form role="form" method="post" action="<?= site_url('admin/customer/update/'. $data['id']) ?>"  enctype="multipart/form-data">
					<input name="_method" type="hidden" value="PUT">
				<?php else: ?>
					<form role="form" method="post" action="<?= site_url('admin/customer/save') ?>"  enctype="multipart/form-data">
				<?php endif; ?>
					<input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : null ?>"/>
					<div class="card-body">
						<?= view('admin/shared/flash_message') ?>
						
						
						<div class="form-group">
							<label for="email">email*</label>
							<?= form_input('email', set_value('email', isset($data['email']) ? ($data['email']) : '' ), ['class' => 'form-control', 'id' => 'email']) ?>
						</div>
						<div class="form-group">
							<label for="passwd">passwd</label>
							<?= form_input('passwd', '', ['class' => 'form-control', 'id' => 'passwd','autocomplete'=>'false']) ?>
						</div>
						<div class="form-group">
							<label for="firstname">firstname</label>
							<?= form_input('firstname', set_value('firstname', isset($data['firstname']) ? ($data['firstname']) : '' ), ['class' => 'form-control', 'id' => 'firstname']) ?>
						</div>
						<div class="form-group">
							<label for="lastname">lastname</label>
							<?= form_input('lastname', set_value('lastname', isset($data['lastname']) ? ($data['lastname']) : '' ), ['class' => 'form-control', 'id' => 'lastname']) ?>
						</div>
						<div class="form-group">
							<label for="siteName">company</label>
							<?= form_input('company', set_value('company', isset($data['company']) ? ($data['company']) : '' ), ['class' => 'form-control', 'id' => 'company']) ?>
						</div>
						<div class="form-group">
							<label for="country">country</label>
							<?= form_input('country', set_value('country', isset($data['country']) ? ($data['country']) : '' ), ['class' => 'form-control', 'id' => 'country']) ?>
						</div>
						<div class="form-group">
							<label for="city">city</label>
							<?= form_input('city', set_value('city', isset($data['city']) ? ($data['city']) : '' ), ['class' => 'form-control', 'id' => 'city']) ?>
						</div>
						<div class="form-group">
							<label for="address">address</label>
							<?= form_input('address', set_value('address', isset($data['address']) ? ($data['address']) : '' ), ['class' => 'form-control', 'id' => 'address']) ?>
						</div>
						<div class="form-group">
							<label for="cif">cif</label>
							<?= form_input('cif', set_value('cif', isset($data['cif']) ? ($data['cif']) : '' ), ['class' => 'form-control', 'id' => 'cif']) ?>
						</div>
						<div class="form-group">
							<label for="optin">advertising</label>
							<?php
							$options = array(
								'1' => 'Si',
								'0' => 'No'
							);
							$selected_option = isset($data['optin']) ? $data['optin'] : '0';
							echo form_dropdown('optin', $options, $selected_option, 'class="form-control" id="optin"');
							?>
						</div>
						<div class="form-group">
							<label for="siteSku">Status*</label>
							<?= form_dropdown('status',$statuses, set_value('status', isset($data['status']) ? ($data['status']) : '' ), ['class' => 'form-control', 'id' => 'status']) ?>
						</div>
						
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
						<a href="<?= site_url('admin/customer') ?>" class="btn btn-default">Volver</a>
					</div>
				</form>
			</div>
			<!-- /.card -->
			</div>
			<!-- /.card -->
		  	</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>