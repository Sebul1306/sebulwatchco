<?= $this->extend("layout") ?>
<?= $this->section("content") ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

  .home-wrap { font-family: 'Plus Jakarta Sans', sans-serif; }
  
  /* Hero Banner Premium - Super Depth */
  .hero-banner {
    background: linear-gradient(135deg, #0f172a 0%, #020617 100%);
    border-radius: 24px;
    padding: 32px 48px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15), inset 0 2px 3px rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
  }
  .hero-glow-1 {
    position: absolute;
    top: -30%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(212,175,55,0.25) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    z-index: 1;
    filter: blur(50px);
    animation: floatGlow 10s ease-in-out infinite alternate;
  }
  .hero-glow-2 {
    position: absolute;
    bottom: -40%;
    right: -10%;
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, rgba(139,90,43,0.35) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    z-index: 1;
    filter: blur(60px);
    animation: floatGlow 12s ease-in-out infinite alternate-reverse;
  }
  @keyframes floatGlow {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(20px, 30px) scale(1.1); }
  }
  
  .hero-content {
    position: relative;
    z-index: 2;
    flex: 1;
    max-width: 60%;
  }
  .hero-visual {
    flex: 1;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    position: relative;
    z-index: 2;
  }
  .hero-visual img {
    width: 220px;
    height: auto;
    object-fit: contain;
    mix-blend-mode: lighten;
    -webkit-mask-image: radial-gradient(circle at center, black 55%, transparent 75%);
    mask-image: radial-gradient(circle at center, black 55%, transparent 75%);
    transform-origin: bottom center;
    animation: waveHello 3.5s ease-in-out infinite;
  }
  @keyframes waveHello {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    10% { transform: translateY(-3px) rotate(8deg); }
    20% { transform: translateY(-3px) rotate(-6deg); }
    30% { transform: translateY(-3px) rotate(8deg); }
    40% { transform: translateY(-3px) rotate(-6deg); }
    50% { transform: translateY(0) rotate(0deg); }
  }
  .hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #d4af37 0%, #8b5a2b 100%);
    color: white !important;
    padding: 12px 28px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.95rem;
    margin-top: 16px;
    text-decoration: none;
    box-shadow: 0 8px 20px rgba(212,175,55,0.25);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  }
  .hero-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(212,175,55,0.4);
  }
  /* Menggunakan div agar tidak ditimpa oleh h1-h6 style dari layout */
  .hero-title {
    color: #ffffff !important;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
    line-height: 1.2;
    text-shadow: 0 8px 16px rgba(0,0,0,0.4);
  }
  .hero-title span {
    background: linear-gradient(135deg, #fde047 0%, #d4af37 50%, #b45309 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 3px 8px rgba(212,175,55,0.3));
    white-space: nowrap;
  }
  .hero-subtitle {
    color: #cbd5e1;
    font-size: 0.95rem;
    max-width: 600px;
    line-height: 1.6;
    margin: 0;
    font-weight: 500;
  }

  /* Table Container - Transparan agar row mengambang */
  .trx-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
    margin-bottom: 24px;
    padding: 0 12px;
  }
  .trx-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 22px; 
    color: #1e293b; 
    font-weight: 800;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .trx-icon {
    width: 44px; height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, rgba(139,90,43,0.15) 0%, rgba(139,90,43,0.05) 100%);
    color: #8B5A2B;
    display: grid; place-items: center;
    font-size: 20px;
    box-shadow: 0 4px 12px rgba(139,90,43,0.1);
  }
  
  /* Floating Rows Table Design */
  .table-responsive { padding: 0 4px 32px 4px; overflow-x: auto; }
  .table-home {
    border-collapse: separate !important;
    border-spacing: 0 16px !important; 
    margin-top: -16px;
    width: 100%;
  }
  .table-home th {
    border: none !important; 
    background: transparent !important;
    color: #8B5A2B !important; 
    font-size: 13px;
    text-transform: uppercase; 
    letter-spacing: 1px; 
    font-weight: 800;
    padding: 0 24px 12px 24px;
  }
  
  /* Row Styling - The 3D Card Look */
  .table-home tr.trx-row {
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(139,90,43,0.06), 0 1px 3px rgba(0,0,0,0.02);
    border-radius: 20px;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  }
  .table-home tr.trx-row:hover {
    transform: translateY(-5px) scale(1.005);
    box-shadow: 0 20px 40px rgba(139,90,43,0.12), 0 4px 10px rgba(139,90,43,0.05);
  }
  
  .table-home td { 
    border: none !important; 
    padding: 18px 24px; 
    vertical-align: middle; 
    font-size: 14.5px; 
    color: #1e293b;
    font-weight: 600;
  }
  .table-home td:first-child { border-top-left-radius: 20px; border-bottom-left-radius: 20px; }
  .table-home td:last-child { border-top-right-radius: 20px; border-bottom-right-radius: 20px; }
  
  /* Badges & Buttons */
  .price-badge {
    font-weight: 800;
    color: #fff;
    background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
    padding: 8px 16px;
    border-radius: 12px;
    display: inline-block;
    box-shadow: 0 6px 15px rgba(139,90,43,0.25);
    letter-spacing: 0.5px;
  }
  .ongkir-text {
    font-weight: 700;
    color: #8B5A2B;
    background: rgba(139,90,43,0.06);
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid rgba(139,90,43,0.15);
  }
  
  .btn-detail {
    background: rgba(139,90,43,0.06); 
    color: #8B5A2B; 
    border: 1px solid rgba(139,90,43,0.15);
    padding: 10px 22px; 
    border-radius: 12px; 
    font-weight: 800; 
    font-size: 13px;
    transition: all .3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .btn-detail:hover { 
    background: linear-gradient(135deg, #d4af37, #8b5a2b);
    color: #fff;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(139,90,43,0.25);
  }
  
  .empty-trx { text-align: center; padding: 80px 20px; background: #fff; border-radius: 24px; box-shadow: 0 10px 30px rgba(139,90,43,0.05); }
  .empty-trx i { font-size: 72px; color: #e2e8f0; margin-bottom: 20px; display: block; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.05)); }
  .empty-trx h5 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: #1e293b; margin-bottom: 12px; font-size: 24px; }
  .empty-trx p { color: #64748b; margin-bottom: 30px; font-size: 15px; }
  .btn-belanja {
    background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
    color: #fff; border: none; font-weight: 800;
    padding: 14px 32px; border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: inline-block;
    box-shadow: 0 10px 25px rgba(139,90,43,0.3);
  }
  .btn-belanja:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(139,90,43,0.4);
    color: #fff;
  }
</style>

<div class="home-wrap">
  
  <!-- Hero Section (Super Depth) -->
  <div class="hero-banner">
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>
    <div class="hero-content">
      <div class="hero-title">Selamat Datang, <span>Sebul Watch Co.</span></div>
      <p class="hero-subtitle">Platform e-commerce eksklusif untuk koleksi jam tangan premium. Temukan kualitas dan keanggunan dalam setiap detik perjalanan Anda.</p>
      <a href="<?= base_url('produk') ?>" class="hero-btn">
        <i class="bi bi-compass"></i> Jelajahi Koleksi
      </a>
    </div>
    <div class="hero-visual d-none d-lg-flex">
      <img src="<?= base_url('NiceAdmin/assets/img/3d_watch_seller_waving.png') ?>" alt="Watch Seller Waving">

    </div>
  </div>

  <div class="trx-header">
    <div class="trx-title">
      <div class="trx-icon"><i class="bi bi-clock-history"></i></div>
      <?= session()->get('role') == 'admin' ? 'Semua Transaksi Terbaru' : 'Riwayat Pesanan Saya' ?>
    </div>
  </div>
  
  <div class="table-responsive">
    <table class="table table-home">
      <thead>
        <tr>
          <th scope="col" style="width: 60px; text-align: center !important;">#</th>
          <?php if (session()->get('role') == 'admin'): ?>
          <th scope="col">Username</th>
          <?php endif; ?>
          <th scope="col">Alamat</th>
          <th scope="col">Ongkir</th>
          <th scope="col">Total Harga</th>
          <th scope="col">Tanggal</th>
          <th scope="col" class="text-center" style="text-align: center !important;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($transactions)): ?>
          <?php $i=1; foreach($transactions as $trx): ?>
          <tr class="trx-row">
            <td class="fw-bolder fs-5" style="color: #94a3b8 !important;"><?= sprintf('%02d', $i++) ?></td>
            <?php if (session()->get('role') == 'admin'): ?>
            <td class="fw-bolder text-dark fs-6"><?= esc($trx['username']) ?></td>
            <?php endif; ?>
            <td style="max-width: 280px; line-height: 1.5; font-size: 13px;"><?= nl2br(esc($trx['alamat'])) ?></td>
            <td><span class="ongkir-text">IDR <?= number_format($trx['ongkir'], 0, ',', '.') ?></span></td>
            <td><span class="price-badge">IDR <?= number_format($trx['total_harga'], 0, ',', '.') ?></span></td>
            <td class="text-muted fw-bold"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></td>
            <td class="text-center">
              <button class="btn-detail" data-bs-toggle="modal" data-bs-target="#detailModal<?= $trx['id'] ?>">
                <i class="bi bi-arrow-right-circle"></i> Lihat
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="<?= session()->get('role') == 'admin' ? '7' : '6' ?>" style="background: transparent; box-shadow: none;">
              <div class="empty-trx">
                <i class="bi bi-bag-x"></i>
                <h5>Belum Ada Transaksi</h5>
                <p>Catatan transaksi pesanan akan otomatis muncul di sini begitu ada aktivitas.</p>
                <?php if (session()->get('role') != 'admin'): ?>
                <a href="<?= base_url('produk') ?>" class="btn-belanja text-decoration-none"><i class="bi bi-cart3 me-2"></i>Belanja Sekarang</a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if(!empty($transactions)): ?>
  <?php foreach($transactions as $trx): ?>
    <div class="modal fade" id="detailModal<?= $trx['id'] ?>" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none; box-shadow: 0 25px 60px rgba(0,0,0,0.2);">
          <div class="modal-header" style="background: rgba(139,90,43,0.03); border-bottom: 1px solid rgba(139,90,43,0.08); padding: 24px 32px;">
            <h5 class="modal-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: #1e293b; font-size: 22px;">
              <i class="bi bi-receipt me-2" style="color: #8B5A2B;"></i>Detail Transaksi <span style="color: #8B5A2B;">#<?= sprintf('%04d', $trx['id']) ?></span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: rgba(0,0,0,0.05); border-radius: 50%; padding: 12px;"></button>
          </div>
          <div class="modal-body" style="padding: 32px;">
            <div class="row mb-5 bg-white p-4 rounded-4 mx-0" style="border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.02);">
              <div class="col-md-6 mb-3 mb-md-0">
                <small class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 1.5px;">Pelanggan</small>
                <div class="fw-bolder text-dark mb-3 fs-5"><?= esc($trx['username']) ?></div>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 1.5px;">Alamat Pengiriman</small>
                <div class="text-dark fw-semibold"><?= esc($trx['alamat']) ?></div>
              </div>
              <div class="col-md-6 text-md-end">
                <small class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 1.5px;">Tanggal Transaksi</small>
                <div class="text-dark mb-3 fw-bolder fs-5"><?= date('d F Y, H:i', strtotime($trx['created_at'])) ?></div>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 1.5px;">Status Pesanan</small><br>
                <span class="badge" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 8px 16px; border-radius: 10px; font-weight: 800; font-size: 12px; margin-top: 8px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">BERHASIL</span>
              </div>
            </div>
            
            <h6 class="fw-bolder text-dark mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 18px;">Daftar Produk</h6>
            <table class="table table-borderless align-middle mb-0">
              <thead>
                <tr style="border-bottom: 2px solid #e2e8f0;">
                  <th class="text-muted text-uppercase" style="font-size: 11px; font-weight: 800; padding-bottom: 16px; letter-spacing: 1px;">Produk</th>
                  <th class="text-center text-muted text-uppercase" style="font-size: 11px; font-weight: 800; padding-bottom: 16px; letter-spacing: 1px;">Qty</th>
                  <th class="text-end text-muted text-uppercase" style="font-size: 11px; font-weight: 800; padding-bottom: 16px; letter-spacing: 1px;">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php if(isset($details[$trx['id']]) && !empty($details[$trx['id']])): ?>
                  <?php foreach($details[$trx['id']] as $d): ?>
                  <tr style="border-bottom: 1px dashed #e2e8f0;">
                    <td style="padding: 20px 0;">
                      <div class="d-flex align-items-center gap-4">
                        <?php if(!empty($d['foto'])): ?>
                          <img src="<?= base_url('NiceAdmin/assets/img/' . $d['foto']) ?>" width="64" height="64" class="rounded-4 object-fit-cover shadow-sm" style="border: 1px solid rgba(0,0,0,0.05);">
                        <?php else: ?>
                          <div class="bg-light rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width:64px; height:64px; border: 1px solid rgba(0,0,0,0.05);"><i class="bi bi-watch text-muted fs-4"></i></div>
                        <?php endif; ?>
                        <span class="fw-bolder text-dark fs-6"><?= esc($d['nama'] ?? 'Produk Dihapus') ?></span>
                      </div>
                    </td>
                    <td class="text-center fw-bolder text-muted fs-6">x<?= $d['jumlah'] ?></td>
                    <td class="text-end fw-bolder text-dark fs-6">IDR <?= number_format($d['subtotal_harga'], 0, ',', '.') ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="3" class="text-center text-muted py-5 fw-bold bg-light rounded-4">Detail tidak ditemukan</td></tr>
                <?php endif; ?>
              </tbody>
              <tfoot style="border-top: 2px solid #e2e8f0;">
                <tr>
                  <td colspan="2" class="text-end text-muted pt-4 pb-2 fw-bold" style="font-size: 14px;">Ongkos Kirim</td>
                  <td class="text-end fw-bolder text-dark pt-4 pb-2 fs-6">IDR <?= number_format($trx['ongkir'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td colspan="2" class="text-end fw-bolder fs-5 text-dark pb-3 pt-2">Total Pembayaran</td>
                  <td class="text-end pb-3 pt-2"><span class="price-badge fs-5">IDR <?= number_format($trx['total_harga'], 0, ',', '.') ?></span></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer" style="border-top: none; background: rgba(139,90,43,0.02); padding: 20px 32px;">
            <button type="button" class="btn btn-secondary fw-bolder px-5 py-2" style="border-radius: 14px; background: #e2e8f0; color: #475569; border: none; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.background='#cbd5e1'" onmouseout="this.style.background='#e2e8f0'" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
