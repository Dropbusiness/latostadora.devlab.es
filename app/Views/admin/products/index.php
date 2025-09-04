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
			  <li class="breadcrumb-item active">Productos</li>
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
		  	<div class="col-12">
				<div class="card">
			  		<div class="card-header">
					<h3 class="card-title">Total Productos (<?=$total?>)</h3>
			  </div>
			  <!-- /.card-header -->
			  	<div class="card-body p-0">

					<!--buscador-->
					<div class="col-auto pt-3" style="max-width:650px;">
						<div class="card card-success">
							<div class="card-header">
								<h3 class="card-title">Buscador</h3>
							</div>
							<div class="card-body pt-0">
							<form method='get' action="<?= site_url('admin/products') ?>" id="searchForm">
					<div class="row ">
							<div class="col-12">
									<div class="clearfix py-1">
										<a data-toggle="collapse" href="#collapsesearch" role="button" aria-expanded="false" aria-controls="collapsesearch" class="advanced float-right ml-3"> Más opciones de busqueda <i class="fa fa-angle-down"></i> </a>
									</div>
									<div class="collapse clearfix" id="collapsesearch">
										<div class="card card-body mt-2">
											<div class="row">
											<div class="col-6">
													<div class="form-group">
														<div class="mb-2">Categorias</div>
														<div class="categoriestree">
														<div class="row">
																<div class="col">
																	<div id="ps_categoryTags" class="pstaggerTagsWrapper">
																		<?= isset($tagcategories) ? $tagcategories : '' ?>
																	</div>
																</div>
														</div>
															<?php
															$selected = isset($s_categories) ? $s_categories : '';
															echo treeCatFilter($categories,'',$selected)?>
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="form-group">
														<div class="mb-2">Eventos</div>
														<?= form_dropdown('s_events',$events,$s_events, ['class' => 'form-control']) ?>
													</div>
													<div class="form-group">
														<div class="mb-2">Marcas</div>
														<?= form_dropdown('s_brand',$brands,$s_brand, ['class' => 'form-control']) ?>
													</div>
													<div class="form-group">
														<div class="mb-2">Estado producto</div>
														<?= form_dropdown('s_status',$statuses,$s_status, ['class' => 'form-control']) ?>
													</div>
												</div>
											</div>
										</div>
									</div>
							</div>
							<div class="col-12">
								<div class="input-group input-group">
									<input type="search" class="form-control form-control" placeholder="Buscar..." name='s_name' value="<?=$s_name?>" >
									<div class="input-group-append">
										<button type="submit" class="btn btn btn-default">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</div>
								<a  href="<?= site_url('admin/products') ?>"  class="advanced float-left"> Limpiar busqueda <i class="fas fa-times"></i> </a>
						</div>
					</div>
					</form>

							</div>
						</div>
					</div>
					<!--//buscador-->

					</div>

				  <?= view('admin/shared/flash_message') ?>
					
				  	<div class="row px-3">
						<div class="col-4 text-left  py-1">
						<a target="_blank" class="text-success" href="<?= site_url('admin/products/export') ?>">
							<i class="fas fa-download"></i> Exportar lista <small>(<?php echo $total ?>)</small>
						</a>
						<a target="_blank" class="text-success pl-3" href="<?= site_url('admin/products/create') ?>">
							<i class="fas fa-plus"></i> Crear producto</small>
						</a>
						</div>
						<div class="col-8 text-right  py-1">
							<?php echo $pager ?>
						</div>
					</div>
					<form method='post' action="<?= site_url('admin/products/maintenance') ?>" id="productsForm">
					<div class="table-responsive">
					<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th><input type="checkbox" id="checkAll" /> </th>
							<th>ID</th>
							<th>SKU</th>
							<th>Image</th>
							<th>Producto</th>
							<th>Category</th>
							<th>Price</th>
							<th>Status</th>
							<th>Posición</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($alldata): ?>
							<?php foreach ($alldata as $product): ?>
								<tr>
									<td width="50" class="text-center"><input type="checkbox" class="pck" name="products[<?=$product['id']?>]" value="<?=$product['id']?>"></td>
									<td><?=$product['id']?></td>
									<td><?=$product['sku']?></td>
									<td><?= !empty($product['img']) ? '<img src="/uploads/products/small/'. $product['img']. '">' : null ?></td>
									<td><?= $product['name'] ?></td>
									<td><?=(isset($listcategories[$product['id_category_default']])?$listcategories[$product['id_category_default']]['name']:'-') ?> <small>(<?=$product['id_category_default']?>)</small></td>
									<td><?= $product['price'] ?></td>
									<td>
										<?= isset($statuses[$product['status']])?$statuses[$product['status']]:'' ?>
									</td>
									<td><?= $product['position'] ?></td>
									<td  width="100">
											<a href="<?= site_url('admin/products/'. $product['id'] .'/edit') ?>" class="btn btn-info  mr-3"><i class="far fa-edit"></i></a>
											<a  class="btn btn-danger delete-row" href="#" data-url="<?= site_url('admin/products/delete/'. $product['id']) ?>" ><i class="far fa-trash-alt"></i></a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="11">No record found</td>
							</tr>
						<?php endif; ?>
					</tbody>
					</table>
					</div>
					<div class="row px-3">
						<div class="col-6 text-left py-3">
									<div class="clearfix py-2">
										<a data-toggle="collapse" href="#collapacciona" role="button" aria-expanded="false" aria-controls="collapacciona" class="advanced"> Acciones agrupadas <i class="fa fa-angle-down"></i> </a>
									</div>
									<div class="collapse clearfix" id="collapacciona">
										<div class="card card-body mt-2">
											<div class="row">
												<div class="col mb-3  text-right">
														<button type="submit"  class="btn btn btn-danger" name="btndelprods">Eiminar productos seleccionados</button>
												</div>
											</div>
											<div class="row">
												<div class="col-12">
													<h4 class="border-bottom border-success">Asociar productos con categorias</h4>
												</div>
												<div class="col-4">
													<label>Categorias <small>(ejemplo: 3003,5011,...)</small></label>
													<input type="text" class="form-control" name="m_categories" value="">
												</div>
												<div class="col-4">
													<label>Categoria principal/default <small>(ejemplo: 5011)</small></label>
													<input type="text" class="form-control" name="m_category" value="">
												</div>
												<div class="col-4">
													<label>Tipo</label>
													<select class="form-control" name="m_tipo">
														<option value="combinar">Combinar con categorías actuales</option>
														<option value="reemplazar">Mover productos a categorías</option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col pt-3 text-right">
														<button type="submit" class="btn btn btn-success" name="btnupprods">Actualizar productos/categorias</button>
												</div>
											</div>
										</div>
									</div>
						</div>
						<div class="col-6 text-right  py-3">
							<?php echo $pager ?>
						</div>
					</div>
					</form>
					</div>
			</div>
			<!-- /.card -->
		  	</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>