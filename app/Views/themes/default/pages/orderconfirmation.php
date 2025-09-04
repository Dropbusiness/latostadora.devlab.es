<?= $this->extend('themes/'. $currentTheme .'/layout') ?>
<?= $this->section('content') ?>
<?php 
    $event_id = $order_details[0]['events_id'];
    $eventDetails = get_event_details($event_id);
?>
<script>
/* SealMetrics Tracker Code */
(function() {
var options = {
   account: '6823a76ff0be2a6fcf15609b',
   event: 'conversion',
   label: 'Purchase',
   amount: '<?=$order['order_total']??0?>',
   use_session: 1,
};
var url="//app.sealmetrics.com/tag/v2/tracker";function loadScript(callback){var script=document.createElement("script");script.src=url;script.async=true;script.onload=function(){if(typeof callback==="function"){callback();}};script.onerror=function(){console.error("Error loading script: "+url);};document.getElementsByTagName("head")[0].appendChild(script);}loadScript(function(){options.id=Math.floor((Math.random()*999)+1);if(window.sm){var instance=new window.sm(options);instance.track(options.event);}else{console.error("sm2 plugin is not available");}});
})();
/* End SealMetrics Tracker Code */
</script>

    <!-- Ec checkout page -->
    <section class="ec-page-content theme_<?= $eventDetails['tour_id']; ?>">
        <div class="container">
            <div class="row">
                <div class="col-12 py-5">
                    <div class="position-relative">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16" style="
                                color: green;
                            ">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"></path>
                            </svg></div>
                            <div class="vide_content text-center text-success">
                            <h1><i class="ecicon eci-check-circle"></i> <?=front_translate('General','confir-text')?></h1>
                            <h2><?=front_translate('General','confir-number')?><?=$order['order_reference']?></h2>
                        </div>
                        <h1 style="color:#333;font-family:Arial;font-size:20px"><?=front_translate('General', 'hi')?> , <?= ($order['order_company']!=''?$order['order_company']:$order['order_firstname'])?></h1>
                        <h3 style="color:#333;font-family:Arial;font-size:13px"><?= front_translate('General', 'msj-budget')?> </h3>
                        <p style="color:#333;font-family:Arial;font-size:12px"><?= front_translate('General', 'msj-budget1')?> </p>

                        <table border="0" width="100%" style="width:100%;border-collapse:collapse;border-left:1px solid #d6d4d4;border-right:1px solid #d6d4d4;border-bottom:1px solid #d6d4d4;" >
                            <tr>
                                <th style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" width="22%" ><?=front_translate('General', 'product')?></th>
                                <th style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" width="17%" ><?=front_translate('General', 'unit-price') ?></th>
                                <th style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" ><?= front_translate('General', 'quantity')?></th>
                                <th style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" width="17%" ><?=front_translate('General', 'total-price') ?></th>
                            </tr>
                            <?php   foreach ($order_details as $key => $c)  {  ?>
                            <tr>
                                <td  width="22%" style="color:#333;font-family:Arial;font-size:12px;padding-right:5px"><?=$c['product_name']?></td>
                                <td width="17%" align="right" style="color:#333;font-family:Arial;font-size:12px;padding-right:5px"><?=number_format($c['product_price'],2,',','.')?>€</td>
                                <td  align="center" style="color:#333;font-family:Arial;font-size:12px;padding-right:5px"><?=$c['product_quantity']?></td>
                                <td width="17%"  align="right" style="color:#333;font-family:Arial;font-size:12px;padding-right:5px"><?=number_format($c['product_price']*$c['product_quantity'],2,',','.')?>€</td>
                            </tr><?php } ?>
                        </table>
                        <table border="0" width="100%">
                            <tbody>
                                <tr >
                                    <td align="right">
                                        <table border="0" width="300">
                                            <tbody>
                                                <tr >
                                                    <td align="right" style="color:#333;font-family:Arial;font-size:13px;padding-right:5px">Gastos de envío</td>
                                                    <td align="right" style="color:#333;font-family:Arial;font-size:13px;padding-right:5px; width: 115px;"><?=($order['order_subtotal']>0?number_format($order['order_shipping_price'],2,',','.').'€':'-')?></td>
                                                </tr>
                                                <tr >
                                                    <td align="right" style="color:#333;font-family:Arial;font-size:18px;padding-right:5px;font-weight:bold">Total</td>
                                                    <td align="right" style="color:#333;font-family:Arial;font-size:18px;padding-right:5px;font-weight:bold; width: 115px;"><?=($order['order_subtotal']>0?number_format($order['order_total'],2,',','.').'€':'-')?></td>
                                                </tr>
                                        </table>
                                    </td>
                                </tr>
                        </table>
                        <br/><br/>
                        <table border="0" width="100%" style="width:100%;border-collapse:collapse;border-top:1px solid #d6d4d4;border-left:1px solid #d6d4d4;border-right:1px solid #d6d4d4;border-bottom:1px solid #d6d4d4;" >
                            <tbody>
                                <tr>
                                    <td style="padding-left:7px;color:#333;font-family:Arial;font-size:13px;">
                                        <p style="border-bottom:1px solid #d6d4d4;margin:3px 0 7px;font-weight:bold;font-size:15px;padding-bottom:10px;color:#333;font-family:Arial;">
                                        <?=front_translate('General', 'budget-detail') ?>:</p>
                                        <b>Nº <?=front_translate('General', 'budget') ?>:</b> <?=$order['order_reference']?> <br>
                                        <b><?= front_translate('General', 'date') ?> de <?=front_translate('General', 'budget') ?>:</b> <?=$order['updated_at']?> <br>
                                        <b>Método de envío:</b> <?=(isset($methodshippings[$order['shipping_id']])?$methodshippings[$order['shipping_id']]['name']:'-')?> <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?= site_url().'event/'.$eventDetails['artist_slug']."/".$eventDetails['tour_slug']."/".$eventDetails['slug']."/".$eventDetails['date'];?>" class="btn btn-dark seguir_cprando"><?= front_translate('General', 'continue-buy') ?></a>
                </div>
            </div>
        </div>
    </section>
    <?= $this->endSection() ?>