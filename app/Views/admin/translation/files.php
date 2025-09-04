<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1><?= admin_translate('Translation', 'page_title'); ?></h1> 
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active"><?= admin_translate('Translation', 'page_title'); ?></li>
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
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($files as $key => $value) { ?>
                            <?php $file = include $path . $value ?>
                            <?php $name = str_replace('.php', '', $value)?>
                                <a href="<?= base_url(route_to('admin_translation_translate', $lang, $folder, $name)); ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1" style="font-weight: 600; font-size: 19px;"><?= stripslashes(lang($folder . '/' . $name . '.title')); ?></h5>
                                    </div>
                                    <p class="mb-1"><?= stripslashes(lang($folder . '/' . $name . '.title')); ?></p>
                                </a>
                        <?php } ?>
                    </div>
                </div>
                 <div class="card-footer text-right">
                        <a href="<?= base_url(route_to('admin_translation_listing')); ?>" class="btn btn-default">Volver</a>
                    </div>
            </div>
			<!-- /.card -->
		  	</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>