<?= $this->extend('themes/'. $currentTheme .'/layout') ?>

<?= $this->section('content') ?>
<main class="main-wrapper">
 <!-- Ec breadcrumb start -->
 <?php echo $this->include('themes/'. $currentTheme .'/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->

    <!-- Start Privacy & Policy page -->
    <section class="axil-section-gap">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-3">
                        <h4 class="mc-title"><a href="<?=base_url_locale('misdatos')?>"><i class="fas fa-user"></i> <?=front_translate('General','my-data')?></a></h4>
                </div>
                <div class="col-12 col-lg-3">
                        <h4 class="mc-title"><a href="<?=base_url_locale('miscarritos')?>"><i class="fas fa-shopping-basket"></i> <?=front_translate('General','my-orders')?></a></h4>
                </div>
                <div class="col-12 col-lg-3">
                        <h4 class="mc-title"><a href="<?=base_url_locale('logout')?>"><i class="fal fa-sign-out"></i> <?=front_translate('General','disconnect')?></a></h4>
                </div>
            </div>
        </div>
    </section>
    <!-- End Privacy & Policy page -->
    </main>
    <?= $this->endSection() ?>