<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Address</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Address</li>
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
					<h3 class="card-title"><?= isset($data) ? 'Actualizar' : 'Nueva' ?> Address</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<?php if (!empty($data)): ?>
					<form role="form" method="post" action="<?= site_url('admin/address/update/'. $data['id']) ?>"  enctype="multipart/form-data">
					<input name="_method" type="hidden" value="PUT">
				<?php else: ?>
					<form role="form" method="post" action="<?= site_url('admin/address/save') ?>"  enctype="multipart/form-data">
				<?php endif; ?>
					<input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : null ?>"/>
					<div class="card-body">
						<?= view('admin/shared/flash_message') ?>
						
						<div class="form-group">
							<label for="id_customer">id_customer</label>
							<?= form_input('id_customer', set_value('id_customer', isset($data['id_customer']) ? ($data['id_customer']) : '' ), ['class' => 'form-control', 'id' => 'id_customer']) ?>
						</div>
						<div class="form-group">
							<label for="name">name</label>
							<?= form_input('name', set_value('name', isset($data['name']) ? ($data['name']) : '' ), ['class' => 'form-control', 'id' => 'name']) ?>
						</div>
						<div class="form-group">
							<label for="address">address</label>
							<?= form_input('address', set_value('address', isset($data['address']) ? ($data['address']) : '' ), ['class' => 'form-control', 'id' => 'address']) ?>
						</div>
						<div class="form-group">
							<label for="postcode">postcode</label>
							<?= form_input('postcode', set_value('postcode', isset($data['postcode']) ? ($data['postcode']) : '' ), ['class' => 'form-control', 'id' => 'postcode']) ?>
						</div>
						<div class="form-group">
							<label for="state">state</label>
							<?= form_input('state', set_value('state', isset($data['state']) ? ($data['state']) : '' ), ['class' => 'form-control', 'id' => 'state']) ?>
						</div>
						<div class="form-group">
							<label for="city">city</label>
							<?= form_input('city', set_value('city', isset($data['city']) ? ($data['city']) : '' ), ['class' => 'form-control', 'id' => 'city']) ?>
						</div>
						<div class="form-group">
							<label for="phone">phone</label>
							<?= form_input('phone', set_value('phone', isset($data['phone']) ? ($data['phone']) : '' ), ['class' => 'form-control', 'id' => 'phone']) ?>
						</div>
						<div class="form-group">
							<label for="erp_addressID">erp address ID</label>
							<?= form_input('erp_addressID', set_value('erp_addressID', isset($data['erp_addressID']) ? ($data['erp_addressID']) : '' ), ['class' => 'form-control', 'id' => 'erp_addressID']) ?>
						</div>
						<div class="form-group">
							<label for="siteSku">Status</label>
							<?= form_dropdown('status',$statuses, set_value('status', isset($data['status']) ? ($data['status']) : '' ), ['class' => 'form-control', 'id' => 'status']) ?>
						</div>
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
						<a href="<?= site_url('admin/address') ?>" class="btn btn-default">Volver</a>
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