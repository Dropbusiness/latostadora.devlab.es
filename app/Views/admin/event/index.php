<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Eventos</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?=site_url('admin/dashboard') ?>">Eventos</a></li>
			  <li class="breadcrumb-item active">Eventos</li>
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
					<h3 class="card-title">Eventos</h3>
					<div class="card-tools">
						<form method='get' action="<?= site_url('admin/event') ?>" id="searchForm">
							<div class="form-group">
								<div class="input-group input-group-sm" style="width: 150px;">
									<input type="text" class="form-control float-right" placeholder="Buscar..." name='s_name' value="<?=$s_name?>" >
									<div class="input-group-append">
										<button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
									</div>
								</div>
							</div>
						</form>
					</div>
			  </div>
			  <!-- /.card-header -->
			  	<div class="card-body  p-0">
				  	<?= view('admin/shared/flash_message') ?>
					  <div class="row px-3">
						<div class="col-9">
							<div class="row">
								<div class="col-3  py-3"><a href="<?= site_url('admin/event/add') ?>" class="btn btn-success">Crear nuevo evento</a></div>
								<div class="col-3  py-3"><?= form_dropdown('s_artist',[''=>'Todos los artistas']+$artists,$s_artist, ['class' => 'form-control','id'=>'s_artist','onchange'=>'filterevents(this,event)']) ?></div>
								<div class="col-3  py-3"><?= form_dropdown('s_tour',[''=>'Todos los tours']+$tours,$s_tour, ['class' => 'form-control','id'=>'s_tour','onchange'=>'filterevents(this,event)']) ?></div>
								<div class="col-3  py-3"><?= form_dropdown('s_status',[''=>'Todos los status']+$statuses,$s_status, ['class' => 'form-control','id'=>'s_status','onchange'=>'filterevents(this,event)']) ?></div>
							</div>
						</div>

						

						<div class="col-3 text-right  py-3">
							<?=$pager ?>
						</div>
					</div>
					<div class="table-responsive">
					<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>Id</th>
							<th >Artista/Tours</th>
							<th >Ciudad</th>
							<th >Fecha</th>
                            <th>Status</th>
                            <th>Actions</th>
						</tr>
					</thead>
					<tbody>
                        <?php
                        $i = 0;
                        foreach ($alldata as $item) {
                            $i++;
							$enlace=getenv('app.baseURL').'event/'.$item['artist_slug']."/".$item['tour_slug']."/".$item['slug']."/".$item['date'];
                            ?>
                            <tr>
                                <td><?=$item['id']; ?></td>
                                <td>
									<?=isset($tours[$item['tour_id']])?$tours[$item['tour_id']]:$item['tour_id']?><br/>
									<b>Enlace:</b> <a href="<?=$enlace?>" target="_blank"><?=$enlace?></a>
								</td>
								<td><?=$item['city'] ?></td>
								<td><?=$item['date'] ?></td>
                                <td class="center">
                                    <?=isset($statuses[$item['status']])?$statuses[$item['status']]:$item['status']; ?>
                                </td>
                                <td class="center"  width="100">
                                    <a class="btn btn-info" href="<?=base_url('admin/event/edit/' . $item['id']); ?>">
									<i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-danger delete-row" href="#" data-url="<?=base_url('admin/event/delete/' . $item['id']); ?>">
										<i class="far fa-trash-alt"></i>
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
								<a href="<?= site_url('admin/event/add') ?>" class="btn btn-success">Crear nuevo</a>
							</div>
							<div class="col-6 text-right  py-3">
								<?=$pager ?>
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
<?= $this->section('script'); ?>
<script>
	function filterevents(select,event){
		event.preventDefault();
		var s_artist = document.getElementById('s_artist').value;
		var s_tour = document.getElementById('s_tour').value;
		var s_status = document.getElementById('s_status').value;
		window.location.href = "<?= site_url('admin/event') ?>?s_artist=" + s_artist + "&s_tour=" + s_tour + "&s_status=" + s_status;
	}
</script>
<?= $this->endSection() ?>