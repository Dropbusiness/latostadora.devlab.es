<!DOCTYPE html>
<html  lang="<?=$lang?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?=($meta_description??'')?>" />
    <meta name="keywords" content="<?=($meta_keywords??'')?>" />
    <meta property="og:title" content="<?=($meta_title??'')?>" />
    <meta property="og:description" content="<?=($meta_description??'')?>" />
    <meta property="og:url" content="<?=($meta_url??'')?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?= isset($meta_image) && !is_null($meta_image) ? $meta_image : base_url('assets/img/site-overview.png')?>" />
    <title><?=($page_title??'')?></title>
    <!-- site Favicon -->
    <link rel="icon" href="/home/favicon/favicon.svg" type="image/svg+xml">
    <link rel="icon" type="image/png" href="/home/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/home/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/home/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/home/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="/home/site.webmanifest" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/font-awesome.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/flaticon/flaticon.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/slick.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/slick-theme.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/jquery-ui.min.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/sal.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/magnific-popup.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/vendor/base.css">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/plugins/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/plugins/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/style.css?v=<?=time()?>">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/custom.css?v=<?=time()?>">
    <link rel="stylesheet" href="<?=$config_theme?>/assets/css/new_style.css?v=<?=time()?>">
    <?= $this->renderSection('style') ?>
    <?php if ($theme_id=='theme_2'){?>
        <link rel="stylesheet" href="<?=$config_theme?>/assets/css/theme_2.css?v=<?=time()?>">
    <?php }elseif($theme_id=='theme_4'){?>
        <link rel="stylesheet" href="<?=$config_theme?>/assets/css/theme_4.css?v=<?=time()?>">
        <?php }elseif($theme_id=='theme_7'){?>
        <link rel="stylesheet" href="<?=$config_theme?>/assets/css/theme_7.css?v=<?=time()?>">
        <?php }?>
    
    <script type="text/javascript">
      let LANG_CODE = "<?= $lang ?>";
      let LANG_CODE_DEFAULT = "<?= $lang_default ?>";
      let LANG_URI = "<?=($lang!=$lang_default?$lang.'/':'') ?>";
      let ISLOGGEDIN = "<?= $isLoggedIn ?>";
      /////////////TRADUCCION///////////////////
      let LANG_IMAGE = "<?=front_translate('General','image')?>";
      let LANG_PRODUCT = "<?=front_translate('General','product')?>";
      let LANG_QUANTITY = "<?=front_translate('General','quantity')?>";
      let LANG_SHOP = "<?=front_translate('General','shop-cart')?>";
      let LANG_TOTAL = "<?=front_translate('General','total')?>";
      let LANG_DELETE = "<?=front_translate('General','delete')?>";


    </script>
  
  <!--
  <link rel="stylesheet" href="https://cdf6519016.cdn.adyen.com/checkoutshopper/sdk/6.5.1/adyen.css" />
<script src="https://cdf6519016.cdn.adyen.com/checkoutshopper/sdk/6.5.1/adyen.js"></script>
    -->

  
</head>
<body class="<?=$page_class?> sticky-header <?=$theme_id?>" >
<script>
/* SealMetrics Tracker Code */
(function() {
var options = {
   account: '6823a76ff0be2a6fcf15609b',
   event: 'pageview',
   use_session: 1,
};
var url="//app.sealmetrics.com/tag/v2/tracker";function loadScript(callback){var script=document.createElement("script");script.src=url;script.async=true;script.onload=function(){if(typeof callback==="function"){callback();}};script.onerror=function(){console.error("Error loading script: "+url);};document.getElementsByTagName("head")[0].appendChild(script);}loadScript(function(){options.id=Math.floor((Math.random()*999)+1);if(window.sm){var instance=new window.sm(options);instance.track(options.event);}else{console.error("sm2 plugin is not available");}});
})();
/* End SealMetrics Tracker Code */
</script>

    <?php echo in_array($page_name,['product_page','catalogo','product','tour'])?$this->include('themes/'. $currentTheme .'/shared/header'):'';?>
    <?php echo $this->renderSection('content')?>
    <?php echo in_array($page_name,['product_page','catalogo','product','content-1','content-6','content-9','checkout','contacto','tour'])?$this->include('themes/'. $currentTheme .'/shared/footer'):'';?>

    <!-- JS
============================================ -->
    <!-- Modernizer JS -->
    <script src="<?=$config_theme?>/assets/js/vendor/modernizr.min.js"></script>
    <!-- jQuery JS -->
    <script src="<?=$config_theme?>/assets/js/vendor/jquery.js"></script>
    <!-- Bootstrap JS -->
    <script src="<?=$config_theme?>/assets/js/vendor/popper.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/bootstrap.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/plugins/jquery.fancybox.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/slick.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/js.cookie.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/jquery-ui.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/jquery.ui.touch-punch.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/jquery.countdown.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/sal.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/jquery.magnific-popup.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/imagesloaded.pkgd.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/isotope.pkgd.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/counterup.js"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/waypoints.min.js"></script>
    <script src="<?=$config_theme?>/assets/js/bootstrap-notify.min.js"></script>

    <script src="<?=$config_theme?>/assets/js/vendor/touchspin/jquery.bootstrap-touchspin.min.css"></script>
    <script src="<?=$config_theme?>/assets/js/vendor/touchspin/jquery.bootstrap-touchspin.min.js"></script>
    
    <script src="<?=$config_theme?>/assets/js/waitingfor.js"></script>
    <!-- Main JS -->
    <script src="<?=$config_theme?>/assets/js/main.js?v=<?=time()?>"></script>
    <script src="<?=$config_theme?>/assets/js/new_main.js?v=<?=time()?>"></script>
    <script src="<?=$config_theme?>/assets/js/custom.js?v=<?=time()?>"></script>
    <?php if($page_name=='checkout'){?>
    <script src="<?=$config_theme?>/assets/js/checkout.js?v=<?=time()?>"></script>
    <?php }?>

 
</body>

</html>