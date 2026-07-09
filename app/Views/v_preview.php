<?= $this->extend("layout_clear") ?>
<?= $this->section("content") ?>

<style>
  /* ── Google Font ── */
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

  body {
    background-color: #f8f9fa !important;
  }
  .preview-wrap { 
    font-family: 'Plus Jakarta Sans', sans-serif; 
  }

  /* ── Navbar ── */
  .preview-navbar {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(139, 90, 43, 0.1);
    padding: 16px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
  }
  .preview-brand {
    font-size: 24px;
    font-weight: 800;
    color: #8B5A2B;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .preview-brand img {
    width: 40px;
  }
  .preview-brand:hover {
    color: #5C4033;
  }
  .btn-login-nav {
    background: linear-gradient(135deg, #d4af37, #b8860b);
    color: #fff !important;
    padding: 10px 24px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none;
    box-shadow: 0 4px 15px rgba(212,175,55,0.3);
    text-decoration: none;
    transition: all 0.3s ease;
  }
  .btn-login-nav:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212,175,55,0.4);
  }

  /* ── Hero Section ── */
  .hero-section {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: white;
    padding: 80px 5%;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .hero-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-image: url('https://images.unsplash.com/photo-1670177257750-9b47927f68eb?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxsdXh1cnklMjB3YXRjaCUyMGRhcmslMjBlbGVnYW50fGVufDF8fHx8MTc4MjY3MTM2M3ww&ixlib=rb-4.1.0&q=80&w=1080');
    background-size: cover;
    background-position: center;
    opacity: 0.2;
    z-index: 0;
  }
  .hero-content {
    position: relative;
    z-index: 1;
  }
  .hero-title {
    font-size: 42px;
    font-weight: 800;
    margin-bottom: 16px;
    background: linear-gradient(135deg, #fde047, #d4af37);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .hero-subtitle {
    font-size: 18px;
    color: #cbd5e1;
    max-width: 600px;
    margin: 0 auto 30px;
  }

  /* ── Product Cards ── */
  .catalog-container {
    padding: 60px 5%;
    max-width: 1400px;
    margin: 0 auto;
  }
  .catalog-title {
    font-size: 28px;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 30px;
    text-align: center;
  }
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 30px;
  }
  .product-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(139, 90, 43, 0.08);
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,.03);
    transition: transform .3s ease, box-shadow .3s ease;
    animation: fadeUp .5s ease both;
  }
  .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(139,90,43,.12); }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .card-img-wrap {
    height: 220px; background: linear-gradient(135deg, #fdfbf7 0%, #f4eee1 100%);
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
  }
  .card-img-wrap img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .4s ease;
  }
  .product-card:hover .card-img-wrap img { transform: scale(1.08); }
  .card-img-wrap i { font-size: 60px; color: #d4af37; }
  .img-fallback {
    width: 100%; height: 100%;
    align-items: center; justify-content: center;
  }


  .card-body-inner { padding: 24px; }
  .product-name {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 18px; font-weight: 700; color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 8px;
  }
  .product-price {
    font-size: 24px; font-weight: 800; 
    background: linear-gradient(135deg, #d4af37, #b8860b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 2px 8px rgba(212,175,55,0.15);
    margin-bottom: 16px;
    letter-spacing: -0.5px;
  }
  
  .btn-preview-buy {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 13px;
    transition: all 0.3s;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    background: rgba(37, 99, 235, 0.05);
    color: #2563eb;
    border: 1px solid rgba(37, 99, 235, 0.2);
    text-decoration: none;
  }
  .btn-preview-buy:hover {
    background: #2563eb;
    color: white;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
  }

  /* ── Empty state ── */
  .empty-state { text-align: center; padding: 80px 20px; color: #94a3b8; }
  .empty-state i { font-size: 64px; margin-bottom: 16px; display: block; color: #cbd5e1; }
  .empty-state h3 { font-weight: 700; color: #475569; }
</style>

<div class="preview-wrap">

  <!-- Navbar -->
  <nav class="preview-navbar">
    <a href="<?= base_url() ?>" class="preview-brand">
      <img src="<?= base_url('NiceAdmin/assets/img/logo51.png') ?>" alt="Logo">
      Sebul Watch Co.
    </a>
    <a href="<?= base_url('login') ?>" class="btn-login-nav">
      <i class="bi bi-box-arrow-in-right me-1"></i> Login / Daftar
    </a>
  </nav>

  <!-- Hero Section -->
  <header class="hero-section">
    <div class="hero-content">
      <h1 class="hero-title">Elegansi di Setiap Detik</h1>
      <p class="hero-subtitle">Jelajahi koleksi jam tangan premium kami. Didesain untuk memberikan kesan mewah, eksklusif, dan profesional untuk menunjang penampilan Anda.</p>
      <a href="#katalog" class="btn btn-login-nav px-5 py-3 mt-2" style="font-size: 15px;">Lihat Koleksi</a>
    </div>
  </header>

  <!-- Produk Terlaris Section -->
  <?php if (!empty($top_products)): ?>
  <div class="catalog-container pb-0" style="padding-top: 40px;">
    <div style="text-align: center; margin-bottom: 40px;">
      <h2 class="catalog-title" style="margin-bottom: 8px;">🔥 Produk Terlaris Bulan Ini</h2>
      <p style="color: #64748b; font-size: 16px; max-width: 600px; margin: 0 auto;">Pilihan favorit pelanggan kami. Jam tangan dengan penjualan terbanyak yang wajib Anda miliki!</p>
    </div>
    <div class="product-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px;">
      <?php foreach ($top_products as $index => $p): ?>
        <div class="product-card" style="animation-delay: <?= $index * 0.1 ?>s; border: 2px solid rgba(212, 175, 55, 0.4); transform: scale(1.02);">
          <div class="card-img-wrap" style="height: 260px;">
            <!-- Top rank badge -->
            <div style="position: absolute; top: 16px; left: 16px; background: linear-gradient(135deg, #d4af37, #b8860b); color: white; padding: 6px 14px; border-radius: 20px; font-weight: 800; font-size: 12px; z-index: 10; box-shadow: 0 4px 10px rgba(212,175,55,0.4);">
              #<?= $index + 1 ?> Terlaris
            </div>
            <?php if (!empty($p["foto"])): ?>
              <img src="<?= base_url("NiceAdmin/assets/img/" . esc($p["foto"])) ?>"
                   alt="<?= esc($p["nama"]) ?>"
                   onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
              <div class="img-fallback" style="display:none"><i class="bi bi-watch"></i></div>
            <?php else: ?>
              <i class="bi bi-watch"></i>
            <?php endif; ?>
          </div>
          <div class="card-body-inner">
            <div class="product-name" title="<?= esc($p["nama"]) ?>" style="font-size: 20px;"><?= esc($p["nama"]) ?></div>
            <div class="product-price" style="font-size: 26px;">Rp <?= number_format($p["harga"], 0, ",", ".") ?></div>
            <div style="margin-bottom: 20px; color: #475569; font-size: 14px; font-weight: 600;">
              <i class="bi bi-bag-check-fill text-success"></i> Terjual <?= $p['terjual'] ?> pcs
            </div>
            <?php if ($p['stok'] > 0): ?>
              <a href="<?= base_url('login') ?>" class="btn-preview-buy">
                <i class="bi bi-cart"></i> Login untuk Membeli
              </a>
            <?php else: ?>
              <button class="btn btn-secondary w-100" style="padding: 12px; border-radius: 12px; font-weight: 700; text-transform: uppercase; font-size: 13px;" disabled>Stok Habis</button>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Catalog Section -->
  <div class="catalog-container" id="katalog">
    <h2 class="catalog-title">Semua Katalog Produk</h2>
    
    <?php if (empty($products)): ?>
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <h3>Belum ada produk tersedia.</h3>
        <p>Silakan kembali lagi nanti untuk melihat koleksi terbaru kami.</p>
      </div>
    <?php else: ?>
      <div class="product-grid">
        <?php foreach ($products as $index => $p): ?>
          <div class="product-card" style="animation-delay: <?= $index * 0.1 ?>s">
            <div class="card-img-wrap">
              <?php if (!empty($p["foto"])): ?>
                <img src="<?= base_url("NiceAdmin/assets/img/" . esc($p["foto"])) ?>"
                     alt="<?= esc($p["nama"]) ?>"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="img-fallback" style="display:none"><i class="bi bi-watch"></i></div>
              <?php else: ?>
                <i class="bi bi-watch"></i>
              <?php endif; ?>
            </div>
            
            <div class="card-body-inner">
              <div class="product-name" title="<?= esc($p["nama"]) ?>"><?= esc($p["nama"]) ?></div>
              <div class="product-price">Rp <?= number_format($p["harga"], 0, ",", ".") ?></div>
              
              <?php if ($p['jumlah'] > 0): ?>
                <a href="<?= base_url('login') ?>" class="btn-preview-buy">
                  <i class="bi bi-cart"></i> Login untuk Membeli
                </a>
              <?php else: ?>
                <button class="btn btn-secondary w-100" style="padding: 12px; border-radius: 12px; font-weight: 700; text-transform: uppercase; font-size: 13px;" disabled>Stok Habis</button>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  
  <!-- Simple Footer -->
  <footer style="text-align: center; padding: 40px 20px; background: #ffffff; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 14px;">
    &copy; <?= date('Y') ?> <strong>Sebul Watch Co.</strong> Hak Cipta Dilindungi.<br>
    <small>Silakan login atau daftar untuk mulai berbelanja.</small>
  </footer>

</div>

<?= $this->endSection() ?>
