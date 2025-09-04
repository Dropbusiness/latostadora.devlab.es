<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Producto</h1>
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
			<?php if (isset($product['id']) && $product['id']>0): ?>
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
					<h3 class="card-title"><?= (isset($product['id']) && $product['id']>0) ? 'Actualizar' : 'Nuevo' ?> Producto</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<?php if (isset($product['id']) && $product['id']>0): ?>
					<form role="form" method="post" action="<?= site_url('admin/products/'. $product['id']) ?>"  class="needs-validation" novalidate>
					<input name="_method" type="hidden" value="PUT">
				<?php else: ?>
					<form role="form" method="post" action="<?= site_url('admin/products') ?>"  class="needs-validation" novalidate>
				<?php endif; ?>
					<input type="hidden" name="id" value="<?= isset($product['id']) ? $product['id'] : null ?>"/>
					<input type="hidden" name="minimal_quantity" value="1">
					<div class="card-body">
						<?= view('admin/shared/flash_message') ?>

						<div class="form-group">
							<label for="productSku">SKU</label>
							<?= form_input('sku', set_value('sku', isset($product['sku']) ? ($product['sku']) : '' ), ['class' => 'form-control', 'id' => 'productSku', 'placeholder' => 'Enter product sku']) ?>
						</div>
						
						
						<?=input_multilanguage([
							'name'=>'name',
							'label'=>'Nombre',
							'values'=>$product['multilanguage']['name'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
						
						
						<div class="form-group">
							<label for="parentCategory">Categoría*</label>
							<div class="categoriestree">
								<div class="row">
										<div class="col-6">
											<div id="ps_categoryTags" class="pstaggerTagsWrapper">
												<?= isset($tagcategories) ? $tagcategories : '' ?>
											</div>
										</div>
										<div class="col-6  text-right">
											<div id="ps_categoryTags" class="pstaggerTagsWrapper">
												<?= isset($tagcategoryd) ? $tagcategoryd : '' ?>
											</div>
										</div>
								</div>
								<div class="row">
									<div class="col-6">Categorias*</div>
									<div class="col-6 text-right" >Principal*</div>
								</div>
								<?php
								$selected = isset($categoryIds) ? $categoryIds : '';
								$principal = isset($product['id_category_default'])? $product['id_category_default'] : '';
								echo treeCatProd($categories,'',$selected,$principal)?>
							</div>
						</div>
						<div class="form-group">
                            <label for="brand">Marca</label>
                            <?= form_dropdown('brand_id', $brands, set_value('brand_id', isset($product['brand_id']) ? ($product['brand_id']) : '' ), ['class' => 'form-control', 'id' => 'brand_id']) ?>
                        </div>

						<div class="form-group">
                            <label for="brand">Eventos*</label>
							<select class="select2-multiple w-100" name="events[]" multiple="multiple" required>
								<?php  foreach ($events as $id => $name) { ?>
										<option value="<?=$id?>"   <?=isset($eventsIds[$id])?'selected':''?> ><?=$name?></option>
								<?php } ?>
							</select>
                        </div>

						<div class="form-group">
							<label for="price">Price*</label>
							<?= form_input('price', set_value('price', isset($product['price']) ? ($product['price']) : '' ), ['class' => 'form-control', 'id' => 'price']) ?>
						</div>
						
						<div class="form-group">
							<label for="stock">stock</label>
							<?= form_input('stock', set_value('stock', isset($product['stock']) ? ($product['stock']) : '' ), ['class' => 'form-control', 'id' => 'stock']) ?>
						</div>

						<?=input_multilanguage([
							'name'=>'description_short',
							'label'=>'Descripción corta',
							'values'=>$product['multilanguage']['description_short'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							'type'=>'textarea',
							])?>
						<?=input_multilanguage([
							'name'=>'description',
							'label'=>'Descripción',
							'values'=>$product['multilanguage']['description'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							'type'=>'textarea',
							])?>
						
						<?=input_multilanguage([
							'name'=>'link_rewrite',
							'label'=>'link_rewrite   <small>solo letras (A-Z, a-z), números (0-9) y guiones (-)</small>',
							'values'=>$product['multilanguage']['link_rewrite'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							'class'=>'form-control link_rewrite',
							])?>
							<?=input_multilanguage([
							'name'=>'meta_title',
							'label'=>'meta_title',
							'values'=>$product['multilanguage']['meta_title'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
							<?=input_multilanguage([
							'name'=>'meta_keywords',
							'label'=>'meta_keywords <small>Usa comas para separar los tags. Ej.: vestido, algodón, vestidos de fiesta.</small>',
							'values'=>$product['multilanguage']['meta_keywords'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
							<?=input_multilanguage([
							'name'=>'meta_description',
							'label'=>'meta_description',
							'values'=>$product['multilanguage']['meta_description'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
						<div class="form-group">
							<label for="group_showsize">Guía de medidas</label>
							<?= form_dropdown('group_showsize', $guiatallas, set_value('group_showsize', isset($product['group_showsize']) ? ($product['group_showsize']) : '' ), ['class' => 'form-control', 'id' => 'group_showsize']) ?>
						</div>
						<div class="form-group">
							<label for="productPrice">Posición</label>
							<?= form_input('position', set_value('position', isset($product['position']) ? ($product['position']) : '' ), ['class' => 'form-control', 'id' => 'position']) ?>
						</div>
						<div class="form-group">
							<label for="productStatus">Estado producto</label>
							<?= form_dropdown('status', $statuses, set_value('status', isset($product['status']) ? ($product['status']) : '' ), ['class' => 'form-control', 'id' => 'productStatus']) ?>
						</div>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
						<?php if (!empty($product)): ?>
							<a href="<?= site_url('producto/'.$product['multilanguage']['link_rewrite'][$lang_id]) ?>" class="btn btn-default" target="_black"><i class="fas fa-search-plus"></i> Vista previa</a>
							<a href="<?= site_url('admin/products') ?>" class="btn btn-default"><i class="fas fa-angle-double-left"></i> Volver lista</a>
						<?php endif;?>
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
