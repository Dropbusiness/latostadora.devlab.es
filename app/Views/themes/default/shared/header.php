<a href="#top" class="back-to-top" id="backto-top"><i class="fal fa-arrow-up"></i></a>
<!-- Start Header -->
<header class="header axil-header header-style-5">
    <div id="axil-sticky-placeholder"></div>
    <div class="axil-mainmenu p-md-0">
        <div class="container my-0 py-md-3">
            <div class="header-navbar d-flex justify-content-between">
            <?php if(isset($tour) && in_array($page_name,['tour'])){?>
                    <div class="row flex-fill">
                        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h2 class="m-0 title-catalogo"><?=  ($tour['artist_name']??'') . ' - ' . ($tour['name']??'')  ?> </h2>
                        </div>
                    </div>
                <?php }?>
            <?php if(isset($event) && isset($events) && in_array($page_name,['catalogo','product'])){?>
                    <div class="row flex-fill">
                        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h2 class="m-0 title-catalogo"><?= ($event['artist_name']??'') . ' - ' . ($event['tour_name']??'') ?> </h2>
                            <select id="events" class="form-select selec-ancho select-date form-select-lg ml-md-auto  selec-font" aria-label=".form-select-lg" style="height:35px">
                                <?php foreach ($events as $item){ $href=site_url().'event/'.$item['artist_slug']."/".$item['tour_slug']."/".$item['slug']."/".$item['date'];?>
                                    <option value="<?=$item['id']?>" data-href="<?=$href?>" <?=($event['id']==$item['id']?'selected':'')?>><?=mb_strtoupper($item['city'], 'UTF-8')?> - <?=date("d/m/Y", strtotime($item['date']))?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                <?php }?>
                <div id="ban"></div>
                <div class="header-action">
                    <ul class="action-list m-0 mt-2">
                        <li class="shopping-cart ">
                            <a href="#" class="cart-dropdown-btn header-black">
                                <span class="cart-count">0</span>
                                <i class="flaticon-shopping-cart" ></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Mainmenu Area -->
</header>
<!-- End Header -->

<div class="cart-dropdown" id="cart-dropdown">
        <div class="cart-content-wrap">
            <div class="cart-header">
                <h2 class="header-title"><?=front_translate('General','my-cart')?></h2>
                <button class="cart-close sidebar-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="cart-body">
                <ul class="cart-item-list"><li><?=front_translate('General','not-cart')?></li></ul>
            </div>
            <div class="cart-footer">
                    <a href="<?=base_url_locale()?>checkout" class="checkout-btn w-100 text-center py-2 product-button"><?=front_translate('General','show-cart')?></a>
            </div>
        </div>
    </div>