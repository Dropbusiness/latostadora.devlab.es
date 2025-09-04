<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Marca</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Marca</li>
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
					<h3 class="card-title"><?= !empty($data['id']) ? 'Actualizar' : 'Nueva' ?> Marca</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<?php if (!empty($data['id'])): ?>
					<form role="form" method="post" action="<?= site_url('admin/brand/update/'. $data['id']) ?>"  enctype="multipart/form-data">
					<input name="_method" type="hidden" value="PUT">
				<?php else: ?>
					<form role="form" method="post" action="<?= site_url('admin/brand/save') ?>"  enctype="multipart/form-data">
				<?php endif; ?>
					<input type="hidden" name="id" value="<?=$data['id']?>"/>
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
						<?=input_multilanguage([
							'name'=>'description',
							'label'=>'Description',
							'values'=>$data['multilanguage']['description'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							'type'=>'textarea',
							])?>
							<div class="form-group">
									<label for="img">Imagen*</label>
									<?= form_upload('image', '' , ['class' => 'form-control', 'id' => 'image']) ?>
									<?php if(isset($data['img']) && $data['img']!=''){?><img src="/uploads/brands/small/<?=$data['img']?>" class="thumbnails"/><?php }?>
							</div>
							<?=input_multilanguage([
							'name'=>'link_rewrite',
							'label'=>'link_rewrite',
							'values'=>$data['multilanguage']['link_rewrite'],
							'required'=>true,
							'lang'=>$lang,
							'languages'=>$languages['codes'],
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
								<label for="siteSku">Status*</label>
								<?= form_dropdown('status',$statuses, set_value('status', isset($data['status']) ? ($data['status']) : '' ), ['class' => 'form-control', 'id' => 'status']) ?>
							</div>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
						<a href="<?= site_url('admin/brand') ?>" class="btn btn-default">Volver</a>
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