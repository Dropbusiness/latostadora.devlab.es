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
		  	<div class="col-12">
				<div class="card">
			  		<div class="card-header">
					<h3 class="card-title">Contact</h3>
					<div class="card-tools">
						<form method='get' action="<?= site_url('admin/contact') ?>" id="searchForm">
						<div class="input-group input-group-sm" style="width: 150px;">
							<input type="text"  class="form-control float-right" placeholder="Buscar..." name='s_name' value="<?=$s_name?>">
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
						<div class="col-12 text-right  py-3">
							<?php echo $pager ?>
						</div>
					</div>
					<div class="table-responsive">
					<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>Id</th>
                            <th >Nombre</th>
                            <th >Email</th>
							<th >Teléfono</th>
							<th >Boletín</th>
							<th >Fecha</th>
							<th>Tipo</th>
                            <th>Estado</th>
                            <th>Acción</th>
						</tr>
					</thead>
					<tbody>
                        <?php
                        $i = 0;
                        foreach ($alldata as $item) {
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $item['first_name'] ?> <?php echo $item['last_name'] ?></td>
								<td><?php echo $item['email'] ?></td>
								<td><?php echo $item['phone'] ?></td>
								<td><?php echo ($item['optin']?'Si':'No') ?></td>
								<td><?php echo $item['created_at'] ?></td>
								<td class="center">
                                    <?php echo isset($ctype[$item['ctype']])?$ctype[$item['ctype']]:$item['ctype']; ?>
                                </td>
                                <td class="center">
                                    <?php echo isset($statuses[$item['status']])?$statuses[$item['status']]:$item['status']; ?>
                                </td>
                                <td class="center">
                                    <a class="btn btn-info" href="<?php echo base_url('admin/contact/show/'.$item['id']); ?>">
									<i class="fas fa-search-plus"></i>
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
						<div class="col-12 text-right  py-3">
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