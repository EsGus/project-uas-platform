<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>
<h2>Pembayaran</h2>

<div class="alert alert-info">
    <h5>Informasi Pembayaran</h5>
    <p>Silakan transfer ke rekening berikut:</p>
    <ul>
        <li><strong>Nama Penerima:</strong> Band Merch Store</li>
        <li><strong>Bank:</strong> BCA</li>
        <li><strong>Nomor Rekening:</strong> 123-456-7890</li>
    </ul>
</div>

<?php if (empty($orders)): ?>
    <p>Tidak ada pesanan yang perlu dibayar.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <?php
                $productModel = new \App\Models\ProductModel();
                $product = $productModel->find($order['product_id']);
                ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $product ? $product['name'] : 'Tidak diketahui' ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                    <td>
                        <p>Payment Status: <?= $order['payment_status'] ?></p> <!-- Debugging -->
                        <?php if ($order['payment_status'] === 'pending'): ?>
                            <a href="/order/cancel_payment/<?= $order['id'] ?>" class="btn btn-warning btn-sm" onclick="return confirm('Yakin ingin membatalkan pembayaran?')">Batalkan</a>
                            <a href="/order/upload_proof/<?= $order['id'] ?>" class="btn btn-primary btn-sm">Upload Bukti Pembayaran</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?= $this->endSection() ?>