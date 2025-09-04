<?= $this->extend('themes/' . $currentTheme . '/layout') ?>
<?= $this->section('content') ?>
<main class="main-wrapper">
    <!-- Ec breadcrumb start -->
    <?php echo $this->include('themes/' . $currentTheme . '/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->
    <!-- Ec login page -->
    <section class="axil-section-gap">
        <div class="container">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <div class="axil-signin-form">
                        <h3 class="title text-center"><?= front_translate('General', 'modify-password') ?></h3>
                        <?= view('themes/' . $currentTheme . '/shared/flash_message') ?>
                        <form action="<?= base_url_locale('change-password'); ?>" method="POST" accept-charset="UTF-8" class="mt-5 needs-validation singin-form" novalidate>
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label><?= front_translate('General', 'new-password') ?>*</label>
                                <input type="password" name="password" value="" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'confirm-password') ?>*</label>
                                <input type="password" name="password_confirm" value="" required />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="axil-btn btn-bg-primary submit-btn w-100 mb-3 boton-black"><?= front_translate('General', 'modify-password') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?= $this->endSection() ?>