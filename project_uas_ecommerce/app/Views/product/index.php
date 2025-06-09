<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>
<h2>Daftar Produk</h2>
<div class="row">
    <?php if (empty($products)): ?>
        <div class="col-12">
            <div class="alert alert-info">Tidak ada produk yang tersedia.</div>
        </div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="/uploads/<?= esc($product['image']) ?>" 
                         class="card-img-top" 
                         alt="<?= esc($product['name']) ?>"
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/300?text=Produk'">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= esc($product['name']) ?></h5>
                        <p class="card-text flex-grow-1"><?= esc(substr($product['description'], 0, 100)) ?>...</p>
                        <p class="card-text fw-bold">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                        <p class="card-text"><strong>Stok: <?= $product['stock'] ?></strong></p>
                        <a href="/product/<?= $product['id'] ?>" class="btn btn-primary mt-auto">Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
<?= $this->include('templates/footer') ?>