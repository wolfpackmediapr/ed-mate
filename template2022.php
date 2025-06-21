<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' | ' : '' ?>Edumate</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= asset_url('images/logo/favicon.png') ?>">
    
    <!-- Third-party CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/file-upload.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/plyr.css') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="<?= asset_url('css/full-calendar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/jquery-ui.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/editor-quill.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/apexcharts.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/calendar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/jquery-jvectormap-2.0.5.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/main.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Page-specific CSS -->
    <?php if (isset($page_css)) : ?>
        <?php foreach ($page_css as $css) : ?>
            <link rel="stylesheet" href="<?= asset_url('css/' . $css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader"></div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="side-overlay"></div>

    <!-- Sidebar -->
    <?php include 'partials/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="dashboard-main-wrapper">
        <!-- Top Navbar -->
        <?php include 'partials/top-navbar.php'; ?>

        <!-- Page Content -->
        <div class="dashboard-body">
            <?= $content ?? '' ?>
        </div>

        <!-- Footer -->
        <?php include 'partials/footer.php'; ?>
    </div>

    <!-- Core JS -->
    <script src="<?= asset_url('js/jquery-3.7.1.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="<?= asset_url('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= asset_url('js/phosphor-icon.js') ?>"></script>
    <script src="<?= asset_url('js/file-upload.js') ?>"></script>
    <script src="<?= asset_url('js/plyr.js') ?>"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="<?= asset_url('js/full-calendar.js') ?>"></script>
    <script src="<?= asset_url('js/jquery-ui.js') ?>"></script>
    <script src="<?= asset_url('js/editor-quill.js') ?>"></script>
    <script src="<?= asset_url('js/apexcharts.min.js') ?>"></script>
    <script src="<?= asset_url('js/calendar.js') ?>"></script>
    <script src="<?= asset_url('js/jquery-jvectormap-2.0.5.min.js') ?>"></script>
    <script src="<?= asset_url('js/jquery-jvectormap-world-mill-en.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?= asset_url('js/main.js') ?>"></script>
    <script src="<?= asset_url('js/dark-mode.js') ?>"></script>

    <!-- Page-specific JS -->
    <?php if (isset($page_js)) : ?>
        <?php foreach ($page_js as $js) : ?>
            <script src="<?= asset_url('js/' . $js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 