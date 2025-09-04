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
		  	<div class="col-md-12">
              <?= view('admin/shared/flash_message') ?>
				<form action="<?= current_url(); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?= admin_translate('Input', 'title') ?></label>
                            <textarea style="border-color: #394eea; height: 60px;" name="translate[title]" class="form-control"><?= stripslashes($strings['title']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?= admin_translate('Input', 'description') ?></label>
                            <textarea style="border-color: #394eea; height: 60px;" name="translate[description]" class="form-control"><?= stripslashes($strings['description']); ?></textarea>
                        </div>
                        <?php foreach ($strings['text'] as $key => $value) { ?>
                            <div class="form-group">
                                <label><?= $key; ?></label>
                                <textarea style="border-color: #394eea; height: 60px;" name="translate[text][<?= $key; ?>]" class="form-control"><?= stripslashes($value); ?></textarea>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="card-footer text-right">
                        <a href="<?= base_url(route_to('admin_translation_files', $lang, $folder)); ?>" class="btn btn-default">Volver</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>
<?php $this->section('script'); ?>
    <?= script_tag('adm/js/translation.js'); ?>
<?php $this->endSection(); ?>

