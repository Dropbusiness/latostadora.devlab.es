<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Template email</h1> 
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Template email</li>
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
					<h3 class="card-title">Template email <small>(<?php echo $total ?>)</small></h3>
					<div class="card-tools">
						<form method='get' action="<?= site_url('admin/templateemail') ?>" id="searchForm">
						<div class="input-group input-group-sm" style="width: 150px;">
							<input type="text" class="form-control float-right" placeholder="Buscar..." name='s_name' value="<?=$s_name?>" >
							<div class="input-group-append">
								<button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
							</div>
						</div>
						</form>
					</div>
			  		</div>
			  <!-- /.card-header -->
			  	<div class="card-body p-0">
				  	<?= view('admin/shared/flash_message') ?>
					  <div class="row px-3">
						<div class="col-6 text-left py-3">
							<a href="<?= site_url('admin/templateemail/add') ?>" class="btn btn-success">Crear nuevo</a>
						</div>
						<div class="col-6 text-right  py-3">
							<?php echo $pager ?>
						</div>
					</div>
					<div class="table-responsive">
					<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>Id</th>
                            <th>Name</th>
							<th >subject</th>
                            <th>Status</th>
                            <th>Actions</th>
						</tr>
					</thead>
					<tbody>
                        <?php
                        $i = 0;
                        foreach ($alldata as $item) {
                            $i++;
                            ?>
                            <tr>
                                <td><?=$item['id']?></td>
                                <td><?php echo $item['name'] ?></td>
								<td><?php echo $item['subject'] ?></td>
                                <td class="text-center" width="100"><?php echo isset($statuses[$item['status']])?$statuses[$item['status']]:$item['status']; ?></td>
                                <td class="text-center" width="100">
                                    <a class="btn btn-info  mr-3" href="<?php echo base_url('admin/templateemail/edit/' . $item['id']); ?>">
										<i class="far fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
					</table>
					</div>
			  	</div>
				<!-- /.card-body -->
				<div class="card-footer clearfix">
					<div class="row px-3">
						<div class="col-6 text-left py-3">
							<a href="<?= site_url('admin/templateemail/add') ?>" class="btn btn-success">Crear nuevo</a>
							
						</div>
						<div class="col-6 text-right  py-3">
							
							<?php echo $pager ?>
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