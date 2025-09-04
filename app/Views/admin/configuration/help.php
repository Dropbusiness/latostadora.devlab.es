<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Ayudas</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Ayudas</li>
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
					<h3 class="card-title">Ayudas</h3>
			  </div>
			  <!-- /.card-header -->
			  	<div class="card-body">
                    <div class="row">
                    <div class="col-12 col-lg-6">
                         <h5>Sincronizar  </h5>
                         
						 <ul>
							<li>
									<p>
									<strong>Actualizar buscador</strong>
										<br><a href="<?=site_url('elasticsearch')?>" target="_black"><?=site_url('elasticsearch')?></a>
									</p>
								</li>
						 </ul>
                    </div>
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