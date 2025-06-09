<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>
<h2>Riwayat Transaksi</h2>
<?php if (session()->has('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>
<?php if (session()->has('success')): ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>
<?php if (empty($transactions)): ?>
    <p>Belum ada transaksi.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Status Pengiriman</th>
                <th>Status Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <?php
                $productModel = new \App\Models\ProductModel();
                $product = $productModel->find($transaction['product_id']);
                $status = $transaction['status'] ?? 'N/A';
                $shippingStatus = $transaction['shipping_status'] ?? 'N/A';
                $paymentStatus = $transaction['payment_status'] ?? 'N/A';
                ?>
                <tr>
                    <td><?= $transaction['id'] ?? 'N/A' ?></td>
                    <td><?= $product ? $product['name'] : 'Tidak diketahui' ?></td>
                    <td><?= $transaction['quantity'] ?? 'N/A' ?></td>
                    <td>Rp <?= number_format($transaction['total_price'] ?? 0, 0, ',', '.') ?></td>
                    <td><?= $status ?></td>
                    <td><?= $shippingStatus ?></td>
                    <td><?= $paymentStatus ?></td>
                    <td>
                        <?php if ($paymentStatus !== 'completed'): ?>
                            <a href="/order/delete_transaction/<?= $transaction['id'] ?? '' ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus transaksi?')">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?= $this->endSection() ?>