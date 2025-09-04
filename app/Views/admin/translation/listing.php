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
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4><?= admin_translate('Translation', 'language_select'); ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <?php foreach (bo_language() as $key => $lang) { ?>
                                        <a href="javascript:void(0)"
                                           data-url="<?= base_url(route_to('admin_translation_folder_listing')); ?>"
                                           data-lang="<?= $lang['code']; ?>"
                                           class="list-group-item list-group-item-action language-select">
                                            <img style="width: 30px; margin-right: 10px;" src="/uploads/languages/<?= $lang['img']; ?>" alt="">
                                            <?= $lang['name'] ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4><?= admin_translate('Translation', 'folder_select'); ?></h4>
                            </div>
                            <div class="card-body" id="folder_list"></div>
                        </div>
                    </div>
                </div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>
<?php $this->section('script'); ?>
    <?= script_tag('adm/js/translation.js'); ?>
<?php $this->endSection(); ?>

