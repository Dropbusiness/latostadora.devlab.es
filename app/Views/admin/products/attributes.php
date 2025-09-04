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
					<div class="card-body table-responsive ">
					<h2> Combinaciones de sus productos</h2>
					<button type="button" class="btn btn-success" id="btnnewcombination">Agregar nueva combinación</button> 
					<script>
						var product_id = <?= $product['id'] ?>;
						var product_price = <?= $product['price'] ?>;
						var product_stock = <?= $product['stock'] ?>;
						var product_sku = '<?= $product['sku'] ?>';
						var product_models = <?= json_encode($productmodels) ?>;
					</script>
					<button type="button" class="btn btn-success" id="btngeneratecombinationsbymodel" onclick="generatecombinationsbymodel(this,event,<?=$product['id']?>)">Generar combinaciones por modelo</button>
					<div id="newcombination" class="border border-success p-3 d-none">
						<form role="form" method="post" action="<?= site_url('admin/products/'. $product['id'] .'/setattributes') ?>">
							<div class="row">
								<div class="col-8">
									<label>Combinaciones seleccionadas:</label>
									<input type="hidden" name="selectedCombinations" id="selectedCombinations" readonly>
									<div id="combinationsTags" class="border border-success p-3 bg-gray-light" ></div>
									<div class="row mt-2">
										<div class="form-group col-md-6">
											<label for="price">Price*</label>
											<?= form_input('price', '', ['class' => 'form-control', 'id' => 'price']) ?>
										</div>
										
										<div class="form-group col-md-6">
											<label for="stock">stock*</label>
											<?= form_input('stock',  '', ['class' => 'form-control', 'id' => 'stock']) ?>
										</div>
										<div class="form-group col-md-6">
											<label for="models_code">models_code (H_A2)</label>
											<?= form_input('models_code',  '', ['class' => 'form-control', 'id' => 'models_code']) ?>
										</div>
										<div class="form-group col-md-6">
											<label for="reference">reference</label>
											<?= form_input('reference',  '', ['class' => 'form-control', 'id' => 'reference']) ?>
										</div>

										<div class="form-group col-md-6">
											<label for="ean">ean</label>
											<?= form_input('ean',  '', ['class' => 'form-control', 'id' => 'ean']) ?>
										</div>
									</div>
									<button type="submit" class="btn btn-success mt-3">Generar combinación</button>
								</div>
								<div class="col-4">
									<div id="attributesAccordion">
										<?php foreach ($attributes as $attribute): ?>
											<div class="card">
												<div class="card-header" id="heading<?= $attribute['id'] ?>">
													<h5 class="mb-0">
														<button class="btn btn-link text-success w-100" type="button" data-toggle="collapse" data-target="#collapse<?= $attribute['id'] ?>" aria-expanded="true" aria-controls="collapse<?= $attribute['id'] ?>">
															<?= $attribute['name'] ?>
															<i class="fas fa-chevron-down float-right"></i>
														</button>
													</h5>
												</div>
												<div id="collapse<?= $attribute['id'] ?>" class="collapse" aria-labelledby="heading<?= $attribute['id'] ?>" data-parent="#attributesAccordion">
													<div class="card-body">
														<?php foreach ($attribute['values'] as $value): ?>
															<button  type="button"  class="btn btn-outline-secondary select-attribute" data-attributeid="<?= $attribute['id'] ?>" data-attribute="<?= $attribute['name'] ?>" data-value="<?= $value['name'] ?>"  data-valueid="<?= $value['id'] ?>"><?= $value['name'] ?></button>
														<?php endforeach; ?>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Combinación</th>
                    <th>Detalles</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Referencia</th>
                    <th>EAN</th>
                    <th>Por defecto</th>
					<th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($productattributes as $combination) { ?>
                    <tr>
                    <td><?=$combination['combination_id']?></td>
                    <td><?=$combination['combination_details']?></td>
                    <td><?=$combination['price']?></td>
                    <td><?=$combination['stock']?></td>
                    <td><?=$combination['reference']?></td>
                    <td><?=$combination['ean']?></td>
                    <td><?=$combination['default_on'] ? 'Sí' : 'No'?></td>
					<td><a  onclick="getattributes(this,event,<?=$combination['product_id']?>,<?=$combination['combination_id']?>)" class="btn btn-sm btn-success">Editar</a>
					<a href="#" data-url="<?= site_url('admin/products/deleteCombination/' . $combination['combination_id']) ?>" class="btn btn-sm btn-danger delete-row mt-1" >Eliminar</a></td>
                    </tr>
				<?php }
                ?>
            </tbody>
        </table>
    </div>


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

<?php $this->section('script'); ?>
    <?= script_tag('adm/js/products_attributes.js?v='.time()); ?>
<?php $this->endSection(); ?>