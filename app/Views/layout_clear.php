<?php
$hlm = "Home";
if (uri_string() != "") {
    $hlm = ucwords(uri_string());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= $hlm ?> - Toko</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url() ?>NiceAdmin/assets/img/logo51.png" rel="icon">
  <link href="<?= base_url() ?>NiceAdmin/assets/img/logo51.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --bs-font-sans-serif: 'Plus Jakarta Sans', sans-serif;
      --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
      --premium-lime-gradient: linear-gradient(135deg, #2b2b2b 0%, #1a1a1a 100%);
      --premium-lime-hover: linear-gradient(135deg, #c5a059 0%, #8b6d31 100%);
      --lime-shadow: 0 8px 25px rgba(197, 160, 89, 0.4);
      --luxury-gold-gradient: linear-gradient(135deg, #2b2b2b 0%, #1a1a1a 100%);
      --luxury-gold-hover: linear-gradient(135deg, #c5a059 0%, #8b6d31 100%);
      --luxury-shadow: 0 8px 25px rgba(197, 160, 89, 0.4);
    }
    body, h1, h2, h3, h4, h5, h6, .card-title, .logo span {
      font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    
    /* Premium Gradient Overrides */
    .btn-primary, .bg-primary, .badge.bg-primary,
    .btn-success, .bg-success, .badge.bg-success {
      background: var(--luxury-gold-gradient) !important;
      border: none !important;
      color: #fff !important;
    }
    .btn-primary:hover, .btn-success:hover {
      background: var(--luxury-gold-hover) !important;
      box-shadow: var(--luxury-shadow) !important;
      transform: translateY(-2px);
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .text-primary, .text-success {
      color: #c5a059 !important;
    }
    
    .back-to-top {
      background: var(--luxury-gold-gradient) !important;
      box-shadow: var(--luxury-shadow) !important;
    }
    .back-to-top:hover {
      background: var(--luxury-gold-hover) !important;
      transform: translateY(-3px);
    }
    
    /* Subtle hover effect for all cards */
    .card {
      transition: all 0.3s ease;
    }
    .card:hover {
      box-shadow: 0 10px 30px rgba(197, 160, 89, 0.15) !important;
    }
  </style>

  <!-- Vendor CSS Files -->
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?= base_url() ?>NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= base_url() ?>NiceAdmin/assets/css/style.css" rel="stylesheet">
</head>

<body>

  <main>

    <?= $this->renderSection('content') ?>

  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/echarts/echarts.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="<?= base_url() ?>NiceAdmin/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="<?= base_url() ?>NiceAdmin/assets/js/main.js"></script>
  <!-- Custom Premium Cursor -->
  <?= $this->include("components/custom_cursor") ?>
</body>

</html>
