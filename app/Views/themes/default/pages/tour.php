<?= $this->extend('themes/'. $currentTheme .'/layout') ?>
<?= $this->section('content') ?>
<main class="main-wrapper">
    <!-- Ec breadcrumb start -->
    <section class="axil-shop-area axil-section-gap bg-color-white" style="padding-top:3px;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <?php if($tour['img']!=''){?>
                    <img src="/uploads/tours/original/<?=$tour['img']?>" class="img-fluid">
                    <?php }?>
                </div>
            </div>
            <div id="lista-productos" class="row justify-content-center mt-5 mb--100 text-center">
            <?php
            if(count($events)>0){
                // Agrupar eventos por ciudad
                $groupedEvents = [];
                foreach ($events as $event) {
                    $groupedEvents[$event['city']][] = $event;
                }

                // Ordenar eventos por fecha descendente dentro de cada ciudad
                foreach ($groupedEvents as &$cityEvents) {
                    usort($cityEvents, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
                }
                unset($cityEvents); // Liberar referencia para evitar problemas

                // Ordenar ciudades por fecha más tardía (descendente)
                uksort($groupedEvents, fn($a, $b) => strtotime($groupedEvents[$b][0]['date']) - strtotime($groupedEvents[$a][0]['date']));

                // Generar HTML
                foreach ($groupedEvents as $city => $cityEvents) {
                    echo '<div class="col-12"><h2 class="mb-2">' . esc($city) . '</h2></div>';
                    
                    foreach ($cityEvents as $event) {
                        $url = base_url_locale("event/{$event['artist_slug']}/{$event['tour_slug']}/{$event['slug']}/{$event['date']}");
                        $date = date("d/m/Y", strtotime($event['date']));
                        echo '<div class="col-12"><h4 class="mb-2"><a href="' . $url . '" class="text-decoration-underline">' . $date . '</a></h4></div>';
                    }
                }
            }else{
                echo '<div class="col-12"><div class="mb-2">Lo sentimos, no hay ningún evento disponible en estos momentos. Escríbenos a <a href="mailto:ayuda@easymerx.com">ayuda@easymerx.com</a> si tienes alguna duda.</div></div>';
            }
                ?>
            </div>
        </div>
    </section>
</main>
<?= $this->endSection() ?>