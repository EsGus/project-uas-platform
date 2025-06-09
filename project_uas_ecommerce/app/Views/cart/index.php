<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>
<h2>Keranjang Belanja</h2>
<?php if (empty($cart_items)): ?>
    <p>Keranjang belanja Anda kosong.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $grandTotal = 0; ?>
            <?php foreach ($cart_items as $item): ?>
                <?php $total = $item['price'] * $item['quantity']; $grandTotal += $total; ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                    <td>
                        <a href="/cart/remove/<?= $item['id'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td colspan="2">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
    <a href="/cart/checkout" class="btn btn-primary">Checkout</a>
<?php endif; ?>
<?= $this->endSection() ?>