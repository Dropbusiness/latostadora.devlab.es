<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1><?php echo lang('Auth.edit_group_heading');?></h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Users</li>
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
					<h3 class="card-title"><?php echo lang('Auth.edit_group_heading');?></h3>
				</div>
				<!-- /.card-header -->
				<?php echo form_open(current_url());?>
					<div class="card-body">
						<!-- main body -->
<p><?php echo lang('Auth.edit_group_subheading');?></p>
<div id="infoMessage"><?php echo $message;?></div>
	<p>
		<?php echo form_label(lang('Auth.edit_group_name_label'), 'group_name');?> <br />
		<?php echo form_input($groupName);?>
	</p>
	<p>
		<?php echo form_label(lang('Auth.edit_group_desc_label'), 'description');?> <br />
		<?php echo form_input($groupDescription);?>
	</p>
					<!--/. main body -->
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
					<?php echo form_submit('submit', lang('Auth.edit_group_submit_btn'),['class'=>'btn btn-primary']);?>
					<a href="<?= site_url('admin/users') ?>" class="btn btn-default">Cancel</a>
					</div>
					<?php echo form_close();?>
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