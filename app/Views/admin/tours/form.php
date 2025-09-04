<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Tour</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Tour</li>
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
					<h3 class="card-title"><?= !empty($data['id']) ? 'Actualizar' : 'Nueva' ?> Tour</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<?php if (!empty($data['id'])): ?>
					<form role="form" method="post" action="<?= site_url('admin/tours/update/'. $data['id']) ?>"  enctype="multipart/form-data">
					<input name="_method" type="hidden" value="PUT">
				<?php else: ?>
					<form role="form" method="post" action="<?= site_url('admin/tours/save') ?>"  enctype="multipart/form-data">
				<?php endif; ?>
					<input type="hidden" name="id" value="<?=$data['id']?>"/>
					<div class="card-body">
						<?= view('admin/shared/flash_message') ?>
						<div class="form-group">
    <label for="artists_id">Artista*</label>
    <?= form_dropdown('artists_id', $artists, set_value('artists_id', isset($data['artists_id']) ? $data['artists_id'] : ''), ['class' => 'form-control', 'id' => 'artists_id','required'=>true]) ?>
</div>

						<?=input_multilanguage([
							'name'=>'name',
							'label'=>'Nombre',
							'values'=>$data['multilanguage']['name'],
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
							<?=input_multilanguage([
							'name'=>'meta_title',
							'label'=>'meta_title',
							'values'=>$data['multilanguage']['meta_title'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
							<?=input_multilanguage([
							'name'=>'meta_description',
							'label'=>'meta_description',
							'values'=>$data['multilanguage']['meta_description'],
							'lang'=>$lang,
							'languages'=>$languages['codes'],
							])?>
							<div class="form-group">
									<label for="img">Imagen* <small> Recomendacion tamaño ancho:1300px y alto:300px</small></label>
									<?= form_upload('image', '' , ['class' => 'form-control', 'id' => 'image']) ?>
									<?php if(isset($data['img']) && $data['img']!=''){?><img src="/uploads/tours/small/<?=$data['img']?>" class="thumbnails"/><?php }?>
							</div>
							<div class="form-group">
									<label for="img_e">Imagen Email* <small> Recomendacion tamaño ancho:600px y alto:300px</small></label>
									<?= form_upload('image2', '' , ['class' => 'form-control', 'id' => 'image2']) ?>
									<?php if(isset($data['img_e']) && $data['img_e']!=''){?><img src="/uploads/tours/small/<?=$data['img_e']?>" class="thumbnails"/><?php }?>
							</div>
							<div class="form-group">
								<label for="custom_email">Emails para reports (separar por comas)</label>
								<?= form_input('custom_email', set_value('custom_email', isset($data['custom_email']) ? ($data['custom_email']) : '' ), ['class' => 'form-control', 'id' => 'custom_email']) ?>
							</div>
							<div class="form-group">
								<label for="custom_contact">Nombre para Reports</label>
								<?= form_input('custom_contact', set_value('custom_contact', isset($data['custom_contact']) ? ($data['custom_contact']) : '' ), ['class' => 'form-control', 'id' => 'custom_contact']) ?>
							</div>
							<div class="form-group">
								<label for="custom_sendemail">Enviar email reports</label>
								<?= form_dropdown('custom_sendemail', [0 => 'No', 1 => 'Si'], set_value('custom_sendemail', isset($data['custom_sendemail']) ? ($data['custom_sendemail']) : '' ), ['class' => 'form-control', 'id' => 'custom_sendemail']) ?>
							</div>
								
							<div class="form-group">
								<label for="siteSku">Status*</label>
								<?= form_dropdown('status',$statuses, set_value('status', isset($data['status']) ? ($data['status']) : '' ), ['class' => 'form-control', 'id' => 'status','required'=>true]) ?>
							</div>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
						<a href="<?= site_url('admin/tours') ?>" class="btn btn-default">Volver</a>
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