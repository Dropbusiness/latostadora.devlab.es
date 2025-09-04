<?= $this->extend('themes/' . $currentTheme . '/layout') ?>

<?= $this->section('content') ?>
<main class="main-wrapper">
    <!-- Ec breadcrumb start -->
    <?php echo $this->include('themes/' . $currentTheme . '/shared/breadcrumb.php'); ?>
    <!-- Ec breadcrumb end -->

    <!-- Start Privacy & Policy page -->
    <section class="axil-section-gap">
        <div class="container">
            <div class="row">
                <div class="col text-right">
                    <a href="<?= base_url_locale('miscarritos') ?>" class="title-highlighter highlighter-primary2"><i class="fas fa-shopping-basket"></i>  <?= front_translate('General', 'return-list') ?> </a>
                </div>
                <div class="col-12" id="print">
                    <h2 class="page-heading bottom-indent"><?= front_translate('General', 'budget-detail') ?></h2>
                    <div id="content-wrapper" class="page-order-detail">
                        <section id="main">
                            <section id="content" class="page-content">
                                <div id="order-infos">
                                    <div class="box">
                                        Nº <?= front_translate('General', 'cart') ?> <?= $order['id'] ?> - <?= front_translate('General', 'made-the') ?> <?= date("d/m/Y", strtotime($order['updated_at'])) ?><br>
                                        <b><?= front_translate('General', 'date') ?> de <?= front_translate('General', 'budget') ?> :</b> <?= $order['updated_at'] ?> <br>
                                        <b><?= front_translate('General', 'budget-comments') ?>:</b> <br>
                                        <?= $order['order_obs'] ?>
                                    </div>
                                </div>
                                <section id="order-history-detail" class="box pt-4">
                                    <h5><?= front_translate('General', 'products-detail') ?></h5>
                                    <div class="axil-dashboard-order">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th><?= front_translate('General', 'product') ?></th>
                                                        <th class="text-center"><?= front_translate('General', 'quantity') ?></th>
                                                        <th class="text-right"><?= front_translate('General', 'price') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($orderitems as $item) : ?>
                                                        <tr>
                                                            <td><?= $item['product_name'] ?></td>
                                                            <td class="text-center"><?= number_format($item['product_quantity'], 0, ',', '.') ?></td>
                                                            <td class="text-right"><?= number_format($item['product_price'], 2, ',', '.') ?>€</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </section>
                        </section>
                    </div>
                </div>
               
            </div>
        </div>
    </section>
    <!-- End Privacy & Policy page -->
</main>
<?= $this->endSection() ?>