<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Edmate</title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <style>
        .auth { min-height: 100vh; background-color: #f8f9fa; }
        .auth-left { background-color: #f0f4ff; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .auth-right { display: flex; align-items: center; justify-content: center; padding: 2.5rem; }
        .auth-right__inner { max-width: 400px; width: 100%; }
        .auth-right__logo { display: block; margin-bottom: 2rem; }
        .auth-right__logo img { max-height: 40px; }
        .form-control { padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 0.5rem; }
        .btn-main { background-color: #4f46e5; color: white; padding: 0.75rem 1.5rem; border-radius: 9999px; }
        .btn-main:hover { background-color: #4338ca; color: white; }
        .text-main-600 { color: #4f46e5; }
    </style>
</head>

<body>
    <section class="auth d-flex">
        <div class="auth-left d-none d-lg-flex">
            <img src="<?= base_url('assets/images/thumbs/auth-img1.png') ?>" alt="Forgot Password" class="img-fluid">
        </div>
        <div class="auth-right">
            <div class="auth-right__inner">
                <a href="<?= base_url() ?>" class="auth-right__logo">
                    <img src="<?= base_url('assets/images/logo/logo.png') ?>" alt="Edmate">
                </a>
                <h2 class="mb-3">Forgot Password</h2>
                <p class="text-muted mb-4">Enter your email to receive a password reset link</p>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                <?php endif; ?>
                <form method="post" action="<?php echo base_url('forgot-password'); ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="position-relative">
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                            <i class="ph ph-envelope position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-main w-100 mb-4">Send Reset Link</button>
                    <p class="text-center text-muted">Remembered your password?
                        <a href="<?= base_url('login') ?>" class="text-main-600 text-decoration-none">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </section>
</body>
</html> 