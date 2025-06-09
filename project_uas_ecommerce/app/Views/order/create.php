<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>
<h2>Buat Pesanan</h2>
<form action="/order/store" method="post">
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    <div class="mb-3">
        <label for="product" class="form-label">Produk</label>
        <input type="text" class="form-control" id="product" value="<?= $product['name'] ?>" disabled>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Harga</label>
        <input type="text" class="form-control" id="price" value="Rp <?= number_format($product['price'], 0, ',', '.') ?>" disabled>
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">Jumlah</label>
        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
    </div>
    <button type="submit" class="btn btn-primary">Buat Pesanan</button>
</form>
<?= $this->endSection() ?>
<?= $this->include('templates/footer') ?>