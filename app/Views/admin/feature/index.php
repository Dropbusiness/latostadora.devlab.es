<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Grupo características</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Grupo características</li>
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
					<h3 class="card-title">Grupo características</h3>
					<div class="card-tools">
						<form method='get' action="<?= site_url('admin/feature') ?>" id="searchForm">
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
			  	<div class="card-body  p-0">
				  	<?= view('admin/shared/flash_message') ?>
					  <div class="row px-3">
						<div class="col-6 text-left py-3">
						<a target="_blank" class="btn btn-primary" href="<?= site_url('admin/feature/export') ?>"><i class="fas fa-download"></i> Exportar <small>(<?php echo $total ?>)</small></a>  <a href="<?= site_url('admin/feature/add') ?>" class="btn btn-success">Crear nuevo</a>
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
                            <th >Nombre</th>
                            <th>Orden</th>
							<th>Estado</th>
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
                                <td><?=$item['id'] ?></td>
                                <td><?php echo $item['name'] ?></td>
                                <td class="text-center">
                                    <?php echo $item['position']; ?>
                                </td>
								<td>
									<?= isset($statuses[$item['status']])?$statuses[$item['status']]:'' ?>
								</td>
                                <td class="text-right"  width="100">
                                    <a class="btn btn-info  mr-3" href="<?php echo base_url('admin/feature/edit/' . $item['id']); ?>">
									<i class="far fa-edit"></i>
                                    </a>
                                    <a class="btn btn-danger delete-row" href="#" data-url="<?php echo base_url('admin/feature/delete/' . $item['id']); ?>">
										<i class="far fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
					</table>
			  	</div>
				<!-- /.card-body -->
				<div class="card-footer clearfix">
				<div class="row px-3">
						<div class="col-6 text-left py-3">
						<a target="_blank" class="btn btn-primary" href="<?= site_url('admin/feature/export') ?>"><i class="fas fa-download"></i> Exportar <small>(<?php echo $total ?>)</small></a>  <a href="<?= site_url('admin/feature/add') ?>" class="btn btn-success">Crear nuevo</a>
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