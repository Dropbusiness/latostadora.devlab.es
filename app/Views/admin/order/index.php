<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1>Order</h1>
		  </div>
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard') ?>">Dashboard</a></li>
			  <li class="breadcrumb-item active">Order</li>
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
						<h3 class="card-title">Order</h3>
			  		</div>
			  <!-- /.card-header -->
			  	<div class="card-body p-0">
				  	<?= view('admin/shared/flash_message') ?>
					  <!--buscador-->
					<form method='get' action="<?= site_url('admin/order') ?>" id="searchForm">
					<div class="row mx-2 mt-3">
							<div class="col-12 col-md-8 col-lg-6">
								<div class="input-group input-group">
									<input type="search" class="form-control form-control" placeholder="Buscar..." name='s_name' value="<?=$s_name?>" >
									<div class="input-group-append">
										<button type="submit" class="btn btn btn-default">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</div>
								<div class="mt-1">
									<div class="clearfix py-2">
										<a data-toggle="collapse" href="#collapsesearch" role="button" aria-expanded="false" aria-controls="collapsesearch" class="advanced float-right ml-3"> Más opciones de busqueda <i class="fa fa-angle-down"></i> </a>
										<a  href="<?= site_url('admin/order') ?>"  class="advanced float-left"> Limpiar busqueda <i class="fas fa-times"></i> </a>
									</div>
									<div class="collapse clearfix" id="collapsesearch">
										<div class="card card-body mt-2">
											<div class="row">
												<div class="col-4">
													<div class="form-group">
														<div class="mb-2">Fecha desde</div>
														<input type="date" class="input form-control" id="s_idate" name="s_idate" value="<?=$s_idate?>">
													</div>
												</div>
												<div class="col-4">
													<div class="mb-2">Fecha hasta</div>
													<input type="date"  class="input form-control" id="s_fdate" name="s_fdate" value="<?=$s_fdate?>">
												</div>
												<div class="col-4">
													<div class="form-group">
													<div class="mb-2">Artista</div>
														<?= form_dropdown('s_artist',[''=>'---']+$artists,$s_artist, ['class' => 'form-control']) ?>
													</div>
												</div>
												<div class="col-6">
													<div class="form-group">
													<div class="mb-2">Tour</div>
														<?= form_dropdown('s_tour',[''=>'---']+$tours,$s_tour, ['class' => 'form-control']) ?>
													</div>
												</div>
												<div class="col-6">
													<div class="form-group">
													<div class="mb-2">Evento</div>
														<?= form_dropdown('s_event',[''=>'---']+$events,$s_event, ['class' => 'form-control']) ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
						</div>
						<div class="col-12 col-lg-6 text-right">
							<a class="btn btn-info" href="<?php echo base_url('admin/order/export?s_name='.$s_name.'&s_idate='.$s_idate.'&s_fdate='.$s_fdate.'&s_status='.$s_status.'&s_artist='.$s_artist.'&s_tour='.$s_tour.'&s_event='.$s_event.''); ?>" target="_black"><i class="fas fa-file-download"></i> Exportar</a>
						</div>
					</div>
					</form>
							<!--//buscador-->
							<div class="row px-3">
						<div class="col-6 text-left py-3">
							Total registro: <?=$total?> 
							
						</div>
						<div class="col-6 text-right  py-3">
							<?php echo $pager ?>
						</div>
					</div>
					<div class="table-responsive">
					<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>Nº pedido</th>
                            <th >Cliente</th>
							<th >Método envío</th>
                            <th  class="text-center">Ref. adyen</th>
							<th  class="text-center">Pago</th>
							<th  class="text-right">total</th>
							<th  class="text-center">Fecha pedido</th>
                            <th  class="text-center">Estado</th>
							<th>Id carrito</th>
                            <th>Acciones</th>
						</tr>
					</thead>
					<tbody>
                        <?php
                        $i = 0;
                        foreach ($alldata as $item) {
                            $i++;
                            ?>
                            <tr>
								<td><?php echo $item['order_reference']; ?></td>
                                
                                <td>
									<?= (isset($item['firstname']) ? $item['firstname'] : '') . ' ' . (isset($item['lastname']) ? $item['lastname'] : '') ?><br/>
									<b>Tel: </b><?php echo $item['phone'] ?><br/>
									<b>Email: </b><?php echo $item['order_email'] ?><br/>
									<b>Dir: </b><?php echo $item['address'] ?>, <?php echo $item['city'] ?>,<?php echo $item['cp'] ?> <?= getCountryNameByISO($item['country']) ?><br/>
								</td>
								<td><?=isset($shippings[$item['shipping_id']])?$shippings[$item['shipping_id']]['name']:'-'; ?></td>
								<td  class="text-center"><?php echo $item['payment_reference'] ?></td>
								<td  class="text-center"><?= get_value_from_data($item['payment_data'],'paymentMethod') ?></td>
								<td  class="text-right"><?=number_format($item['order_total'], 2, ',', '.')?>€</td>
								<td  class="text-center"><?php echo $item['created_order'] ?></td>
                                <td  class="text-center">
                                    <?php echo isset($statuses[$item['order_status']])?$statuses[$item['order_status']]:$item['order_status']; ?>
                                </td>
								<td><?php echo $item['id']; ?></td>
                                <td  class="text-center">
                                    <a class="btn btn-info" href="<?php echo base_url('admin/order/show/' . $item['id']); ?>">
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
						<div class="col-6 text-left py-3">
							Total registro: <?=$total?> 
							
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