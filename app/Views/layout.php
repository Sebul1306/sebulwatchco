<?php
$hlm = "Home";
if (uri_string() != "") {
    $hlm = ucwords(str_replace(['/', '-'], ' ', uri_string()));
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

  <link href="<?= base_url() ?>NiceAdmin/assets/img/logo51.png" rel="icon">
  <link href="<?= base_url() ?>NiceAdmin/assets/img/logo51.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <style>
    /* Custom Luxury Cursor */
    body {
        cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="%23d4af37" stroke="%235C4033" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 3 10 21 14 14 21 10 3 3"></polygon></svg>'), auto;
    }
    a, button, .btn, .nav-link, select, input, .oc-flat-link, .oc-group-btn {
        cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="%23ffffff" stroke="%23d4af37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 3 10 21 14 14 21 10 3 3"></polygon><path d="M14 14l6 6"></path></svg>') 12 12, pointer !important;
    }

    /* Override Nav Tabs (Sebul Watch Co. Branding) */
    .nav-tabs-bordered .nav-link.active {
      color: #8B5A2B !important;
      border-bottom-color: #8B5A2B !important;
    }
    .nav-tabs-bordered .nav-link:hover {
      color: #5C4033 !important;
      border-bottom-color: #5C4033 !important;
    }
    .nav-tabs .nav-link {
      color: #64748b;
    }
    .nav-tabs .nav-link:hover {
      color: #8B5A2B;
    }

    /* Premium Varied Colors (Sebul Watch Co.) */
    /* Primary (Sapphire Blue) */
    .btn-primary, .bg-primary, .badge.bg-primary {
      background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;
      border: none !important;
      color: #fff !important;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.25) !important;
    }
    .btn-primary:hover {
      box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4) !important;
      transform: translateY(-2px);
    }
    .text-primary { color: #2563eb !important; }

    /* Success (Emerald Green) - Replaces the old Lime Green */
    .btn-success, .bg-success, .badge.bg-success {
      background: linear-gradient(135deg, #065f46 0%, #059669 100%) !important;
      border: none !important;
      color: #fff !important;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.25) !important;
    }
    .btn-success:hover {
      box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4) !important;
      transform: translateY(-2px);
    }
    .text-success { color: #059669 !important; }

    /* Danger (Ruby Red) */
    .btn-danger, .bg-danger, .badge.bg-danger {
      background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%) !important;
      border: none !important;
      color: #fff !important;
      box-shadow: 0 4px 15px rgba(220, 38, 38, 0.25) !important;
    }
    .btn-danger:hover {
      box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4) !important;
      transform: translateY(-2px);
    }
    
    /* Warning (Topaz Gold) */
    .btn-warning, .bg-warning, .badge.bg-warning {
      background: linear-gradient(135deg, #ca8a04 0%, #eab308 100%) !important;
      border: none !important;
      color: #fff !important;
      box-shadow: 0 4px 15px rgba(234, 179, 8, 0.25) !important;
    }

    /* Premium Outline Buttons */
    .btn-outline-primary {
      color: #2563eb !important;
      border: 1px solid #2563eb !important;
      background: rgba(37, 99, 235, 0.05) !important;
    }
    .btn-outline-primary:hover {
      background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;
      color: #fff !important;
      border-color: transparent !important;
      box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3) !important;
      transform: translateY(-2px);
    }
    
    .btn-outline-success {
      color: #059669 !important;
      border: 1px solid #059669 !important;
      background: rgba(5, 150, 105, 0.05) !important;
    }
    .btn-outline-success:hover {
      background: linear-gradient(135deg, #065f46 0%, #059669 100%) !important;
      color: #fff !important;
      border-color: transparent !important;
      box-shadow: 0 5px 15px rgba(5, 150, 105, 0.3) !important;
      transform: translateY(-2px);
    }
    
    .btn-outline-danger {
      color: #dc2626 !important;
      border: 1px solid #dc2626 !important;
      background: rgba(220, 38, 38, 0.05) !important;
    }
    .btn-outline-danger:hover {
      background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%) !important;
      color: #fff !important;
      border-color: transparent !important;
      box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3) !important;
      transform: translateY(-2px);
    }

    /* Transition for all these buttons */
    .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-outline-primary, .btn-outline-success, .btn-outline-danger {
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    }

    /* Footer & Back to top Overrides (Sebul Watch Co. Branding) */
    .footer .credits a {
      color: #8B5A2B !important;
      text-decoration: none;
    }
    .footer .credits a:hover {
      color: #5C4033 !important;
    }
    .back-to-top {
      background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%) !important;
      box-shadow: 0 5px 15px rgba(139,90,43, 0.3) !important;
    }
    .back-to-top:hover {
      background: linear-gradient(135deg, #5C4033 0%, #3E2723 100%) !important;
      transform: translateY(-3px);
    }

    /* ── Layout Wrapper (Cuanki style) ── */
    .app-wrapper {
        display: flex !important;
        flex-direction: row !important;
        gap: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        background: #f1f5f9;
        min-height: 100vh;
    }
    .main-content {
        margin-left: 0 !important;
        width: auto !important;
        flex: 1 1 0% !important;
        min-width: 0 !important;
        display: flex;
        flex-direction: column;
    }
    .topbar {
        background: #ffffff;
        border-radius: 20px;
        margin: 12px 24px 0 16px;
        top: 12px;
        position: sticky;
        z-index: 1000;
        box-shadow: 0 6px 24px rgba(15,23,42,0.05);
        border: 2px solid rgba(212, 175, 55, 0.3);
        padding: 12px 20px;
    }
    
    /* Apply outline to all main content cards for consistency */
    .card {
        border: 2px solid rgba(212, 175, 55, 0.3) !important;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,.04);
    }
    
    .content-area {
        padding: 20px 24px 24px 16px !important;
        flex: 1;
        margin-top: 0 !important; /* override NiceAdmin */
        margin-left: 0 !important; /* override NiceAdmin */
    }
    .main {
        margin-top: 0 !important;
        margin-left: 0 !important;
        padding: 20px 24px 24px 16px !important;
        min-height: calc(100vh - 120px);
    }
    @media (max-width: 991px) {
        .topbar { margin: 8px 12px 0 12px; border-radius: 16px; padding: 12px 14px; }
        .content-area, .main { padding: 14px 12px 16px 12px !important; }
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
<div class="app-wrapper">
  <?= $this->include("components/sidebar") ?>

  <div class="main-content" id="mainContent">
    <?= $this->include("components/header") ?>

    <main id="main" class="main content-area">
      <?php if($this->renderSection("page_action")): ?>
      <div class="pagetitle d-flex justify-content-end align-items-center mb-3">
        <div>
          <?= $this->renderSection("page_action") ?>
        </div>
      </div>
      <?php endif; ?>

      <section class="section">
        <?= $this->renderSection("content") ?>
      </section>
    </main>

    <?= $this->include("components/footer") ?>
  </div>
</div>

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
  
  <!-- Custom Modal Logout dari Cuanki -->
  <style>
    @keyframes fadeInUpModal {
        from { opacity: 0; transform: translate3d(0, 30px, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }
    .btn-logout-cancel {
        flex: 1; padding: 12px; border-radius: 14px; border: none;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0); font-weight: 700; color: #475569; cursor: pointer; font-size: 14px;
        transition: all 0.3s ease;
    }
    .btn-logout-cancel:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); color: #334155;
    }
    .btn-logout-confirm {
        flex: 1; padding: 12px; border-radius: 14px;
        background: linear-gradient(135deg, #ef4444, #b91c1c); border: none;
        color: #fff; font-weight: 700; font-size: 14px; text-decoration: none !important;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        box-shadow: 0 8px 20px rgba(185,28,28,0.3);
        transition: all 0.3s ease;
    }
    .btn-logout-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(185,28,28,0.4);
        color: #fff;
    }
  </style>
  <div id="modalLogout"
       style="display:none;position:fixed;inset:0;z-index:9999;
              background:rgba(15,23,42,.55);backdrop-filter:blur(4px);
              align-items:center;justify-content:center"
       onclick="if(event.target===this) this.style.display='none'">
      <div style="background:#fff;border-radius:24px;padding:36px 32px;
                  max-width:380px;width:90%;text-align:center;
                  box-shadow:0 30px 60px rgba(0,0,0,.2);animation:fadeInUpModal .25s ease">
          <div style="width:68px;height:68px;border-radius:50%;background:rgba(220,38,38,.1);
                      display:grid;place-items:center;margin:0 auto 18px">
              <i class="bi bi-box-arrow-right" style="font-size:2rem;color:#dc2626"></i>
          </div>
          <h5 style="font-weight:800;color:#0f172a;margin-bottom:8px;font-size:1.2rem">Yakin ingin keluar?</h5>
          <p style="color:#64748b;font-size:14px;margin-bottom:28px;line-height:1.6">
              Sesi Anda akan diakhiri dan diarahkan<br>ke halaman login.
          </p>
          <div class="d-flex gap-3">
              <button onclick="document.getElementById('modalLogout').style.display='none'" class="btn-logout-cancel">
                  <i class="bi bi-x-circle me-1"></i>Batal
              </button>
              <a href="<?= base_url('logout') ?>" id="confirmLogoutBtn" class="btn-logout-confirm">
                  <i class="bi bi-box-arrow-right"></i>Keluar
              </a>
          </div>
      </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const logoutLinks = document.querySelectorAll('a[href*="logout"]');
      logoutLinks.forEach(link => {
        // Skip the confirm button itself
        if (link.id === 'confirmLogoutBtn') return;
        
        link.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('modalLogout').style.display = 'flex';
          // Update the confirm button href just in case
          document.getElementById('confirmLogoutBtn').href = this.getAttribute('href');
        });
      });
      
      // Close on Escape key
      document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
              document.getElementById('modalLogout').style.display = 'none';
          }
      });
    });
  </script>

  <!-- Floating Chat Widget -->
  <?= $this->include("components/chat_widget") ?>

  <!-- Custom Premium Cursor -->
  <?= $this->include("components/custom_cursor") ?>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const quickFilter = document.getElementById('quickFilter');
      const filterTypeHidden = document.getElementById('filter_type_hidden');
      const btnSubmit = document.getElementById('btnSubmitFilter');
      if (!quickFilter) return;
      
      if (filterTypeHidden && filterTypeHidden.value) {
          quickFilter.value = filterTypeHidden.value;
      }
      
      function updateUI() {
          const val = quickFilter.value;
          document.querySelectorAll('.custom-bulan-ui').forEach(el => el.style.display = (val === 'custom_bulan') ? 'block' : 'none');
          document.querySelectorAll('.custom-tanggal-ui').forEach(el => el.style.display = (val === 'custom') ? 'block' : 'none');
      }
      
      quickFilter.addEventListener('change', updateUI);
      updateUI();
      
      if(btnSubmit) {
          btnSubmit.addEventListener('click', function() {
              const val = quickFilter.value;
              const awal = document.getElementById('tanggal_awal');
              const akhir = document.getElementById('tanggal_akhir');
              
              if(filterTypeHidden) filterTypeHidden.value = val;
              
              const formatDate = (date) => {
                  const d = new Date(date);
                  let month = '' + (d.getMonth() + 1);
                  let day = '' + d.getDate();
                  const year = d.getFullYear();
                  if (month.length < 2) month = '0' + month;
                  if (day.length < 2) day = '0' + day;
                  return [year, month, day].join('-');
              };

              let start = new Date();
              let end = new Date();
              const today = new Date();

              if (val === 'custom') {
                  awal.value = document.getElementById('ui_tanggal_awal').value;
                  akhir.value = document.getElementById('ui_tanggal_akhir').value;
              } else if (val === 'custom_bulan') {
                  const b = document.getElementById('bulanSelect').value;
                  const t = document.getElementById('tahunSelect').value;
                  awal.value = formatDate(new Date(t, parseInt(b) - 1, 1));
                  akhir.value = formatDate(new Date(t, parseInt(b), 0));
              } else {
                  switch(val) {
                      case 'today':
                          break;
                      case 'this_week':
                          const firstDay = today.getDate() - today.getDay() + (today.getDay() === 0 ? -6 : 1);
                          start = new Date(today.setDate(firstDay));
                          end = new Date();
                          break;
                      case 'this_month':
                          start = new Date(today.getFullYear(), today.getMonth(), 1);
                          end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                          break;
                      case '3_months':
                          start = new Date(today.getFullYear(), today.getMonth() - 3, today.getDate());
                          end = new Date();
                          break;
                      case '6_months':
                          start = new Date(today.getFullYear(), today.getMonth() - 6, today.getDate());
                          end = new Date();
                          break;
                      case 'this_year':
                          start = new Date(today.getFullYear(), 0, 1);
                          end = new Date(today.getFullYear(), 11, 31);
                          break;
                      case 'all_time':
                          start = new Date(2020, 0, 1);
                          end = new Date();
                          break;
                  }
                  if(val !== '') {
                      awal.value = formatDate(start);
                      akhir.value = formatDate(end);
                  }
              }
              
              if (val === 'custom_bulan') {
                  let hb = document.createElement('input'); hb.type='hidden'; hb.name='bulan'; hb.value=document.getElementById('bulanSelect').value;
                  let ht = document.createElement('input'); ht.type='hidden'; ht.name='tahun'; ht.value=document.getElementById('tahunSelect').value;
                  quickFilter.closest('form').appendChild(hb);
                  quickFilter.closest('form').appendChild(ht);
              }
              
              quickFilter.closest('form').submit();
          });
      }
  });
  </script>
</body>

</html>
