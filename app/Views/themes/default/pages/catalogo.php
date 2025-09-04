<?= $this->extend('themes/'. $currentTheme .'/layout') ?>
<?= $this->section('content') ?>
<main class="main-wrapper">
 <!-- Ec breadcrumb start -->
<section class="axil-shop-area axil-section-gap bg-color-white" style="padding-top:3px;">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <?php if($event['tour_img']!=''){?>
                            <img src="/uploads/tours/original/<?=$event['tour_img']?>" class="img-fluid">
                        <?php }?>
                    </div>
                </div>
                <div id="lista-productos" class="row justify-content-center mt-5">
                    <!-- Contenedor de Productos -->
                    <?php 
                    if($event['status']==1){
                        foreach ($recommendation as $product){
                                    $url=base_url_locale('producto/'.$product['link_rewrite']).'?event='.$event['id'];
                                    $img='/uploads/products/medium/'.($product['img']!=''?$product['img']:'default.jpg');
                                    ?>
                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-6">
                                        <div class="axil-product product-style-one has-color-pick mt-2">
                                            <div class="thumbnail">
                                                <a href="<?=$url?>">
                                                    <img src="<?=$img?>" alt="Product Images">
                                                </a>
                                            </div>
                                            <div class="product-content text-start">
                                                <div class="inner">
                                                    <h5 class="title"><a class="titulo-producto" href="<?=$url?>"><?= $product['name'] ?></a></h5>
                                                    <div class="product-price-variant">
                                                        <span class="price current-price titulo-producto-des"><?= number_format($product['price'], 2, ',', '.') ?>€</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Single Product  -->
                        <?php } 
                    }else{
                        echo '<div class="col-12"><div class="mb-2 text-center">Este evento no está activo en estos momentos</div></div>';
                    }
                    ?>
                </div>
            </div>
</section>
</main>
 <?= $this->endSection() ?>
 