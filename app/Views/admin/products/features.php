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
						<h3 class="card-title">Product feature : <?= $product['multilanguage']['name'][$lang_id] ?></h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0">
						<form role="form" method="post" action="<?= site_url('admin/products/'. $product['id'] .'/setfeatures') ?>" enctype="multipart/form-data">
						<?= view('admin/shared/flash_message') ?>
						<table class="table table-hover text-nowrap">
							<thead>
								<tr>
									<th>feature</th>
									<th>value</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($features)) : ?>
									<?php foreach ($features as $feature) : ?>
										<tr>
											<td style="max-width: 300px;"><?= $feature['feature_name'] ?></td>
											<td style="padding-right:10px;">
												<?php if(isset($feature['values']) && is_array($feature['values'])){ ?>
													<select class="select2-multiple w-100" name="feature[<?=$feature['feature_id']?>][]" multiple="multiple">
													<?php foreach ($feature['values'] as $value) { ?>
														<option value="<?=$value['id']?>" <?=$value['selected']==1?'selected':''?> ><?=$value['name']?></option>
													<?php } ?>
													</select>
												<?php } ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="2">No record found</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
						<div class="p-3">
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
						</form>
					</div>
					<!-- /.card-body -->
				
				</div>
				<!-- /.card -->
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>