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
                <div class="col-12 text-right">
                    <a href="<?=base_url_locale('micuenta') ?>" class="title-highlighter highlighter-primary2"><i class="fas fa-home"></i> <?=front_translate('General','my-account')?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-12  mb-2">
                       <h3 class="mc-title"><?=front_translate('General','person-information')?></h3>
                       <div class=""><b><?=front_translate('General','company')?>:</b> <?=($customer['company']!=''?$customer['company']:$customer['firstname'])?> </div>
                       <div class=""><b><?=front_translate('General','name')?>:</b> <?=$customer['firstname']?> </div>
                       <div class=""><b><?=front_translate('General','last-name')?>:</b> <?=$customer['lastname']?> </div>
                       <div class=""><b><?=front_translate('General','country')?>:</b> <?=$customer['country']?> </div>
                       <div class=""><b><?=front_translate('General','city')?>:</b> <?=$customer['city']?> </div>
                       <div class=""><b><?=front_translate('General','address')?>:</b> <?=$customer['address']?> </div>
                       <div class=""><b><?=front_translate('General','phone')?>:</b> <?=$customer['phone']?> </div>
                       <div class=""><b>CP:</b> <?=$customer['cp']?> </div>
                       <div class=""><b>Email:</b> <?=$customer['email']?> </div>
                </div>
              
                <div class="col-12">
                    <a href="<?=base_url_locale('change-dato')?>" class="axil-btn btn-outline"><?=front_translate('General','modify-data')?></a>
                    <a href="<?=base_url_locale('change-password')?>" class="axil-btn btn-outline"><?=front_translate('General','modify-password')?></a>
                </div>
            </div>
        </div>
    </section>
    <!-- End Privacy & Policy page -->
</main>
    <?= $this->endSection() ?>