<?= $this->extend('themes/'. $currentTheme .'/layout') ?>

<?= $this->section('content') ?>
<main class="main-wrapper">
    <!-- Ec breadcrumb start -->
    <?php //echo $this->include('themes/'. $currentTheme .'/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->
    <div class="container mb-3" style="margin-top:3px;">
        <?php if(isset($event) && $events){?>
        <div class="row">

            <div class="col-12 text-center">
                <?php if($event['tour_img']!=''){?>
                <img src="/uploads/tours/original/<?=$event['tour_img']?>" class="img-fluid ">
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
    <?php if($details['status']==1){ ?>
    <div class="axil-single-product-area bg-color-white">
        <div class="single-product-thumb axil-section-gapcommon single-product-modern">
            <div class="container">
                <div class="row row--20 justify-content-center">
                    <div class="d-flex mb-4" id="volverEvento1">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                            </svg>
                        </div>
                        <div class="ps-3 btn btn-volver"> <?= front_translate('General', 'back-event') ?></div>
                    </div>
                    <div class="col-lg-5 mb--40">
                        <div class="row justify-content-center">
                            <div class="single-pro-img">
                                <div class="single-product-scroll">
                                    <!-- Swiper principal -->
                                    <?php if(is_array($photos) && count($photos)){?>
                                    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                                        class="swiper SwiperProductsImg">
                                        <div class="swiper-wrapper">
                                            <?php  foreach ($photos as $i){?>
                                            <div class="swiper-slide">
                                                <a href="<?='/uploads/products/original/'.$i['img']?>"
                                                    data-fancybox="gallery">
                                                    <img src="<?='/uploads/products/medium/'.$i['img']?>"
                                                        class="img-fluid">
                                                </a>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                                    <div thumbsSlider="" class="swiper SwiperProductsImgthum mt-4">
                                        <!-- Agrega estos botones -->
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-wrapper">
                                            <?php  foreach ($photos as $i){ $img='/uploads/products/small/'.$i['img'];?>
                                            <div class="swiper-slide"><img src="<?=$img?>" class="img-fluid"></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php }else{ ?>
                                    <img class="img-fluid" src="/uploads/products/large/default.jpg" alt="">
                                    <?php }?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 mb--40">
                        <div class="single-product-content">
                            <div class="inner">
                                <h2 class="product-title"><?=$details['name'] ?></h2>
                                <span class="price-amount"><?=number_format($details['price'],2,',','.')?>€</span>
                                <div class="description">
                                    <?=$details['description_short'] ?>
                                </div>
                                <?php if($details['group_showsize'] != 0){?>
                                <div class="text-end">
                                    <a class="ver_talla" href="#" onclick="mostrartalla(this,<?=$details['group_showsize']?>)">
                                        ¿Cuál es mi talla?
                                    </a>
                                </div>
                                <?php } ?>
                                <div class="product-variations-wrapper">
                                    <?php foreach ($combinaciones['grouped'] as $attribute): ?>
                                    <div class="product-variation" id="attribute_<?=$attribute['attribute_id']; ?>"
                                        data-id="<?=$attribute['attribute_id'];?>"
                                        data-position="<?=$attribute['attribute_position'];?>">
                                        <h6 class="title"><?=$attribute['attribute_name']; ?>:</h6>
                                        <ul class="range-variant">
                                            <?php 
                                                $att_n=count($attribute['items']);
                                                foreach ($attribute['items'] as $item): 
                                                    ?>
                                            <li class="<?=$item['default_on'] ||  $att_n==1? 'active' : ''; ?>"
                                                <?=$attribute['attribute_id']==3?'style="background-color:'.$item['value_code'].'"':''?>
                                                data-attid="<?=$attribute['attribute_id'];?>"
                                                data-valid="<?=$item['value_id'];?>">
                                                <?=$item['value_name']; ?>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if($event['status']!=1){ ?>
                                    <div class="alert alert-warning text-center">
                                    Escríbenos a ayuda@easymerx.com si tienes alguna duda.
                                    </div>
                                <?php }?>
                                    <!-- Start Product Action Wrapper  -->
                                    <div class="product-action-wrapper d-flex-center <?= $event['status']!=1 ? 'd-none' : '' ?>">
                                        <div class="ec-single-qty margin-qty">
                                            <div class="qty-plus-minus">
                                                <input class="input_qty text-center" type="text" name="product_qty"
                                                    id="product_qty" value="1" step="1" min="1" max="999" title="" />
                                            </div>
                                        </div>
                                        <ul class="product-action d-flex-center mb--0">
                                            <li class="add-to-cart">
                                                <button class="custom-button" id="btnaddcart"
                                                    onclick="addcartdetails('#btnaddcart',this)"
                                                    data-id="<?=$details['id']?>" data-sku="<?=$details['sku'] ?>"
                                                    data-event="<?=$event['id']?>"
                                                    data-tour="<?=$event['tour_id']?>">
                                                    <i></i>
                                                    <?= front_translate('General', 'add-cart') ?>
                                                </button>
                                            </li>
                                        </ul>
                                        <!-- End Product Action  -->
                                    </div>
                                
                                <!-- End Product Action Wrapper  -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End .single-product-thumb -->

        <div class="woocommerce-tabs wc-tabs-wrapper">
            <div class="container">
                <div class="product-desc-wrapper mb--20 mb_sm--10">
                    <h4 class="mb--30 desc-heading desc-product">Descripción</h4>
                    <div class="row mb--15">
                        <div class="col mb--20">
                            <div class="single-desc text-descrip-product">
                                <?=$details['description'] ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End .product-desc-wrapper -->
            </div>
        </div>
        <!-- woocommerce-tabs -->
    </div>
    <?php }else{ ?>
        <div class="container my-4" >
            <div class="row my-4">
                <div class="col-12">
                    <div class="text-center my-4">
                        Escríbenos a <a href="mailto:ayuda@easymerx.com">ayuda@easymerx.com</a> si tienes alguna duda.
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
    <!-- Modal ver talla end-->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Obtener el parámetro 'event' del enlace actual
        const urlParams = new URLSearchParams(window.location.search);
        const eventValue = urlParams.get('event'); // Ejemplo: "26"

        if (eventValue) {
            // 2. Buscar en el select la opción con value=eventValue
            const select = document.getElementById('events');
            const option = select.querySelector(`option[value="${eventValue}"]`);

            if (option) {
                // 3. Capturar el atributo 'data-ref' de esa opción
                const dataRef = option.getAttribute('data-href');

                // 4. Asignar el valor de 'data-ref' como un evento onclick al id "volverEvento1"
                const volverEvento1 = document.getElementById('volverEvento1');
                volverEvento1.setAttribute('onclick', `window.location.href='${dataRef}'`);
            }
        }
    });
    </script>
    <!-- Start Expolre Product Area  -->
    <!-- Related Product Start -->
    <?php if(isset($recommendation) && isset($event)){?>
    <?php echo $this->include('themes/'. $currentTheme .'/pages/products_related.php'); ?>
    <?php }?>
    <!-- Related Product end -->
</main>
<?= $this->endSection() ?>