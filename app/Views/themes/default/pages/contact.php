<?= $this->extend('themes/'. $currentTheme .'/layout') ?>

<?= $this->section('content') ?>
<main class="main-wrapper">
 <!-- Ec breadcrumb start -->
 <?php echo $this->include('themes/'. $currentTheme .'/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->


    <!-- Start Contact Area  -->
    <div class="axil-contact-page-area axil-section-gap">
            <div class="container">
                <div class="axil-contact-page">
                    <div class="row row--30">
                        <div class="col-lg-12">
                            <div class="contact-form">
                                <?= view('themes/'. $currentTheme .'/shared/flash_message') ?>
                                <form id="contact-form" method="post" action="<?= base_url_locale('contacto') ?>" class="axil-contact-form needs-validation" >
                                    <input type="hidden" name="frmkey" id="frmkey" value="" autocomplete="off"/>
                                    <script type="text/javascript">
                                        setTimeout(() => {
                                            let date = new Date();
                                            document.getElementById("frmkey").value = date.toISOString().split('T')[0].replace(/-/g, '');
                                            console.log(date.toISOString().split('T')[0].replace(/-/g, ''));
                                        }, 7000);
                                    </script>
                                    <div class="row row--10 mt-5">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="contact-name"><?=front_translate('General', 'name') ?> <span>*</span></label>
                                                <input type="text" name="name" class="form-control mb-0" value="<?= esc(set_value('name')); ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="contact-phone"><?=front_translate('General', 'phone') ?> <span>*</span></label>
                                                <input type="text" name="phone"  class="form-control mb-0" value="<?= esc(set_value('phone')); ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="contact-email">E-mail <span>*</span></label>
                                                <input type="email" name="email"  class="form-control mb-0" value="<?= esc(set_value('email')); ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="contact-message"><?=front_translate('General', 'comment-question') ?> <span>*</span></label>
                                                <textarea name="message"  class="form-control mb-0" cols="1" rows="2" required><?= esc(set_value('message')); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                                    <input class="form-check-input" type="checkbox" name="condiciones" value="1" id="condiciones" required>
                                                    <label for="condiciones" class="mb-0"><?=front_translate('General','info-term')?> <a href="<?=base_url_locale('contenido/'.$allpage[1]['link_rewrite']) ?>" target="_blank"><?=$allpage[1]['name']?></a></label>
                                        </div>  
                                        <div class="col-12 mt-4">
                                            <div class="form-group mb--0">
                                                <button name="submit" type="submit" id="submit" class="contact-button"><?=front_translate('General','send')?></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                       
                    </div>
                </div>
                
            </div>
        </div>
        <!-- End Contact Area  -->

    

    </main>
    <?= $this->endSection() ?>