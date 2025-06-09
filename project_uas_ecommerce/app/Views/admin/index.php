<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>
<h2>Admin Panel - Daftar Pesanan</h2>
<?php if (session()->has('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>
<?php if (session()->has('success')): ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produk</th>
            <th>Pengguna</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Status Pengiriman</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <?php
            $productModel = new \App\Models\ProductModel();
            $product = $productModel->find($order['product_id']);
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($order['user_id']);
            ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $product ? $product['name'] : 'Tidak diketahui' ?></td>
                <td><?= $user ? $user['username'] : 'Tidak diketahui' ?></td>
                <td><?= $order['quantity'] ?></td>
                <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                <td><?= $order['status'] ?></td>
                <td><?= $order['shipping_status'] ?></td>
                <td>
                    <?php if ($order['status'] === 'pending'): ?>
                        <a href="/admin/approve/<?= $order['id'] ?>" class="btn btn-success btn-sm">Setujui</a>
                    <?php elseif ($order['status'] === 'confirmed' && $order['shipping_status'] === 'pending'): ?>
                        <a href="/admin/ship/<?= $order['id'] ?>" class="btn btn-info btn-sm">Kirim</a>
                    <?php elseif ($order['status'] !== 'cancelled'): ?>
                        <a href="/admin/cancel_order/<?= $order['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin membatalkan pesanan?')">Batalkan</a>
                    <?php else: ?>
                        <a href="/admin/delete_order/<?= $order['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesanan?')">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>