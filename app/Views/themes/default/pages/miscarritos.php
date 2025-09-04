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
                    <a href="<?= base_url_locale('micuenta') ?>" class="title-highlighter highlighter-primary2"><i class="fas fa-home"></i> <?= front_translate('General', 'my-account') ?></a>
                </div>
                <div class="col-12">
                    <h2 class="my-3"><?= front_translate('General', 'order-list') ?></h2>
                </div>
                <div class="col-md-12">
                    <?= view('themes/' . $currentTheme . '/shared/flash_message') ?>
                </div>
                <div class="col-12">
                    <?php if ($alldata) : ?>
                        <div class="axil-dashboard-order">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-default">
                                        <tr>
                                            <th>Nº</th>
                                            <th><?= front_translate('General', 'date') ?></th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($alldata as $item) : ?>
                                            <tr>
                                                <td><?= $item['id'] ?></td>
                                                <td><?= date("d/m/Y", strtotime($item['updated_at'])) ?></td>
                                                <td><?= $item['order_status'] ?></td>
                                                <td><?= $item['order_total'] ?></td>
                                                <td width="290">
                                                    <a class="axil-btn view-btn" href="<?= base_url_locale('detallecarrito/' . $item['id']) ?>">
                                                        <?= front_translate('General', 'detail') ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-right  py-3">
                            <?php echo $pager ?>
                        </div>
                    <?php else : ?>
                        <?= front_translate('General', 'no-record') ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- End Privacy & Policy page -->
</main>
<?= $this->endSection() ?>