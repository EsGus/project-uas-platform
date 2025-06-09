<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>

<h2><?= $product['name'] ?></h2>
<div class="row">
    <div class="col-md-6">
        <img src="/uploads/<?= $product['image'] ?>" class="img-fluid" alt="<?= $product['name'] ?>">
    </div>
    <div class="col-md-6">
        <p><?= $product['description'] ?></p>
        <p><strong>Harga:</strong> Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
        <p><strong>Stok:</strong> <?= $product['stock'] ?></p>

        <?php if (session()->has('user_id')): ?>
            <?php if ($product['stock'] > 0): ?>
                <form action="/cart/add" method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" class="form-control d-inline-block w-25" id="quantity" name="quantity"
                               value="1" min="1" max="<?= $product['stock'] ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
                    <a href="/order/create/<?= $product['id'] ?>" class="btn btn-success">Beli Sekarang</a>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">Stok habis</div>
            <?php endif; ?>
        <?php else: ?>
            <a href="/login" class="btn btn-warning">Login untuk Membeli</a>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
