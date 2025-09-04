<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Products</h1>
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
						<h3 class="card-title">Product Images : <?= $product['multilanguage']['name'][$lang_id] ?></h3>
					</div>
					<form role="form" method="post" action="<?= site_url('admin/products/positionimages/' . $product['id']) ?>"  enctype="multipart/form-data">
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0">
						<?= view('admin/shared/flash_message') ?>
						<table class="table table-hover text-nowrap">
							<thead>
								<tr>
									<th>Imagen</th>
									<th>Principal</th>
									<th>nº orden</th>
									<th style="width:15%"></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($productImages)) : ?>
									<?php foreach ($productImages as $image) : ?>
										<tr>
											<td><img src="/uploads/products/small/<?= $image['img'] ?>" class="thumbnails"/></td>
											<td><a class="btn"  href="<?= site_url('admin/products/coverimagen/' . $image['id']) ?>" ><?= $image['cover']?'<i class="fas fa-check-circle fa-1x text-success"></i>':'<i class="fas fa-check-circle fa-1x text-muted"></i>' ?> </a></td>
											<td><input type="number" name="position[<?= $image['id'] ?>]" class="form-control" style="max-width:100px ;"  value="<?=$image['position'] ?>"> </td>
											<td>
												<a class="btn btn-danger delete-row" href="#" data-url="<?= site_url('admin/products/deleteimagen/' . $image['id']) ?>">
													<i class="far fa-trash-alt "></i>
												</a>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="3">No record found</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
					<div class="card-footer clearfix">
						<div class="row">
							<div class="col-4">
								<button type="submit" class="btn btn-dark">Ordenar imagen</button>
								<a href="<?= site_url('admin/products/'. $product['id'] .'/upload-image') ?>?imgopt=local" class="btn btn-success">cargar imagen</a>
								<a href="<?= site_url('admin/products/'. $product['id'] .'/upload-image') ?>?imgopt=url"  class="btn btn-default">importar imágenes</a>
							</div>
						</div>
					</div>
					</form>
				</div>
				<!-- /.card -->
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>