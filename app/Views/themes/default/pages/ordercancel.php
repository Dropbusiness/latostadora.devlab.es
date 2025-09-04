<?= $this->extend('themes/'. $currentTheme .'/layout') ?>

<?= $this->section('content') ?>
  <!-- Ec breadcrumb start -->
  <?php echo $this->include('themes/'. $currentTheme .'/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->

    <!-- Ec checkout page -->
    <section class="ec-page-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="position-relative">
                <video autoplay muted loop id="myVideo" class="position-relative">
  <source src="<?=$config_theme?>/assets/images/video/order.mp4" type="video/mp4" >
  Your browser does not support HTML5 video.
</video>
<div class="vide_content text-center text-danger">
  <h1><i class="ecicon eci-times-circle"></i> <?=front_translate('General','cancel-text')?></h1>
</div>
</div>
                </div>
            </div>
        </div>
    </section>
    <?= $this->endSection() ?>