<nav class="nav flex-column nav-pills">
    <a class="nav-link <?= ($productMenu == 'product_details') ? 'active' : '' ?>" href="<?= site_url('admin/products/'. $product['id'] .'/edit') ?>">Producto detalle</a>
    <a class="nav-link <?= ($productMenu == 'product_attributes') ? 'active' : '' ?>" href="<?= site_url('admin/products/'. $product['id'] .'/attributes') ?>">Producto combinaciones</a>
    <a class="nav-link <?= ($productMenu == 'product_features') ? 'active' : '' ?>" href="<?= site_url('admin/products/'. $product['id'] .'/features') ?>">Producto características</a>
    <a class="nav-link <?= ($productMenu == 'product_images') ? 'active' : '' ?>" href="<?= site_url('admin/products/'. $product['id'] .'/images') ?>">Producto Imagen</a>
</nav>