
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
	  <div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-6">
			<h1 class="m-0 text-dark">Dashboard</h1>
		  </div><!-- /.col -->
		  <div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="#">Home</a></li>
			  <li class="breadcrumb-item active">Dashboard</li>
			</ol>
		  </div><!-- /.col -->
		</div><!-- /.row -->
	  </div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->
	<!-- Main content -->
	<section class="content">
	  <div class="container-fluid">
		   <!-- Small boxes (Stat box) -->
		   <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="total_orders">-</h3>
                <p>Carritos/pedidos</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/admin/order" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3  id="total_products">-</h3>
                <p>Productos</p>
              </div>
              <div class="icon">
                <i class="ion ion-cube"></i>
              </div>
              <a href="/admin/products" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3  id="total_customers">-</h3>
                <p>Clientes</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="/admin/customer" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="total_contacts">-</h3>
                <p>Contacto/incidencias</p>
              </div>
              <div class="icon">
                <i class="ion ion-chatbox"></i>
              </div>
              <a href="/admin/contact" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
		<!-- Main row -->
		<div class="row">
		  <!-- Left col -->
		  <section class="col-lg-12 connectedSortable">
			<!-- Custom tabs (Charts with tabs)-->
			<div class="card">
			  <div class="card-header">
				<h3 class="card-title">
				  <i class="fas fa-chart-pie mr-1"></i>
				  Pedidos de los últimos 30 días
				 </h3>
				 <div class="card-tools">
				 	<div id="reportrange" class="selectbox float-right" data-start="<?=$date_start?>" data-end="<?=$date_end?>">
						<i class="fa fa-calendar"></i>
						<span></span> <i class="fa fa-caret-down"></i>
					</div>
				</div>
			  </div><!-- /.card-header -->
			  <div class="card-body">
					  <canvas id="cart-chart-canvas" height="250" style="height: 250px;"></canvas>                         
			  </div><!-- /.card-body -->
			</div>
			<!-- /.card -->
			<div class="row">
				<div class="col-12 col-lg-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-cart-arrow-down mr-1"></i> Últimos pedidos</h3>
						</div>
						<!-- /.card-header -->
						<div class="card-body table-responsive p-0" style="height: 450px;">
							<table class="table table-head-fixed text-nowrap" id="table_orders">
								<thead>
									<tr>
									<th>Nº</th>
									<th>Fecha</th>
									<th>Cliente</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
								</table>
							</div>
							<div class="card-footer">
									<div id="pagination_orders"></div>
							</div>
					</div>
					<!-- /.card -->
				</div>
				<div class="col-12 col-lg-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-cube mr-1"></i> Top productos</h3>
						</div>
						<!-- /.card-header -->
						<div class="card-body table-responsive p-0" style="height: 450px;">
							<table class="table table-head-fixed text-nowrap" id="table_topproducts">
								<thead>
									<tr>
									<th>Referencia</th>
									<th>producto</th>
									<th>#pedidos</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
								</table>
							</div>
							<div class="card-footer">
									<div id="pagination_topproducts"></div>
							</div>
					</div>
					<!-- /.card -->
				</div>
			</div>
		  </section>
		  <!-- /.Left col -->
		  <!-- right col (We are only adding the ID to make the widgets sortable)-->
		  <section class="col-lg-12 connectedSortable">
			<div class="row">
				<div class="col-6">
					<div class="card">
					<div class="card-header">
						<h3 class="card-title"><i class="fas fa-comments mr-1"></i> Últimas entradas de contacto</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0" style="height: 385px;">
							<table class="table table-head-fixed text-nowrap" id="table_contacts">
							<thead>
								<tr>
								<th>Fecha</th>
								<th>Contacto</th>
								<th></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
							</table>
						</div>
						<div class="card-footer">
								<div id="pagination_contacts"></div>
						</div>
					</div>
					<!-- /.card -->
				</div>
				<div class="col-6">
					<div class="card">
					<div class="card-header">
						<h3 class="card-title"><i class="fas fa-comments mr-1"></i> Últimas entradas de incidencias</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0" style="height: 385px;">
							<table class="table table-head-fixed text-nowrap" id="table_incidencias">
							<thead>
								<tr>
								<th>Fecha</th>
								<th>Contacto</th>
								<th></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
							</table>
						</div>
						<div class="card-footer">
								<div id="pagination_incidencias"></div>
						</div>
					</div>
					<!-- /.card -->
				</div>
			</div>
		  </section>
		  <!-- right col -->
		</div>
		<!-- /.row (main row) -->
	  </div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
<?= $this->endSection() ?>