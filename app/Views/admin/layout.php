<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Administrador</title>
	 <!-- site Favicon -->
	 <link rel="icon" type='image/png' href="<?php echo base_url()?>/adm/img/favicon.png?v=<?=time()?>">
    <link rel="shortcut icon" type='image/png' href="<?php echo base_url()?>/adm/img/favicon.png?v=<?=time()?>">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/jqvmap/jqvmap.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/select2/css/select2.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/toastr/toastr.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/css/adminlte.min.css">
	 <!-- summernote -->
	 <link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/summernote/summernote-bs4.min.css">
	<!-- Custom style -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/css/custom.css?v=<?=time()?>">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?php echo base_url()?>/adm/plugins/daterangepicker/daterangepicker.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<?php $this->renderSection('style'); ?>
	<!-- tinymce -->
	<script src="<?php echo base_url()?>/adm/plugins/tinymce/tinymce.min.js"></script>
	
</head>
<body class="sidebar-mini layout-fixed">
	<div class="wrapper">

		<!-- header -->
		<?php echo $this->include('admin/shared/header'); ?>

		<!-- sidebar -->
		<?php echo $this->include('admin/shared/sidebar'); ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- content -->
			<?php echo $this->renderSection('content'); ?>
		</div>
		<!-- /.content-wrapper -->
		<!-- Footer -->
		<?php echo $this->include('admin/shared/footer'); ?>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="<?php echo base_url()?>/adm/plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?php echo base_url()?>/adm/plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="<?php echo base_url()?>/adm/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ChartJS -->
	<script src="<?php echo base_url()?>/adm/plugins/chart.js/Chart.min.js"></script>
	<!-- daterangepicker -->
	<script src="<?php echo base_url()?>/adm/plugins/moment/moment.min.js"></script>
	<script src="<?php echo base_url()?>/adm/plugins/daterangepicker/daterangepicker.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?php echo base_url()?>/adm/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?php echo base_url()?>/adm/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- Select2 -->
	<script src="<?php echo base_url()?>/adm/plugins/select2/js/select2.full.min.js"></script>
	<!-- Toastr -->
	<script src="<?php echo base_url()?>/adm/plugins/toastr/toastr.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url()?>/adm/js/adminlte.js"></script>
	<!-- Summernote -->
	<script src="<?php echo base_url()?>/adm/plugins/summernote/summernote-bs4.min.js"></script>
	
	<!-- bootstrap-confirm-delete -->
	<script src="<?php echo base_url()?>/adm/js/bootstrap-confirm-delete.js?v=<?=time()?>"></script>
	<!-- waitingfor-->
	<script src="<?php echo base_url()?>/adm/js/waitingfor.js?v=<?=time()?>"></script>

	<!-- Elfinder -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/adm/plugins/elFinder/css/elfinder.full.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/adm/plugins/elFinder/css/elfinder.theme.min.css">
	<script src="<?php echo base_url()?>/adm/plugins/elFinder/js/elfinder.min.js"></script>
	<!-- all  -->
	<script src="<?php echo base_url()?>/adm/js/common.js?v=<?=time()?>"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<?php if($currentAdminSubMenu=='dashboard'){?>
		<script src="<?php echo base_url()?>/adm/js/pages/dashboard.js?v=<?=time()?>"></script>
	<?php }else if($currentAdminSubMenu=='elfinder'){?>
		<!-- AdminLTE for demo purposes -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#elfinder').elfinder({
					lang: 'en',
					height : 700,
					url: '/admin/elfinder/connector',
				});
			
			});
		</script>
	<?php }?>

	<?php $this->renderSection('script'); ?>
</body>
</html>
