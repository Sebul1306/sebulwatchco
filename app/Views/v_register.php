<?= $this->extend("layout_clear") ?>
<?= $this->section("content") ?>

<?php
$username = [
    "name" => "username",
    "id" => "username",
    "class" => "form-control",
    "required" => true
];
$email = [
    "name" => "email",
    "id" => "email",
    "type" => "email",
    "class" => "form-control",
    "required" => true
];
$password = [
    "name" => "password",
    "id" => "password",
    "class" => "form-control",
    "required" => true
];
?>

<style>
  body {
    background: #eef2f5 !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
  }
  .split-card {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    background: #fff;
    max-width: 900px;
    margin: 0 auto;
  }
  
  @keyframes slideInFromRight {
    0% { transform: translateX(50px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
  }
  @keyframes slideInFromLeft {
    0% { transform: translateX(-50px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
  }

  .split-form {
    background: #fff;
    padding: 3rem;
    animation: slideInFromRight 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
  }
  .split-banner {
    background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
    padding: 3rem;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
    overflow: hidden;
    animation: slideInFromLeft 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
  }
  
  /* Decorative circles for the banner side */
  .split-banner::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    top: -100px;
    left: -100px;
  }
  .split-banner::after {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    bottom: -50px;
    right: -50px;
  }
  
  .form-control {
    background: #f8f9fa !important;
    border: 1px solid #e9ecef !important;
    padding: 0.75rem 1rem;
    border-radius: 8px;
  }
  .form-control:focus {
    border-color: #8B5A2B !important;
    box-shadow: 0 0 0 3px rgba(139,90,43, 0.1) !important;
  }
  .input-group-text {
    background: #f8f9fa !important;
    border: 1px solid #e9ecef !important;
    border-right: none !important;
    color: #adb5bd !important;
  }
  .form-control {
    border-left: none !important;
  }
  .btn-primary {
    background: #8B5A2B !important;
    border: none !important;
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 600;
  }
  .btn-primary:hover {
    background: #5C4033 !important;
    box-shadow: 0 5px 15px rgba(139,90,43, 0.3) !important;
    transform: translateY(-1px);
  }
  .btn-outline-light {
    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 20px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s;
  }
  .btn-outline-light:hover {
    background: #fff;
    color: #8B5A2B;
  }
  .logo-wrapper {
    width: 180px;
    height: 180px;
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 40px;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .logo-img {
    width: 140%;
    height: 140%;
    object-fit: contain;
    transform: scale(1.1);
  }
</style>

<section class="section min-vh-100 d-flex align-items-center justify-content-center py-4">
  <div class="container">
    <div class="row split-card g-0 flex-column-reverse flex-lg-row">
      
      <!-- Left Side (Info / Banner) -->
      <div class="col-lg-6 split-banner">
      <div class="logo-wrapper position-relative z-1 mx-auto text-center">
        <img src="<?= base_url('NiceAdmin/assets/img/logo51.png') ?>" alt="Logo" class="logo-img">
      </div>
        <h3 class="fw-bold mb-3 position-relative z-1">Sudah punya akun?</h3>
        <p class="mb-4 small position-relative z-1" style="opacity: 0.9; line-height: 1.6;">
          Login dan kelola pesanan<br>
          serta riwayat transaksi Anda<br>
          dengan mudah & cepat.
        </p>
        <a href="<?= base_url('login') ?>" class="btn btn-outline-light position-relative z-1">
          Masuk / Login
        </a>
      </div>

      <!-- Right Side (Form) -->
      <div class="col-lg-6 split-form d-flex flex-column justify-content-center">
        <div class="mb-4">
          <h3 class="fw-bold" style="color: #2b3445;">Buat Akun Baru</h3>
          <p class="text-muted small">Daftarkan akun untuk mengakses sistem</p>
        </div>

        <?php if (session()->getFlashData("failed")): ?>
        <div class="alert alert-danger py-2 px-3 small rounded-3" role="alert">
          <i class="bi bi-exclamation-octagon me-1"></i><?= session()->getFlashData("failed") ?>
        </div>
        <?php endif; ?>

        <?= form_open("register", ["class" => "needs-validation"]) ?>
          
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Username</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <?= form_input($username) ?>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <?= form_input($email) ?>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label small fw-semibold text-dark">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <?= form_password($password) ?>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-3">
            <i class="bi bi-person-plus"></i> Buat Akun
          </button>
          
          <div class="d-flex justify-content-center gap-2 mt-2">
            <a href="<?= base_url('auth/google/register') ?>" class="btn btn-light border w-100 d-flex justify-content-center align-items-center gap-2" style="font-size: 0.85rem;">
              <i class="bi bi-google text-danger"></i> Google
            </a>
          </div>

        <?= form_close() ?>
      </div>

    </div>
  </div>
</section>

<?= $this->endSection() ?>
