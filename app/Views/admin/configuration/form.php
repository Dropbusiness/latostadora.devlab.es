<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Configuration</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Configuration</li>
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
					<h3 class="card-title"><?= isset($data) ? 'Actualizar' : 'Nueva' ?> Configuration</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<?php if (!empty($data)): ?>
					<form role="form" method="post" action="<?= site_url('admin/configuration/update/'. $data['id']) ?>"  enctype="multipart/form-data">
					<input name="_method" type="hidden" value="PUT">
				<?php else: ?>
					<form role="form" method="post" action="<?= site_url('admin/configuration/save') ?>"  enctype="multipart/form-data">
				<?php endif; ?>
					<input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : null ?>"/>
					<div class="card-body">
						<?= view('admin/shared/flash_message') ?>
						<div class="form-group">
							<label for="name">name</label>
							<?= form_input('name', set_value('name', isset($data['name']) ? ($data['name']) : '' ), ['class' => 'form-control', 'id' => 'name', 'placeholder' => '', 'required' => true]) ?>
						</div>
						<?php if(isset($data['id']) && isset($data['name']) && strpos($data['name'],'_IMG_')!==false){?>
							<div class="form-group">
								<label for="value">Imagen (150px - 70px)</label>
								<?= form_upload('image', '' , ['class' => 'form-control', 'id' => 'image']) ?>
								<?php if(isset($data['value']) && $data['value']!=''){?><img src="/uploads/configuration/<?=$data['value']?>" class="thumbnails"/><?php }?>
							</div>
						<?php }else{?>
							<div class="form-group">
								<label for="value">value</label>
								<?= form_input('value', set_value('value', isset($data['value']) ? ($data['value']) : '' ), ['class' => 'form-control', 'id' => 'value']) ?>
							</div>
						<?php }?>
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
						<a href="<?= site_url('admin/configuration') ?>" class="btn btn-default">Volver</a>
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