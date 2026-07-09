<?= $this->extend("layout_clear") ?>
<?= $this->section("content") ?>

<?php
$is_active_register = (isset($is_register) && $is_register) || session()->getFlashData('failed_register') ? 'right-panel-active' : '';
?>

<style>
  :root {
    --primary-gold: #d4af37;
    --secondary-brown: #8b5a2b;
    --bg-slate: #0f172a;
    --bg-slate-light: #1e293b;
  }

  body {
    background-color: var(--bg-slate) !important;
    background-image: radial-gradient(circle at top right, var(--bg-slate-light), var(--bg-slate)) !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
  }

  .split-container {
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 24px;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 0 0 1px rgba(255,255,255,0.05);
    position: relative;
    overflow: hidden;
    width: 1000px;
    max-width: 100%;
    min-height: 600px;
  }

  .form-container {
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.7s cubic-bezier(0.645, 0.045, 0.355, 1);
    padding: 3.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .sign-in-container {
    left: 0;
    width: 50%;
    z-index: 2;
    opacity: 1;
  }

  .split-container.right-panel-active .sign-in-container {
    transform: translateX(100%);
    opacity: 0;
  }

  .sign-up-container {
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
  }

  .split-container.right-panel-active .sign-up-container {
    transform: translateX(100%);
    opacity: 1;
    z-index: 5;
  }

  .overlay-container {
    position: absolute;
    top: 0;
    left: 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
    transition: transform 0.7s cubic-bezier(0.645, 0.045, 0.355, 1);
    z-index: 100;
    border-radius: 0 24px 24px 0;
  }

  .split-container.right-panel-active .overlay-container {
    transform: translateX(-100%);
    border-radius: 24px 0 0 24px;
  }

  .overlay {
    background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(139, 90, 43, 0.8) 100%), url('https://images.unsplash.com/photo-1670177257750-9b47927f68eb?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxsdXh1cnklMjB3YXRjaCUyMGRhcmslMjBlbGVnYW50fGVufDF8fHx8MTc4MjY3MTM2M3ww&ixlib=rb-4.1.0&q=80&w=1080');
    background-size: cover;
    background-position: center;
    color: #FFFFFF;
    position: relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: transform 0.7s cubic-bezier(0.645, 0.045, 0.355, 1);
  }

  .split-container.right-panel-active .overlay {
    transform: translateX(50%);
  }

  .overlay-panel {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 50px;
    text-align: center;
    top: 0;
    height: 100%;
    width: 50%;
    transform: translateX(0);
    transition: transform 0.7s cubic-bezier(0.645, 0.045, 0.355, 1);
  }

  .overlay-left { transform: translateX(-20%); }
  .split-container.right-panel-active .overlay-left { transform: translateX(0); }
  .overlay-right { right: 0; transform: translateX(0); }
  .split-container.right-panel-active .overlay-right { transform: translateX(20%); }

  h3.fw-bold { font-weight: 800 !important; color: #ffffff !important; letter-spacing: -0.5px; }
  .form-label { font-weight: 600 !important; color: #cbd5e1 !important; letter-spacing: 0.5px; text-transform: uppercase; font-size: 0.75rem; }

  .custom-input-group {
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    display: flex;
    align-items: center;
  }
  .custom-input-group:focus-within {
    border-color: var(--primary-gold);
    background: rgba(255, 255, 255, 0.06);
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.15);
  }
  .custom-input-group .input-group-text {
    background: transparent !important;
    border: none !important;
    color: #94a3b8 !important;
    padding: 1rem 1.25rem !important;
    transition: color 0.3s ease;
  }
  .custom-input-group:focus-within .input-group-text { color: var(--primary-gold) !important; }
  .custom-input-group .form-control {
    background: transparent !important;
    border: none !important;
    color: #ffffff !important;
    padding: 1rem 1rem 1rem 0 !important;
    font-weight: 500;
    box-shadow: none !important;
  }
  .custom-input-group .form-control::placeholder { color: #475569 !important; font-weight: 500; }
  
  /* Fix Browser Autofill Background */
  .custom-input-group .form-control:-webkit-autofill,
  .custom-input-group .form-control:-webkit-autofill:hover, 
  .custom-input-group .form-control:-webkit-autofill:focus, 
  .custom-input-group .form-control:-webkit-autofill:active {
    -webkit-background-clip: text;
    -webkit-text-fill-color: #ffffff !important;
    transition: background-color 5000s ease-in-out 0s;
    box-shadow: inset 0 0 20px rgba(255,255,255,0);
  }

  .btn-gold {
    background: linear-gradient(135deg, var(--primary-gold), var(--secondary-brown)) !important;
    border: none !important;
    border-radius: 16px !important;
    padding: 1rem !important;
    font-weight: 700 !important;
    color: #ffffff !important;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(139, 90, 43, 0.3) !important;
  }
  .btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4) !important;
    background: linear-gradient(135deg, #e6c553, #a06b35) !important;
  }

  .btn-outline-gold {
    border: 1px solid rgba(212, 175, 55, 0.5) !important;
    color: var(--primary-gold) !important;
    border-radius: 16px !important;
    padding: 0.75rem 2.5rem !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    letter-spacing: 1px;
    background: rgba(15, 23, 42, 0.4) !important;
    backdrop-filter: blur(4px);
    transition: all 0.3s ease !important;
  }
  .btn-outline-gold:hover {
    background: var(--primary-gold) !important;
    color: #0f172a !important;
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.4) !important;
    transform: translateY(-2px);
  }

  .social-btn {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 16px !important;
    color: #ffffff !important;
    font-weight: 500 !important;
    padding: 0.75rem !important;
    transition: all 0.3s ease !important;
  }
  .social-btn:hover {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    transform: translateY(-2px);
  }

  .logo-wrapper {
    width: 220px;
    height: 220px;
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.15), rgba(139, 90, 43, 0.15));
    border: 2px solid rgba(212, 175, 55, 0.4);
    border-radius: 40px;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 12px 35px rgba(0,0,0,0.4);
    backdrop-filter: blur(10px);
    overflow: hidden;
  }
  .logo-img { width: 100%; height: 100%; object-fit: cover; }
  .overlay-text { color: #e2e8f0; font-weight: 500; line-height: 1.6; font-size: 0.95rem; }
  
  .alert { border-radius: 16px !important; }

  /* SweetAlert Social Popup styling mimicking logout modal */
  div:where(.swal2-container) div:where(.swal2-popup).swal-custom-popup {
    border-radius: 24px !important;
    box-shadow: 0 30px 60px rgba(0,0,0,0.2) !important;
    padding: 36px 32px !important;
  }
  .swal-btn-cancel {
      flex: 1; padding: 12px !important; border-radius: 14px !important; border: none !important;
      background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important; font-weight: 700 !important; color: #475569 !important; cursor: pointer; font-size: 14px !important;
      transition: all 0.3s ease !important; margin: 0 !important;
  }
  .swal-btn-cancel:hover {
      background: linear-gradient(135deg, #e2e8f0, #cbd5e1) !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important; color: #334155 !important;
  }
  .swal-btn-confirm {
      flex: 1; padding: 12px !important; border-radius: 14px !important;
      background: var(--swal-btn-color, linear-gradient(135deg, #ef4444, #b91c1c)) !important; border: none !important;
      color: #fff !important; font-weight: 700 !important; font-size: 14px !important;
      display: flex; align-items: center; justify-content: center; gap: 6px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
      transition: all 0.3s ease !important; margin: 0 !important;
  }
  .swal-btn-confirm:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.25) !important;
  }
  .swal-actions-container {
      display: flex !important; gap: 12px !important; width: 100% !important; margin-top: 24px !important;
  }

  /* Mobile Responsive Fixes */
  @media (max-width: 768px) {
    body {
      padding: 0 !important;
      margin: 0 !important;
    }
    .split-container {
      min-height: 100vh !important;
      border-radius: 0 !important;
      border: none !important;
      width: 100% !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: center !important;
    }
    .overlay-container {
      display: none !important;
    }
    .form-container {
      position: relative !important;
      width: 100% !important;
      height: auto !important;
      padding: 2rem 1.5rem !important;
      transform: none !important;
    }
    
    /* Default state: Login shows, Register hidden */
    .sign-in-container {
      display: flex !important;
      opacity: 1 !important;
      z-index: 10 !important;
      visibility: visible !important;
    }
    .sign-up-container {
      display: none !important;
      opacity: 0 !important;
      z-index: 1 !important;
      visibility: hidden !important;
    }

    /* Active state (right-panel-active): Register shows, Login hidden */
    .split-container.right-panel-active .sign-in-container {
      display: none !important;
      opacity: 0 !important;
      visibility: hidden !important;
    }
    .split-container.right-panel-active .sign-up-container {
      display: flex !important;
      opacity: 1 !important;
      z-index: 10 !important;
      visibility: visible !important;
    }
  }
</style>

<div class="split-container <?= $is_active_register ?>" id="split-container">
  
  <!-- Register Form -->
  <div class="form-container sign-up-container">
    <div class="mb-4">
      <h3 class="fw-bold mb-1">Buat Akun Baru</h3>
      <p class="small mb-0" style="color: #94a3b8;">Bergabung dengan komunitas eksklusif kami</p>
    </div>

    <?php if (session()->getFlashData("failed_register")): ?>
    <div class="alert py-2 px-3 small" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); color: #ff6b6b;">
      <i class="bi bi-exclamation-octagon me-2"></i><?= session()->getFlashData("failed_register") ?>
    </div>
    <?php endif; ?>

    <?= form_open("register", ["class" => "needs-validation"]) ?>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="custom-input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input type="text" name="username" class="form-control" placeholder="Pilih Username" required>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="custom-input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="custom-input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Buat Password" required>
          <span class="input-group-text toggle-password" style="cursor: pointer;"><i class="bi bi-eye-slash"></i></span>
        </div>
      </div>
      
      <button type="submit" class="btn btn-gold w-100 d-flex align-items-center justify-content-center gap-2 mb-4">
        <i class="bi bi-person-plus"></i> Buat Akun Eksklusif
      </button>
      
      <div class="d-flex justify-content-center gap-3">
        <a href="<?= base_url('auth/google') ?>" class="btn social-btn w-100 d-flex justify-content-center align-items-center gap-2">
          <i class="bi bi-google text-danger"></i> Google
        </a>
      </div>

      <!-- Mobile Toggle -->
      <div class="d-md-none text-center mt-4 pt-2">
        <span class="text-muted small">Sudah punya akun?</span> 
        <a href="#" class="text-warning text-decoration-none fw-bold" id="mobileSignIn">Masuk Sekarang</a>
      </div>
    <?= form_close() ?>
  </div>
  
  <!-- Login Form -->
  <div class="form-container sign-in-container">
    <div class="mb-4">
      <h3 class="fw-bold mb-1">Selamat Datang</h3>
      <p class="small mb-0" style="color: #94a3b8;">Masuk untuk mengakses koleksi Anda</p>
    </div>

    <?php if (session()->getFlashData("success")): ?>
    <div class="alert py-2 px-3 small" role="alert" style="background: rgba(25, 135, 84, 0.1); border: 1px solid rgba(25, 135, 84, 0.2); color: #20c997;">
      <i class="bi bi-check-circle me-2"></i><?= session()->getFlashData("success") ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashData("failed")): ?>
    <div class="alert py-2 px-3 small" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); color: #ff6b6b;">
      <i class="bi bi-exclamation-octagon me-2"></i><?= session()->getFlashData("failed") ?>
    </div>
    <?php endif; ?>

    <?= form_open("login", ["class" => "needs-validation"]) ?>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="custom-input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="custom-input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
          <span class="input-group-text toggle-password" style="cursor: pointer;"><i class="bi bi-eye-slash"></i></span>
        </div>
      </div>
      
      <button type="submit" class="btn btn-gold w-100 d-flex align-items-center justify-content-center gap-2 mb-4">
        <i class="bi bi-box-arrow-in-right"></i> Masuk ke Dashboard
      </button>
      
      <div class="d-flex align-items-center mb-4 mt-2">
        <div class="flex-grow-1" style="height: 1px; background-color: rgba(255,255,255,0.1);"></div>
        <span class="px-3 small" style="color: #64748b;">Atau masuk dengan</span>
        <div class="flex-grow-1" style="height: 1px; background-color: rgba(255,255,255,0.1);"></div>
      </div>

      <div class="d-flex justify-content-center gap-3">
        <a href="<?= base_url('auth/google') ?>" class="btn social-btn w-100 d-flex justify-content-center align-items-center gap-2">
          <i class="bi bi-google text-danger"></i> Google
        </a>
      </div>

      <!-- Mobile Toggle -->
      <div class="d-md-none text-center mt-4 pt-2">
        <span class="text-muted small">Belum punya akun?</span> 
        <a href="#" class="text-warning text-decoration-none fw-bold" id="mobileSignUp">Buat Akun Baru</a>
      </div>
    <?= form_close() ?>
  </div>
  
  <!-- Overlay Banner -->
  <div class="overlay-container">
    <div class="overlay">
      
      <!-- Banner for Register Panel -->
      <div class="overlay-panel overlay-left">
        <div class="logo-wrapper">
          <img src="<?= base_url('NiceAdmin/assets/img/logo51.png') ?>" alt="Logo" class="logo-img">
        </div>
        <h3 class="fw-bold mb-3">Sudah Punya Akun?</h3>
        <p class="overlay-text mb-4">
          Lanjutkan perjalanan Anda bersama kami. Kelola koleksi dan riwayat transaksi eksklusif Anda.
        </p>
        <button class="btn btn-outline-gold" id="signIn">Masuk / Login</button>
      </div>
      
      <!-- Banner for Login Panel -->
      <div class="overlay-panel overlay-right">
        <div class="logo-wrapper">
          <img src="<?= base_url('NiceAdmin/assets/img/logo51.png') ?>" alt="Logo" class="logo-img">
        </div>
        <h3 class="fw-bold mb-3">Sebul Watch Co.</h3>
        <p class="overlay-text mb-4">
          Temukan mahakarya penunjuk waktu. Bergabunglah untuk mengakses penawaran koleksi kelas dunia.
        </p>
        <button class="btn btn-outline-gold" id="signUp">Buat Akun Baru</button>
      </div>
      
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const signUpButton = document.getElementById('signUp');
  const signInButton = document.getElementById('signIn');
  const container = document.getElementById('split-container');
  
  const mobileSignUp = document.getElementById('mobileSignUp');
  const mobileSignIn = document.getElementById('mobileSignIn');

  const showRegister = (e) => {
    e.preventDefault();
    container.classList.add("right-panel-active");
    window.history.pushState({}, '', '<?= base_url('register') ?>');
  };

  const showLogin = (e) => {
    e.preventDefault();
    container.classList.remove("right-panel-active");
    window.history.pushState({}, '', '<?= base_url('login') ?>');
  };

  signUpButton.addEventListener('click', showRegister);
  signInButton.addEventListener('click', showLogin);
  if(mobileSignUp) mobileSignUp.addEventListener('click', showRegister);
  if(mobileSignIn) mobileSignIn.addEventListener('click', showLogin);

  // Password toggle functionality
  document.querySelectorAll('.toggle-password').forEach(item => {
    item.addEventListener('click', function() {
      const input = this.previousElementSibling;
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      } else {
        input.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      }
    });
  });

  // Animasi Loading & Form Popup untuk Social Login (Facebook Simulation)
  document.querySelectorAll('.social-simulated').forEach(btn => {
    btn.addEventListener('click', async function(e) {
      e.preventDefault();
      const href = this.getAttribute('href');
      let platform = href.includes('google') ? 'Google' : 'Facebook';
      let action = href.includes('register') ? 'Mendaftar' : 'Masuk';
      let platformColor = platform === 'Google' ? '#ea4335' : '#1877F2';
      let platformGradient = platform === 'Google' ? 'linear-gradient(135deg, #ea4335, #c5221f)' : 'linear-gradient(135deg, #1877F2, #0d4a96)';
      
      document.documentElement.style.setProperty('--swal-btn-color', platformGradient);

      const { value: formValues } = await Swal.fire({
        html: `
          <div style="font-family: 'Plus Jakarta Sans', sans-serif;">
            <div style="width:68px;height:68px;border-radius:50%;background:${platformColor}1A;display:grid;place-items:center;margin:0 auto 18px">
                <i class="bi bi-${platform.toLowerCase()}" style="font-size:2rem;color:${platformColor}"></i>
            </div>
            <h5 style="font-weight:800;color:#0f172a;margin-bottom:8px;font-size:1.2rem">Sign in with ${platform}</h5>
            <p style="color:#64748b;font-size:14px;margin-bottom:24px;line-height:1.6">Silakan masukkan kredensial akun ${platform} Anda untuk ${action.toLowerCase()}.</p>
            
            <input id="swal-input1" type="email" placeholder="Email (contoh: user@gmail.com)" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: #0f172a; font-size: 14px; margin-bottom: 12px; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='${platformColor}'; this.style.boxShadow='0 0 0 3px ${platformColor}33'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            
            <input id="swal-input2" type="password" placeholder="Password" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: #0f172a; font-size: 14px; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='${platformColor}'; this.style.boxShadow='0 0 0 3px ${platformColor}33'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
          </div>
        `,
        background: '#fff',
        width: '380px',
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
          popup: 'swal-custom-popup',
          confirmButton: 'swal-btn-confirm',
          cancelButton: 'swal-btn-cancel',
          actions: 'swal-actions-container'
        },
        preConfirm: () => {
          const email = document.getElementById('swal-input1').value;
          const password = document.getElementById('swal-input2').value;
          if (!email || !password) {
            Swal.showValidationMessage('Email dan Password wajib diisi!');
            return false;
          }
          return { email, password };
        }
      });

      if (formValues) {
        Swal.fire({
          title: `Otentikasi ${platform}...`,
          html: 'Memvalidasi kredensial Anda, mohon tunggu.',
          timer: 1500,
          timerProgressBar: true,
          background: '#1e293b',
          color: '#fff',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          willClose: () => {
            // Buat form tersembunyi untuk mengirim POST data ke backend
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = href;
            
            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'social_email';
            emailInput.value = formValues.email;
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'social_name';
            nameInput.value = formValues.email.split('@')[0]; 
            
            form.appendChild(emailInput);
            form.appendChild(nameInput);
            document.body.appendChild(form);
            form.submit();
          }
        });
      }
    });
  });
</script>
<?= $this->endSection() ?>
