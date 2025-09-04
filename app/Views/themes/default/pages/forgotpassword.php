<?= $this->extend('themes/'. $currentTheme .'/layout') ?>

<?= $this->section('content') ?>
<main class="main-wrapper">
  <!-- Ec breadcrumb start -->
 <?php echo $this->include('themes/'. $currentTheme .'/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->

    <!-- Ec login page -->
    <section class="axil-section-gap">
        <div class="container">
            <div class="row">
                 <div class="col d-flex justify-content-center">
                        <div class="axil-signin-form">
                            <h3 class="title text-center"><?=front_translate('General','forgot-password')?></h3>
                            <?= view('themes/'. $currentTheme .'/shared/flash_message') ?>
                            <form action="<?= base_url_locale('recuperar-contrasena'); ?>" method="POST" accept-charset="UTF-8" class="mt-5 needs-validation singin-form" novalidate>
                            <?= csrf_field() ?>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= old('email') ?>" required >
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="product-button submit-btn w-100 mb-3"><?=front_translate('General','recover-password')?></button>
                                    <a href="<?=base_url_locale('signin')?>" class="forgot-btn  d-block"><?=front_translate('General','log-in')?></a>
                                    <a href="<?=base_url_locale('signup')?>" class="forgot-btn  d-block"><?=front_translate('General','new-registration')?>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </section>
    </main>
    <?= $this->endSection() ?>