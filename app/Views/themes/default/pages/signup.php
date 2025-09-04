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
                        <div class="axil-signin-form" style="max-width: 600px;">
                        <h3 class="title text-center"><?=front_translate('General','new-registration')?></h3>
                        <?= view('themes/'. $currentTheme .'/shared/flash_message') ?>
                        <form action="<?= base_url_locale('signup'); ?>" method="POST" accept-charset="UTF-8" class="needs-validation  singin-form mt-5" novalidate>
                        <?= csrf_field() ?>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','first-name')?>*</label>
                                    <input type="text"  class="form-control mb-0"  name="firstname" value="<?= old('firstname') ?>"  required >
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-field')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','last-name')?>*</label>
                                    <input type="text" class="form-control mb-0" name="lastname" value="<?= old('lastname') ?>"  required />
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-field')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','email')?>*</label>
                                    <input type="email" class="form-control mb-0" name="email" value="<?= old('email') ?>" required />
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-email')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','password')?>*</label>
                                    <input type="password" class="form-control mb-0" name="passwd" value="<?= old('passwd') ?>" required />
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-field')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','country')?>*</label>
                                    <input type="text"  class="form-control mb-0" name="country" value="<?= old('country') ?>" required/>
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-field')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','city')?>*</label>
                                    <input type="text"  class="form-control mb-0" name="city" value="<?= old('city') ?>" required/>
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-field')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','address')?>*</label>
                                    <input type="text" class="form-control mb-0" name="address" value="<?= old('address') ?>" required/>
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-field')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','post-code')?>*</label>
                                    <input type="number" class="form-control mb-0" name="cp" value="<?= old('cp') ?>"  required/>
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-number')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom01" class="form-label"><?=front_translate('General','phone')?>*</label>
                                    <input type="number" class="form-control mb-0" name="phone" value="<?= old('phone') ?>" required/>
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','fill-number')?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?=front_translate('General','company')?></label>
                                    <input type="text" name="company" value="<?= old('company') ?>" />
                                </div>
                                
                                <div class="">
                                    <input class="form-check-input" type="checkbox" name="publicidad" value="1" id="publicidad">
                                    <label for="publicidad"><?=front_translate('General','info-noved')?></label>
                                </div>

                                <div class="">
                                    <input class="form-check-input " type="checkbox" name="condiciones" value="1" id="condiciones invalidCheck" required/>
                                    <label for="condiciones invalidCheck" class="mb-0"> <?=front_translate('General','info-term')?> <a href="<?=base_url_locale('contenido/'.$allpage[6]['link_rewrite']) ?>" target="_blank"><?=front_translate('General','privacy-policy')?></a></label>
                                    <div class="invalid-feedback">
                                    <?=front_translate('General','acept-term')?>
                                    </div>
                                </div>

                            <div class="form-group mt-4">
                                <button type="submit" class=" submit-btn w-100 mb-3 product-button"><?=front_translate('General','register')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
          
        </div>
    </section>
    </main>
    <?= $this->endSection() ?>