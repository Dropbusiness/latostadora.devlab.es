<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1><?php echo lang('Auth.create_user_heading');?></h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Usuarios</li>
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
					<h3 class="card-title"><?php echo lang('Auth.create_user_heading');?></h3>
				</div>
				<?php echo form_open('admin/users/create');?>
				<!-- /.card-header -->
					<div class="card-body">
						<!-- main body -->
<p><?php echo lang('Auth.create_user_subheading');?></p>
<div id="infoMessage"><?php echo $message;?></div>
	<p>
		<?php echo form_label(lang('Auth.create_user_fname_label'), 'first_name');?> <br />
		<?php echo form_input($firstName);?>
	</p>
	<p>
		<?php echo form_label(lang('Auth.create_user_lname_label'), 'last_name');?> <br />
		<?php echo form_input($lastName);?>
	</p>
	<?php
	if ($identityColumn !== 'email')
	{
		echo '<p>';
		echo form_label(lang('Auth.create_user_identity_label'), 'identity');
		echo '<br />';
		echo form_error('identity');
		echo form_input($identity);
		echo '</p>';
	}
	?>
	<p>
		<?php echo form_label(lang('Auth.create_user_company_label'), 'company');?> <br />
		<?php echo form_input($company);?>
	</p>
	<p>
		<?php echo form_label(lang('Auth.create_user_email_label'), 'email');?> <br />
		<?php echo form_input($email);?>
	</p>
	<p>
		<?php echo form_label(lang('Auth.create_user_phone_label'), 'phone');?> <br />
		<?php echo form_input($phone);?>
	</p>
	<p>
		<?php echo form_label(lang('Auth.create_user_password_label'), 'password');?> <br />
		<?php echo form_input($password);?>
	</p>
	<p>
		<?php echo form_label(lang('Auth.create_user_password_confirm_label'), 'password_confirm');?> <br />
		<?php echo form_input($passwordConfirm);?>
	</p>
	<p></p>

	<?php if ($ionAuth->isAdmin()): ?>

<h3><?php echo lang('Auth.edit_user_groups_heading');?></h3>
<?php foreach ($groups as $group):?>
	<label class="checkbox">
	<?php
	$gID     = (int)$group['id'];
	$checked = ($gID==1)?' checked="checked"':null;
	$item    = null;
	?>
	<input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
	<?= esc($group['name']) ?>
	</label>
<?php endforeach?>

<?php endif ?>
					<!--/. main body -->
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
					<?php echo form_submit('submit', lang('Auth.create_user_submit_btn'),['class'=>'btn btn-primary']);?>
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



