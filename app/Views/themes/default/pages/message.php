<?= $this->extend('themes/'. $currentTheme .'/layout') ?>

<?= $this->section('content') ?>
 <!-- Ec breadcrumb start -->
 <?php echo $this->include('themes/'. $currentTheme .'/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->

    <!-- Start Privacy & Policy page -->
    <section class="ec-page-content">
        <div class="container">
            <div class="row">
                <div class="col">
                    <?= view('themes/'. $currentTheme .'/shared/flash_message') ?>
                </div>
            </div>
        </div>
    </section>
    <!-- End Privacy & Policy page -->
    <?= $this->endSection() ?>