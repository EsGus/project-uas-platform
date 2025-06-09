<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>
<h2>Upload Bukti Transfer</h2>
<?php if (session()->has('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>
<form action="<?= base_url('order/proof/') . $order['id'] ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="proof_image" class="form-label">Bukti Transfer</label>
        <input type="file" class="form-control" id="proof_image" name="proof_image" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-primary">Upload</button>
</form>
<?= $this->endSection() ?>