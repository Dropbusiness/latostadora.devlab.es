<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Categoría</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Categoría</li>
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
					<h3 class="card-title"><?= isset($data) ? 'Actualizar' : 'Nueva' ?> Categoría</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<?php if (!empty($data['id'])): ?>
					<form role="form" method="post" action="<?= site_url('admin/category/update/'. $data['id']) ?>"  enctype="multipart/form-data"  class="needs-validation" novalidate>
					<input name="_method" type="hidden" value="PUT">
				<?php else: ?>
					<form role="form" method="post" action="<?= site_url('admin/category/save') ?>"  enctype="multipart/form-data"  class="needs-validation" novalidate>
				<?php endif; ?>

					<input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : null ?>"/>
					<div class="card-body">
						<?= view('admin/shared/flash_message') ?>
						<?=input_multilanguage([
							'name'=>'name',
							'label'=>'Nombre',
							'values'=>$data['multilanguage']['name'],
							'required'=>true,
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>

						<div class="form-group">
							<label for="parentCategory">Categoría padre*</label>
							<div class="categoriestree">
								<div class="row">
										<div class="col">
										<div id="ps_categoryTags" class="pstaggerTagsWrapper">
											<?php
											if(isset($categorienav) && isset($data['id'])){?>
											<span class="pstaggerTag"><?=$categorienav?></span>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">Categorias</div>
								</div>
								<?php
								$selected = !(empty($data['parent_id'])) ? $data['parent_id'] : "";
								echo treeCategories($categories,'',$selected)?>
							</div>
						</div>
						<?=input_multilanguage([
							'name'=>'description_short',
							'label'=>'Descripción corta',
							'values'=>$data['multilanguage']['description_short'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							'type'=>'textarea',
							])?>
							<?=input_multilanguage([
							'name'=>'description',
							'label'=>'Descripción',
							'values'=>$data['multilanguage']['description'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							'type'=>'textarea',
							])?>
						<div class="form-group">
								<label for="img">Imagen</label>
								<?= form_upload('image', '' , ['class' => 'form-control', 'id' => 'image']) ?>
								<?php if(isset($data['img']) && $data['img']!=''){?><img src="/uploads/categories/small/<?=$data['img']?>" class="thumbnails"/> <a class="small" href="<?= site_url('admin/category/imgdel?col=img&id='.$data['id']) ?>" ><i class="far fa-trash-alt"></i> Eliminar</a><?php }?>
						</div>
						<?=input_multilanguage([
							'name'=>'link_rewrite',
							'label'=>'link_rewrite   <small>solo letras (A-Z, a-z), números (0-9) y guiones (-)</small>',
							'values'=>$data['multilanguage']['link_rewrite'],
							'required'=>true,
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							'class'=>'form-control link_rewrite',
							])?>
							<?=input_multilanguage([
							'name'=>'meta_title',
							'label'=>'meta_title',
							'values'=>$data['multilanguage']['meta_title'],
							'required'=>true,
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
							<?=input_multilanguage([
							'name'=>'meta_keywords',
							'label'=>'meta_keywords',
							'values'=>$data['multilanguage']['meta_keywords'],
							'required'=>false,
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
							<?=input_multilanguage([
							'name'=>'meta_description',
							'label'=>'meta_description',
							'values'=>$data['multilanguage']['meta_description'],
							'required'=>false,
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
						<div class="form-group">
							<label for="meta_title">position*</label>
							<?= form_input('position', set_value('position', isset($data['position']) ? ($data['position']) : '' ), ['class' => 'form-control', 'id' => 'position']) ?>
						</div>
						<div class="form-group">
							<label for="siteSku">Status*</label>
							<?= form_dropdown('status',$statuses, set_value('status', isset($data['status']) ? ($data['status']) : '' ), ['class' => 'form-control', 'id' => 'status', 'required' => true]) ?>
						</div>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
						<a href="<?= site_url('admin/category') ?>" class="btn btn-default">Volver</a>
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