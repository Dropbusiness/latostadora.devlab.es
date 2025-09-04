<?php if(is_array($recommendation) && count($recommendation)){ ?>
    <div class="axil-new-arrivals-product-area fullwidth-container flash-sale-area section-gap-80-35">
            <div class="container ml--xxl-0">
                <div class="section-title-border slider-section-title mb-0 pb-0">
                    <h2 class="relation-product"><?=front_translate('General','related-products')?></h2>
                </div>
                <div class="recently-viwed-activation slick-layout-wrapper--15 axil-slick-angle angle-top-slide">
                <?php foreach ($recommendation as $product){
                    if($details['id']==$product['id'])continue;
                    $url=base_url_locale('producto/'.$product['link_rewrite']).'?event='.$event['id'];
                    $img=getenv('app.baseURL').'/uploads/products/medium/'.($product['img']!=''?$product['img']:'default.jpg');
                    ?>
                    <div class="slick-single-layout">
                        <div class="axil-product product-style-eight">
                            <div class="thumbnail">
                                <a href="<?=$url?>">
                                    <img  class="main-img" src="<?=$img?>" alt="<?= $product['name'] ?>">
                                </a>
                            </div>
                            <div class="product-content ps-0 pe-0">
                                <div class="inner">
                                    <h5 class="title"><a class="titulo-producto" href="<?=$url?>"><?= $product['name'] ?></a></h5>
                                    <div class="product-price-variant">
                                        <span class="price current-price titulo-producto-des"> <?= number_format($product['price'], 2, ',', '.') ?>€</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
 <?php } ?>
   