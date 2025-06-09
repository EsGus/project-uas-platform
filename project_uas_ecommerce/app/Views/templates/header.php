<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Band Merch</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/custom.css"> <!-- Jika ada CSS custom -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">Band Merch</a>
            <div class="navbar-nav">
                <?php if (session()->has('user_id')): ?>
                    <?php
                        $userModel = new \App\Models\UserModel();
                        $user = $userModel->find(session()->get('user_id'));
                    ?>
                    <?php if ($user['username'] === 'admin'): ?>
                        <a class="nav-link" href="/admin">Admin</a>
                        <a class="nav-link" href="/logout">Logout</a>
                    <?php else: ?>
                        <a class="nav-link" href="/">Beranda</a>
                        <a class="nav-link" href="/cart">Keranjang</a>
                        <a class="nav-link" href="/cart/payment">Pembayaran</a>
                        <a class="nav-link" href="/transactions">Riwayat Transaksi</a>
                        <a class="nav-link" href="/logout">Logout</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a class="nav-link" href="/">Beranda</a>
                    <a class="nav-link" href="/login">Login</a>
                    <a class="nav-link" href="/register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
        <?php endif; ?>
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success"><?= session('success') ?></div>
        <?php endif; ?>
    </div>
    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>