<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login Page</title>
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
  <link rel="icon" href="/themes/default/assets/images/favicon.svg" type="image/svg+xml">
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
<div class="login-logo">
    <img  src="<?php echo base_url()?>/adm/img/logo_easymerx.png" alt="logo" width="300">
  </div>
  <!-- /.login-logo -->
  <div class="card rounded">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Por favor ingrese nombre de usuario y contraseña para iniciar sesión</p>
      <div id="infoMessage"><?php echo $message;?></div>
      <?php echo form_open('auth/login');?>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="E-mail" name="identity" value="" id="identity">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña" name="password" value="" id="password" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Recuérdame</label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="submit" class="btn btn-primary btn-block">Entrar</button>
          </div>
          <!-- /.col -->
        </div>
        <?php echo form_close();?>


    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

	<!-- jQuery -->
	<script src="<?php echo base_url()?>/adm/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?php echo base_url()?>/adm/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url()?>/adm/js/adminlte.js"></script>

</body>
</html>

