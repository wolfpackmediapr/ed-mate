<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Login | Edmate</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.png') ?>">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Phosphor Icons -->
    <link href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css" rel="stylesheet">
    <!-- Main css -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <style>
        .auth {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .auth-left {
            background-color: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
        }
        .auth-right__inner {
            max-width: 400px;
            width: 100%;
        }
        .auth-right__logo {
            display: block;
            margin-bottom: 2rem;
        }
        .auth-right__logo img {
            max-height: 40px;
        }
        .form-control {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border-radius: 0.5rem;
        }
        .btn-main {
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
        }
        .btn-main:hover {
            background-color: #4338ca;
            color: white;
        }
        .text-main-600 {
            color: #4f46e5;
        }
        .divider {
            position: relative;
            text-align: center;
            margin: 2rem 0;
        }
        .divider__text {
            background-color: white;
            padding: 0 1rem;
            position: relative;
            z-index: 1;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #e5e7eb;
        }
    </style>
</head>

<body>
    <section class="auth d-flex">
        <div class="auth-left d-none d-lg-flex">
            <img src="https://images.pexels.com/photos/1181353/pexels-photo-1181353.jpeg?auto=compress&w=700&q=80" alt="Student on Laptop" class="img-fluid rounded-4 shadow">
        </div>
        <div class="auth-right">
            <div class="auth-right__inner">
                <a href="<?= base_url() ?>" class="auth-right__logo">
                    <img src="<?= base_url('assets/images/logo/logo.png') ?>" alt="Edmate">
                </a>
                <h2 class="mb-3">Welcome Back! 👋</h2>
                <p class="text-muted mb-4">Please sign in to your account and start the adventure</p>
                
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="position-relative">
                            <input name="email" type="email" class="form-control" id="email" placeholder="Enter your email">
                            <i class="ph ph-envelope position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="position-relative">
                            <input name="password" type="password" class="form-control" id="password" placeholder="Enter your password">
                            <i class="ph ph-lock position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                            <i class="ph ph-eye-slash position-absolute top-50 translate-middle-y end-0 me-3 text-muted" style="cursor: pointer;" onclick="togglePassword()"></i>
                        </div>
                    </div>
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <a href="<?= base_url('forgot-password') ?>" class="text-main-600 text-decoration-none">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-main w-100 mb-4">Sign In</button>
                    <p class="text-center text-muted">New on our platform?
                        <a href="<?= base_url('register') ?>" class="text-main-600 text-decoration-none">Create an account</a>
                    </p>

                    <div class="divider">
                        <span class="divider__text">or</span>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-outline-primary rounded-circle p-2">
                            <i class="ph-fill ph-facebook-logo"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info rounded-circle p-2">
                            <i class="ph-fill ph-twitter-logo"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger rounded-circle p-2">
                            <i class="ph ph-google-logo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.ph-eye-slash');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            }
        }
    </script>
</body>

</html>