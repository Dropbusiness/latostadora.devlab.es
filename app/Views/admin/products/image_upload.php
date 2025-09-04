<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>New Product</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
					<li class="breadcrumb-item active">Products</li>
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
			<?php if (!empty($product)) : ?>
				<div class="col-3">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Menu</h3>
						</div>
						<div class="card-body">
							<?= $this->include('admin/products/menus'); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-9">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title"><?=$imgopt=='local'?'Cargar imagen desde local':'Importar imágenes desde url'?></h3>
					</div>
					<!-- /.card-header -->
					
					<!-- form start -->
					<form role="form" method="post" action="<?= site_url('admin/products/'. $product['id'] .'/upload-image') ?>" enctype="multipart/form-data">
						<input type="hidden" name="imgopt" value="<?=$imgopt?>">
						<div class="card-body">
							<?= view('admin/shared/flash_message') ?>

							<div class="form-group <?=$imgopt=='local'?'':'d-none'?>">
								<label for="productImage">Imagen (jpg,png,webp)</label>
								<?= form_upload('image', '' , ['class' => 'form-control', 'id' => 'image']) ?>
							</div>
							<div class="form-group <?=$imgopt=='url'?'':'d-none'?>">
								<label for="productImage">Url imagen (jpg,png,webp)</label>
								<?= form_textarea('imageurl', '' , ['class' => 'form-control', 'id' => 'imageurl']) ?>
								<div class="form-text">En cada linea tiene que poner la url de la imagen</div>
							</div>
						</div>
						<!-- /.card-body -->

						<div class="card-footer">
							<button type="submit" class="btn btn-primary">Guardar</button>
							<a href="<?= site_url('admin/products/'. $product['id'] .'/images') ?>" class="btn btn-default">Volver</a>
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