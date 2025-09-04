<?= $this->extend('themes/'. $currentTheme .'/layout') ?>

<?= $this->section('content') ?>
<main class="main-wrapper">
 <!-- Ec breadcrumb start -->
 <?php echo $this->include('themes/'. $currentTheme .'/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->
    <!-- Start Privacy & Policy page -->
    <section class="ec-page-content about-style-1 axil-section-gap">
        <div class="container">
            <div class="row">
                <div class="col pagecontent">
                        <?=$details['description']?>
                </div>
            </div>
        </div>
    </section>
</main>
    <!-- End Privacy & Policy page -->
    <?= $this->endSection() ?>