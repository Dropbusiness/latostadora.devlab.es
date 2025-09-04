<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Eventos</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Eventos</li>
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
					<h3 class="card-title"><?= isset($data) ? 'Actualizar' : 'Nueva' ?> Events</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<?php if (!empty($data)): ?>
					<form role="form" method="post" action="<?= site_url('admin/event/update/'. $data['id']) ?>"  enctype="multipart/form-data">
					<input name="_method" type="hidden" value="PUT">
				<?php else: ?>
					<form role="form" method="post" action="<?= site_url('admin/event/save') ?>"  enctype="multipart/form-data">
				<?php endif; ?>
					<input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : null ?>"/>
					<div class="card-body">
						<?= view('admin/shared/flash_message') ?>
						
						<div class="form-group">
							<label for="tour_id">Artista / Tour*</label>
							<?= form_dropdown('tour_id', $tours, set_value('tour_id', isset($data['tour_id']) ? $data['tour_id'] : ''), ['class' => 'form-control', 'id' => 'tour_id','required'=>true]) ?>
						</div>
						
						<div class="form-group">
							<label for="city">city*</label>
							<?= form_input('city', set_value('city', isset($data['city']) ? ($data['city']) : '' ), ['class' => 'form-control', 'id' => 'city','required'=>true]) ?>
						</div>
						<div class="form-group">
							<label for="date">Fecha*</label>
							<input type="date" name="date" id="date" class="form-control" value="<?= set_value('date', isset($data['date']) ? $data['date'] : ''); ?>" required>
						</div>

						<div class="form-group">
							<label for="siteSku">Status*</label>
							<?= form_dropdown('status',$statuses, set_value('status', isset($data['status']) ? ($data['status']) : '' ), ['class' => 'form-control', 'id' => 'status','required'=>true]) ?>
						</div>
						
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
						<a href="<?= site_url('admin/event') ?>" class="btn btn-default">Volver</a>
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