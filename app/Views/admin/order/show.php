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
        <div class="row">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3 "  id="print">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-shopping-cart"></i> Nº PEDIDO <?=$order['order_reference']?>
                    <small class="float-right">Fecha: <?=date("d/m/Y H:i:s", strtotime($order['created_order']))?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Cliente
                  <address>
                    Nombre: <?= $customer['firstname']?><br>
                    Apellido: <?=$customer['lastname']?><br>
                    Email: <?=$order['order_email']?><br>
                    Teléfono: <?=$customer['phone']?><br>
                    Direccion: <?=$customer['address']?><br>
                    Ciudad: <?=$customer['city']?><br>
                    CP: <?=$customer['cp']?><br>
                    País: <?= getCountryNameByISO($customer['country'])?><br>
                    Observación: <?=$order['order_obs']?><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Nº carrito# <?=$order['id']?></b><br>
                  <b>Fecha:</b> <?=date("d/m/Y H:i:s", strtotime($order['created_order']))?><br>
                  <b>Referencia adyen:</b> <?=$order['payment_reference']?><br>
                  <b>Método de pago:</b> <?= get_value_from_data($order['payment_data'],'paymentMethod') ?><br>
                  <b>Estado pedido:</b> <?=(isset($statuses[$order['order_status']])?$statuses[$order['order_status']]:'-')?><br>
                  <b>Método de envío:</b> <?=(isset($methodshippings[$order['shipping_id']])?$methodshippings[$order['shipping_id']]['name']:'-')?><br>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>sku</th>
                      <th>Ref. Tostadora</th>
                      <th>Producto</th>
                      <th class="text-center">cantidad</th>
                      <th class="text-right">Precio u.</th>
                      <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orderitems as $item): ?>
                          <tr>
                              <td><?=$item['product_sku']?></td>
                              <td><?=$item['comb_ref']?></td>
                              <td><?=$item['product_name']?></td>
                              <td class="text-center"><?=number_format($item['product_quantity'], 0, ',', '.')?></td>
                              <td class="text-right"><?=number_format($item['product_price'], 2, ',', '.')?>€</td>
                              <td class="text-right"><?=number_format($item['product_price']*$item['product_quantity'], 2, ',', '.')?>€</td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-8">
                </div>
                <!-- /.col -->
                <div class="col-4">
                  <div class="table-responsive">
                    <table class="table">
                    <tr >
                    <th>Gastos de envío</th>
                    <td class="text-right"><?=($order['order_subtotal']>0?number_format($order['order_shipping_price'],2,',','.').'€':'-')?></td>
                    </tr>
                    <tr >
                    <th>Total</th>
                    <td class="text-right"><?=($order['order_subtotal']>0?number_format($order['order_total'],2,',','.').'€':'-')?></td>
                    </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <div class="row">
                <!-- accepted payments column -->
                <div class="col-12">
                  <button class="btn btn-primary mb-2" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="log1_ log2_">
                    Ver log
                  </button>
                  <div class="row">
                    <div class="col-12 col-lg-6">
                      <div class="collapse multi-collapse" id="log1_">
                        <div class="card card-body">
                        <?php
                        // Supongamos que $order['payment_data'] contiene el JSON que mencionas
                        $paymentData = $order['payment_data'];

                        // Decodificar el JSON a un array asociativo
                        $paymentDataArray = json_decode($paymentData, true);

                        // Si la decodificación es exitosa, mostrar los datos ordenados
                        if ($paymentDataArray !== null) {
                            echo "<pre>"; // Para que la salida sea más legible
                            print_r($paymentDataArray); // Imprimir el array de manera ordenada
                            echo "</pre>";
                        } else {
                            echo "Error al decodificar el JSON.";
                        }
                        ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-lg-6">
                      <div class="collapse multi-collapse" id="log2_">
                        <div class="card card-body">
                        <?php
                          // Supongamos que $order['payment_data'] contiene el JSON que mencionas
                          $paymentSession = $order['payment_session'];

                          // Decodificar el JSON a un array asociativo
                          $paymentSessionArray = json_decode($paymentSession, true);

                          // Si la decodificación es exitosa, mostrar los datos ordenados
                          if ($paymentSessionArray !== null) {
                              echo "<pre>"; // Para que la salida sea más legible
                              print_r($paymentSessionArray); // Imprimir el array de manera ordenada
                              echo "</pre>";
                          } else {
                              echo "Error al decodificar el JSON.";
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <!-- this row will not appear when printing -->
             
            </div>
            <div class="row my-4">
                <div class="col-12">
                  <button type="button"  id="doPrint" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-print"></i> Imprimir
                  </button>
                </div>
              </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
<!-- /.content -->
<?= $this->endSection() ?>