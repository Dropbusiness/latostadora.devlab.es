<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Contact</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Contact</li>
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
					<h3 class="card-title">Contact</h3>
				</div>
				<!-- /.card-header -->
			
					<div class="card-body">
						<div class="">
							<b>data:</b> <?= $data['created_at'] ?> <br/>
							<?php if($data['customer_id']>0){ ?><b>Cliente con ID:</b> <?= $data['customer_id'] ?> <br/><?php } ?>
							<b>first name:</b> <?= $data['first_name'] ?> <br/>
							<b>last name:</b> <?= $data['last_name'] ?> <br/>
							<b>phone:</b> <?= $data['phone'] ?> <br/>
							<b>email:</b> <?= $data['email'] ?> <br/>
							<b>optin:</b> <?=($data['optin']?'Si':'No') ?> <br/>
							<b>Tipo formulario:</b> <?php echo isset($ctype[$data['ctype']])?$ctype[$data['ctype']]:$data['ctype']; ?><br/>
							<b>Status:</b> <?= $statuses[$data['status']] ?><br/>
							<?php if(isset($files) && is_array($files) && count($files)){?> <b>Files:</b><br/> <?php foreach ($files as $i => $v) {?> <a href="/uploads/contact/<?=$v['file'] ?>" target="_black"><?=$v['file'] ?></a> <br/><?php } ?><?php } ?>
							<b>message:</b> <br/><?= $data['message'] ?> <br/>
						</div>
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
						<a href="<?= site_url('admin/contact') ?>" class="btn btn-default">Volver</a>
					</div>
				
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