<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1><?php echo lang('Auth.index_heading');?></h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active"><?php echo lang('Auth.index_heading');?></li>
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
							<h3 class="card-title"><?php echo lang('Auth.index_heading');?></h3>
			  		</div>
					  <div class="card-body table-responsive p-0">
							<!-- main body -->
							<div id="infoMessage"><?php echo $message;?></div>
							<table class="table table-bordered table-hover dataTable">
								<tr>
									<th><?php echo lang('Auth.index_fname_th');?></th>
									<th><?php echo lang('Auth.index_lname_th');?></th>
									<th><?php echo lang('Auth.index_email_th');?></th>
									<th><?php echo lang('Auth.index_groups_th');?></th>
									<th><?php echo lang('Auth.index_status_th');?></th>
									<th><?php echo lang('Auth.index_action_th');?></th>
								</tr>
								<?php foreach ($users as $user):?>
									<tr>
										<td><?php echo esc($user->first_name);?></td>
										<td><?php echo esc($user->last_name);?></td>
										<td><?php echo esc($user->email);?></td>
										<td>
											<?php foreach ($user->groups as $group):?>
												<?php echo anchor('admin/users/edit_group/' . $group->id, esc($group->name)); ?><br>
											<?php endforeach?>
										</td>
										<td>
											<?php
											echo ($user->active) ?
												anchor('admin/users/deactivate/' . $user->id, lang('Auth.index_active_link')) :
												anchor('admin/users/activate/' . $user->id, lang('Auth.index_inactive_link'));
											?>
										</td>
										<td><?php echo anchor('admin/users/edit/' . $user->id, 'Editar') ;?></td>
									</tr>
								<?php endforeach;?>
							</table>
					<!--/. main body -->
					</div>
				<!-- /.card-body -->
				<div class="card-footer clearfix">
					<div class="row">
						<div class="col-12">
							<?php echo anchor('admin/users/create', lang('Auth.index_create_user_link'),['class'=>'btn btn-primary'])?>
							<?php echo anchor('admin/users/create_group', lang('Auth.index_create_group_link'),['class'=>'btn btn-primary'])?>
						</div>
					</div>
				</div>
			</div>
			<!-- /.card -->
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>