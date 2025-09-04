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
                    <div class="axil-signin-form" style="max-width: 600px;">
                        <h3 class="title text-center"><?= front_translate('General', 'modify-data') ?></h3>
                        <?= view('themes/' . $currentTheme . '/shared/flash_message') ?>
                        <form action="<?= base_url_locale('change-dato'); ?>" method="POST" accept-charset="UTF-8" class="needs-validation  singin-form mt-5" novalidate>
                            <?= csrf_field() ?>
                            <input type="hidden" id="customer_id" value="<?= $customer['id'] ?>">
                            <div class="form-group">
                                <label><?= front_translate('General', 'first-name') ?>*</label>
                                <input class="form-control mb-0" type="text" id="firstname" name="firstname" value="<?= $customer['firstname'] ?>" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'last-name') ?>*</label>
                                <input class="form-control mb-0" type="text" id="lastname" name="lastname" value="<?= $customer['lastname'] ?>" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'email') ?>*</label>
                                <input class="form-control mb-0" type="email" id="email" name="email" value="<?= $customer['email'] ?>" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'country') ?>*</label>
                                <input class="form-control mb-0" type="text" id="country" name="country" value="<?= $customer['country'] ?>" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'city') ?>*</label>
                                <input class="form-control mb-0" type="text" id="city" name="city" value="<?= $customer['city'] ?>" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'post-code') ?>*</label>
                                <input class="form-control mb-0" type="number" id="cp" name="cp" value="<?= $customer['cp'] ?>" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'address') ?>*</label>
                                <input class="form-control mb-0" type="text" id="address" name="address" value="<?= $customer['address'] ?>" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'phone') ?>*</label>
                                <input class="form-control mb-0" type="number" id="phone" name="phone" value="<?= $customer['phone'] ?>" required />
                            </div>
                            <div class="form-group">
                                <label><?= front_translate('General', 'company') ?></label>
                                <input type="text" id="company" name="company" value="<?= $customer['company'] ?>" />
                            </div>
                            <div class="form-group">
                                <input class="form-check-input" type="checkbox" name="publicidad" value="1" id="publicidad" <?= $customer['optin'] == 1 ? 'checked' : '' ?>>
                                <label for="publicidad"><?= front_translate('General', 'info-noved') ?></label>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="axil-btn btn-bg-primary submit-btn w-100 mb-3 boton-black"><?= front_translate('General', 'modify-data') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>
<?= $this->endSection() ?>