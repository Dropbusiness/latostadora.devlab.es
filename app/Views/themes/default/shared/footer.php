 <!-- Start Footer Area  -->
 <footer class="axil-footer-area footer-style-1 footer-color">
        <!-- Start Footer Top Area  -->
        <div class="footer-top pt-4 pb-1">
            <div class="container">
                <div class="row justify-content-between">
                    <!-- Start Single Widget  -->
                    <div class="col-md-4 col-sm-12 order-lg-1">
                        <div class="axil-footer-widget mt-sm-5">
                            <div class="logo">
                                <a href="#">
                                    <img class="light-logo" src="/themes/default/assets/images/EasyMerxby_tostadora.png" style="" alt="Site Logo">
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                    <div class="col-md-8 col-sm-12 order-2 order-lg-3">
                        <div class="row mt-2">
                        <!-- Start Single Widget  -->
                        <div class="col-12  col-sm-6">
                            <div class="axil-footer-widget ">
                                <div class="inner">
                                    <ul>
                                        <li><h5 class="widget-title "><a class="footer-menu footer-text" href="<?=base_url_locale('contenido/'.$allpage[1]['link_rewrite']) ?>" target="_blank"><?=$allpage[1]['name']?></a></h5></li>
                                        <li><h5 class="widget-title "><a class="footer-menu footer-text" href="<?=base_url_locale('contenido/'.$allpage[6]['link_rewrite']) ?>" target="_blank"><?=$allpage[6]['name']?></a></h5></li>
                                        <li><h5 class="widget-title "><a class="footer-menu footer-text" href="<?=base_url_locale('contenido/'.$allpage[9]['link_rewrite']) ?>" target="_blank"><?=$allpage[9]['name']?></a></h5></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Widget  -->
                        <!-- Start Single Widget  -->
                        <div class="col-12  col-sm-6">
                                <div class="axil-footer-widget ">
                                    <div class="inner">
                                        <ul>
                                            <li>
                                        <h5 class="widget-title"><a class="footer-menu footer-text" href="<?=base_url_locale('contacto')?>" target="_blank"><?=front_translate('General','contact-customer')?></a></h5>
                                        </ul>
                                    </div>
                                </div>
                                <div class="axil-footer-widget m-0">
                                    <div class=" widget-title footer-menu footer-text">
                                    © Nextalia Ventures SL - B63771349
                                    </div>
                                </div>
                        </div>
                        <!-- End Single Widget  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Top Area  -->
      
</footer>
    <!-- End Footer Area  -->



<div id="idxrcookies" class="hide ">
    <div id="bottom" class="withRejectButton" >
        <div class="row contenido">
            <div class="col-12 text-center">
                <p class="m-0 p-3">
                <?=front_translate('General','cookie-short')?>
                    <a style="color: black; text-decoration: underline;" rel="nofollow" href="<?=base_url_locale('contenido/'.$allpage[9]['link_rewrite']) ?>"><?=front_translate('General','cookie-policy')?></a><br>
                    <?=front_translate('General','cookie-short1')?>
                </p>
            </div>
            <div class="col-12 text-center">
                        <a  class="axil-btn btn-outline" id="idxrcookiesOK" rel="nofollow"><?=front_translate('General','accept')?></a>
                        <a  class="axil-btn btn-outline" id="cookiesConf"  rel="nofollow" data-bs-toggle="modal" data-bs-target="#modal_cookies">
                        <?=front_translate('General','setting')?>
                        </a>
            </div>
        </div>            
    </div>
</div>
<div class="modal fade" id="modal_cookies" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="cookieModalHeader" class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?=front_translate('General','prefer-cookie')?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                
                <div id="cookieModalBody">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Info</button>
                            <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"><?=front_translate('General','cookie-neces')?></button>
                            <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false"><?=front_translate('General','cookie-func')?></button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false"><?=front_translate('General','how-delete0')?></button>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <p>
                                <?=front_translate('General','info-cookie')?>                                
                                </p>
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <p class="cookie-content-title"><?=front_translate('General','cookie-neces')?></p>
                                <p class="always-active"><i class="always-check"></i> <?=front_translate('General','cookie-neces1')?></p>        
                                <p>
                                <?=front_translate('General','cookie-neces2')?>
                                </p>
                                <p class="cookie-content-subtitle"><?=front_translate('General','cookie-neces3')?></p>
                                <div class="info-cookie-list">
                                         <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault2" disabled>
                                            <label class="form-check-label" for="flexSwitchCheckDefault2">https://easymerx.com - cookie_ue</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault3" disabled>
                                            <label class="form-check-label" for="flexSwitchCheckDefault3">https://easymerx.com - PHPSESSID</label>
                                        </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                <p class="cookie-content-title"><?=front_translate('General','cookie-func')?></p>
                                <p>
                                <?=front_translate('General','cookie-func1')?>
                                </p>
                                <p class="cookie-content-subtitle"><?=front_translate('General','cookie-neces3')?></p>
                                <div class="info-cookie-list">
                                        <div class="form-check form-switch ps-4">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked>
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Google Analytics</label>
                                        </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <p>
                                <?=front_translate('General','how-delete')?>                            
                                </p>
                                <p>                                
                                <?=front_translate('General','how-delete1')?>
                                </p>
                                <p><strong><em>Firefox <?=front_translate('General','how-delete2')?> </em></strong> <a target="_blank" href="https://support.mozilla.org/t5/Cookies-y-caché/Habilitar-y-deshabilitar-cookies-que-los-sitios-web-utilizan/ta-p/13811" rel="noreferrer noopener">http://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-que-los-sitios-web</a></p>
                                <p><strong><em>Chrome <?=front_translate('General','how-delete2')?> </em></strong> <a target="_blank" href="https://support.google.com/chrome/answer/95647?hl=es" rel="noreferrer noopener">https://support.google.com/chrome/answer/95647?hl=es</a></p>
                                <p><strong><em>Explorer <?=front_translate('General','how-delete2')?> </em></strong><span> <a target="_blank" href="https://support.microsoft.com/es-es/help/17442/windows-internet-explorer-delete-manage-cookies" rel="noreferrer noopener">https://support.microsoft.com/es-es/help/17442/windows-internet-explorer-delete-manage-cookies</a></span></p>
                                <p><strong><em>Safari <?=front_translate('General','how-delete2')?> </em></strong><a target="_blank" href="https://support.apple.com/kb/ph5042?locale=es_ES" rel="noreferrer noopener"><span>http://support.apple.com/kb/ph5042</span></a></p>
                                <p><strong><em>Opera <?=front_translate('General','how-delete2')?> </em></strong><a target="_blank" href="http://help.opera.com/Windows/11.50/es-ES/cookies.html" rel="noreferrer noopener"><span>http://help.opera.com/Windows/11.50/es-ES/cookies.html</span></a></p>
                            </div>
                        </div>
                    </div>

                </div>                
            </div>
            <div class="modal-footer">
                <div class="row" style="width: 100%;">
                    <div class="col-xs-12 col-md-4 text-left px-0">
                        <a class="cookie-info-page text-left" rel="nofollow" href="<?=base_url_locale('contenido/'.$allpage[9]['link_rewrite']) ?>">
                        <?=front_translate('General','cookie-policy')?>
                        </a>
                    </div>
                    <div class="col-xs-12 col-md-8 text-right px-0">
                        <a id="close_manage_cookie"  class="axil-btn btn-outline" data-bs-dismiss="modal" aria-label="Close"><?=front_translate('General','close')?></a>
                        <a id="save_manage_cookie"  class="axil-btn btn-outline"><?=front_translate('General','save')?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" >
    var COOKIE_MSG="Esta web utiliza cookies técnicas, de personalización y de análisis, propias y de terceros, para anónimamente facilitarle la navegación y analizar estadísticas del uso de la web.";
	var COOKIE_TXTACEPTAR="ACEPTAR";
	var COOKIE_TXTINFO="INFORMACIÓN";
	var COOKIE_URLINFO="<?=base_url_locale('contenido/'.$allpage[9]['link_rewrite']) ?>";
</script>