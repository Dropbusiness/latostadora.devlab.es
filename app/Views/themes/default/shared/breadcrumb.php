<!-- Start Breadcrumb Area  -->
<div class="axil-breadcrumb-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="inner">
                             <ul class="axil-breadcrumb">
                                <?php
                            if(isset($breadcrumb) &&  !is_null($breadcrumb)){
                                foreach ($breadcrumb as $key=>$value) {
                                if($value!=''){
                                ?>
                                <li class="axil-breadcrumb-item"><a href="<?=$value; ?>">
                                        <?=$key; ?>
                                    </a></li>
                                    <li class="separator"></li>
                                <?php }else{?>
                                <li class="axil-breadcrumb-item active">
                                    <?=$key;?>
                                </li>
                                <?php }
                                }
                            }
                            ?>
                            </ul>
                            <h1 class="title"><?=character_limiter($breadcrumb_title,50)?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb Area  -->