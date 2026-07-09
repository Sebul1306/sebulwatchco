<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
  $totalTransaksi = count($buy);
  $totalBelanja = 0;
  $totalProduk = 0;
  $selesaiCount = 0;
  foreach ($buy as $b) {
      $totalBelanja += $b['total_harga'];
      if ($b['status'] == 3) $selesaiCount++;
  }
  foreach ($product as $items) {
      foreach ($items as $p) {
          $totalProduk += $p['jumlah'];
      }
  }
  $initial = strtoupper(substr($username, 0, 1));
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<style>
/* Profile Banner */
.profile-banner {
  background: linear-gradient(135deg, #1e150b, #0a0805);
  border-radius: 16px;
  padding: 40px 30px 70px;
  margin: 0;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(139,90,43,0.15);
}
.profile-banner::before {
  content: '';
  position: absolute;
  width: 300px; height: 300px;
  background: rgba(255,255,255,0.05);
  border-radius: 50%;
  top: -100px; right: -50px;
}
.profile-banner::after {
  content: '';
  position: absolute;
  width: 200px; height: 200px;
  background: rgba(255,255,255,0.04);
  border-radius: 50%;
  bottom: -80px; left: 10%;
}
.profile-banner h3 {
  color: #fceea7 !important;
  font-weight: 700;
  font-size: 1.4rem;
  margin: 0;
  position: relative;
  z-index: 1;
}
.profile-banner p {
  color: rgba(255,255,255,0.75);
  margin: 4px 0 0;
  font-size: 0.88rem;
  position: relative;
  z-index: 1;
}

/* Avatar */
.profile-avatar-wrap {
  margin-top: -55px;
  margin-bottom: 15px;
  position: relative;
  z-index: 2;
  padding-left: 30px;
}
.profile-avatar {
  width: 90px; height: 90px;
  border-radius: 50%;
  background: linear-gradient(135deg, #d4af37, #8b5a2b);
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; font-weight: 700; color: #fff;
  border: 4px solid #fff;
  box-shadow: 0 4px 15px rgba(139,90,43,0.3);
}

/* Stat Cards */
.stat-row { margin-top: 10px; margin-bottom: 24px; }
.stat-card {
  background: #fff;
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
  border: 1px solid #f0f0f5;
  display: flex; align-items: center; gap: 16px;
  transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.stat-icon {
  width: 50px; height: 50px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem;
  flex-shrink: 0;
}
.stat-icon.blue { background: rgba(139,90,43,0.1); color: #8B5A2B; }
.stat-icon.green { background: rgba(42,187,97,0.1); color: #2abb61; }
.stat-icon.orange { background: rgba(255,145,50,0.1); color: #ff9132; }
.stat-icon.red { background: rgba(228,64,95,0.1); color: #e4405f; }
.stat-value { font-size: 1.25rem; font-weight: 700; color: #000000; line-height: 1.3; }
.stat-label { font-size: 0.78rem; color: #899bbd; }

/* Table */
.trx-card { border-radius: 14px; border: 1px solid #f0f0f5; box-shadow: 0 2px 10px rgba(0,0,0,0.04); }
.trx-table thead th {
  background: #f8f9fa;
  color: #000000;
  font-weight: 600;
  font-size: 0.82rem;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  border-bottom: 2px solid #e9ecef;
  padding: 14px 16px;
  white-space: nowrap;
}
.trx-table tbody td {
  padding: 14px 16px;
  vertical-align: middle;
  color: #444;
  font-size: 0.88rem;
  border-bottom: 1px solid #f2f4f8;
}
.trx-table tbody tr:hover { background: #f8f9fa; }

/* Status */
.status-pill {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 14px;
  border-radius: 50px;
  font-size: 0.76rem; font-weight: 600;
}
.status-pill .indicator { width: 7px; height: 7px; border-radius: 50%; }
.status-0 { background: #ffedd5; color: #c2410c; border: 1px solid #fdba74; } /* Menunggu Pembayaran - Orange */
.status-0 .indicator { background: #ea580c; }
.status-validation { background: #fff3cd; color: #856404; } /* Menunggu Validasi - Yellow */
.status-validation .indicator { background: #ffc107; }
.status-1 { background: #cce5ff; color: #004085; }
.status-1 .indicator { background: #007bff; }
.status-2 { background: #e0cffc; color: #432874; }
.status-2 .indicator { background: #6f42c1; }
.status-3 { background: #d1f2eb; color: #0b5c3e; }
.status-3 .indicator { background: #2abb61; }
.status-4 { background: #fce4ec; color: #8e1b32; }
.status-4 .indicator { background: #e4405f; }

/* Detail Button */
.btn-detail-view {
  background: linear-gradient(135deg, #d4af37, #8b5a2b);
  border: none;
  color: #fff;
  padding: 6px 16px;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 600;
  transition: all 0.25s;
  box-shadow: 0 2px 8px rgba(139,90,43,0.25);
}
.btn-detail-view:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(139,90,43,0.4);
  color: #fff;
}

/* Modal */
.modal-premium .modal-content {
  border: none; border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}
.modal-premium .modal-header {
  background: linear-gradient(135deg, #1e150b, #0a0805);
  border: none; padding: 18px 24px;
}
.modal-premium .modal-title { color: #fceea7; font-weight: 700; }
.modal-premium .btn-close { filter: brightness(0) invert(1); opacity: 0.7; }
.modal-premium .modal-body { padding: 24px; }

/* Product List in Modal */
.product-row {
  display: flex; align-items: center; gap: 14px;
  padding: 14px;
  background: #f8f9fa;
  border-radius: 12px;
  margin-bottom: 10px;
  transition: background 0.2s;
}
.product-row:hover { background: #eef1ff; }
.product-row img {
  width: 56px; height: 56px;
  border-radius: 10px; object-fit: cover;
  border: 2px solid #e9ecef;
}
.product-row .ph-icon {
  width: 56px; height: 56px;
  border-radius: 10px;
  background: #e9ecef;
  display: flex; align-items: center; justify-content: center;
  color: #899bbd; font-size: 1.4rem;
}
.product-row .p-name { font-weight: 600; color: #000000; font-size: 0.9rem; }
.product-row .p-price { font-size: 0.8rem; color: #899bbd; }
.product-row .p-qty {
  background: rgba(139,90,43,0.1); color: #8B5A2B;
  padding: 3px 10px; border-radius: 6px;
  font-size: 0.78rem; font-weight: 600;
}
.product-row .p-sub { font-weight: 700; color: #000000; font-size: 0.92rem; white-space: nowrap; }

.summary-line {
  display: flex; justify-content: space-between; align-items: center;
  padding: 10px 14px; border-radius: 10px;
  font-size: 0.9rem;
}
.summary-line.ongkir { background: #f8f9fa; color: #899bbd; }
.summary-line.ongkir span:last-child { font-weight: 600; color: #000000; }
.summary-line.total {
  background: linear-gradient(135deg, #1e150b, #0a0805);
  color: #fceea7; font-weight: 700; font-size: 1rem;
  margin-top: 6px; padding: 14px;
}

/* Empty */
.empty-box { text-align: center; padding: 50px 20px; color: #899bbd; }
.empty-box i { font-size: 3rem; display: block; margin-bottom: 12px; opacity: 0.5; }
</style>

<!-- Banner -->
<div class="profile-banner">
  <h3>Halo, <?= esc($username) ?></h3>
  <p>Selamat datang kembali di Sebul Watch Co.!</p>
</div>

<!-- Avatar -->
<div class="profile-avatar-wrap">
  <div class="profile-avatar"><?= $initial ?></div>
</div>

<!-- Stats -->
<div class="stat-row">
  <div class="row g-3">
    <div class="col-6 col-md-3">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-bag-check-fill"></i></div>
        <div>
          <div class="stat-value"><?= $totalTransaksi ?></div>
          <div class="stat-label">Total Pesanan</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-wallet2"></i></div>
        <div>
          <div class="stat-value">IDR <?= number_format($totalBelanja, 0, ',', '.') ?></div>
          <div class="stat-label">Total Belanja</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-box-seam-fill"></i></div>
        <div>
          <div class="stat-value"><?= $totalProduk ?></div>
          <div class="stat-label">Produk Dibeli</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-check-circle-fill"></i></div>
        <div>
          <div class="stat-value"><?= $selesaiCount ?></div>
          <div class="stat-label">Selesai</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Transaction Table -->
<div class="card trx-card">
  <div class="card-body">
    <h5 class="card-title d-flex align-items-center gap-2">
      <i class="bi bi-receipt" style="color:#8B5A2B;"></i> Riwayat Transaksi
      <span class="badge rounded-pill ms-auto" style="background:rgba(139,90,43,0.1);color:#8B5A2B;font-size:0.78rem;"><?= $totalTransaksi ?> Transaksi</span>
    </h5>

    <?php if (!empty($buy)) : ?>
    <div class="table-responsive">
      <table class="table trx-table datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Alamat</th>
            <th>Total Bayar</th>
            <th>Status</th>
            <th class="text-center" style="text-align: center !important;">Bukti</th>
            <th class="text-center" style="text-align: center !important;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($buy as $index => $item) :
            $statusLabels = [
              0 => 'Menunggu Pembayaran',
              1 => 'Sudah Dibayar',
              2 => 'Sedang Dikirim',
              3 => 'Sudah Selesai',
              4 => 'Dibatalkan',
            ];
            $stLabel = $statusLabels[$item['status']] ?? 'Tidak Diketahui';
            $stClass = 'status-' . ($item['status'] ?? 0);
            
            if ($item['status'] == 0 && !empty($item['bukti_pembayaran'])) {
                $stLabel = 'Menunggu Validasi';
                $stClass = 'status-validation';
            }
          ?>
          <tr>
            <td class="fw-semibold"><?= $index + 1 ?></td>
            <td><span class="fw-bold" style="color:#8B5A2B;">#<?= $item['id'] ?></span></td>
            <td>
              <div class="fw-semibold"><?= date('d M Y', strtotime($item['created_at'])) ?></div>
              <small class="text-muted"><?= date('H:i', strtotime($item['created_at'])) ?> WIB</small>
            </td>
            <td style="max-width: 280px; line-height: 1.5; font-size: 13px;"><?= nl2br(esc($item['alamat'])) ?></td>
            <td class="fw-bold">IDR <?= number_format($item['total_harga'], 0, ',', '.') ?></td>
            <td>
              <span class="status-pill <?= $stClass ?>">
                <span class="indicator"></span>
                <?= $stLabel ?>
              </span>
            </td>
            <td class="text-center">
              <?php if (!empty($item['bukti_pembayaran'])): ?>
                <div class="d-flex align-items-center gap-2 justify-content-center">
                  <a href="<?= base_url('uploads/bukti/' . $item['bukti_pembayaran']) ?>" target="_blank">
                    <img src="<?= base_url('uploads/bukti/' . $item['bukti_pembayaran']) ?>" width="60" class="rounded border" alt="Bukti">
                  </a>
                  <?php if ($item['status'] == 0): ?>
                  <button type="button" class="btn btn-warning btn-sm" style="font-weight: 600; border-radius: 6px; font-size: 0.75rem; padding: 4px 8px;" data-bs-toggle="modal" data-bs-target="#uploadModal-<?= $item['id'] ?>" title="Upload Ulang Bukti">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <?php endif; ?>
                </div>
              <?php elseif ($item['status'] == 0): ?>
                <?php if(!empty($item['checkout_url'])): ?>
                  <a href="<?= esc($item['checkout_url']) ?>" class="btn btn-primary btn-sm" style="font-weight: 600; border-radius: 8px;">
                    <i class="bi bi-wallet2 me-1"></i>Bayar
                  </a>
                <?php else: ?>
                  <button type="button" class="btn btn-warning btn-sm" style="font-weight: 600; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#uploadModal-<?= $item['id'] ?>">
                    <i class="bi bi-upload me-1"></i>Upload Bukti
                  </button>
                <?php endif; ?>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
            <td class="text-center">
              <button type="button" class="btn-detail-view" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['id'] ?>">
                <i class="bi bi-eye me-1"></i>Detail
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else : ?>
    <div class="empty-box">
      <i class="bi bi-cart-x"></i>
      <h5>Belum Ada Transaksi</h5>
      <p>Anda belum memiliki riwayat pembelian.</p>
      <a href="<?= base_url('produk') ?>" class="btn mt-3" style="background: linear-gradient(135deg, #d4af37, #8b5a2b); border: none; color: white; padding: 10px 24px; font-weight: 600; border-radius: 10px; box-shadow: 0 4px 15px rgba(139,90,43,0.3);"><i class="bi bi-shop me-2"></i>Mulai Belanja</a>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Detail Modals -->
<?php if (!empty($buy)) : ?>
  <?php foreach ($buy as $item) :
    $statusLabels = [
      0 => 'Menunggu Pembayaran', 1 => 'Sudah Dibayar',
      2 => 'Sedang Dikirim', 3 => 'Sudah Selesai', 4 => 'Dibatalkan',
    ];
    $stLabel = $statusLabels[$item['status']] ?? 'Tidak Diketahui';
    $stClass = 'status-' . ($item['status'] ?? 0);
    
    if ($item['status'] == 0 && !empty($item['bukti_pembayaran'])) {
        $stLabel = 'Menunggu Validasi';
        $stClass = 'status-validation';
    }
  ?>
  <div class="modal fade modal-premium" id="detailModal-<?= $item['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-receipt me-2"></i>Detail Transaksi #<?= $item['id'] ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Info -->
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="mb-1"><i class="bi bi-calendar3 me-2" style="color:#8B5A2B;"></i><strong>Tanggal:</strong> <?= date('d F Y, H:i', strtotime($item['created_at'])) ?> WIB</div>
              <div class="mb-1"><i class="bi bi-geo-alt-fill me-2" style="color:#e4405f;"></i><strong>Alamat:</strong> <?= esc($item['alamat']) ?></div>
              <?php if (!empty($item['resi'])): ?>
              <div><i class="bi bi-box-seam me-2" style="color:#ff9132;"></i><strong>Resi Pengiriman:</strong> <span class="badge bg-light text-dark border"><?= esc($item['resi']) ?></span></div>
              <?php endif; ?>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
              <span class="status-pill <?= $stClass ?>" style="font-size:0.82rem;">
                <span class="indicator"></span>
                <?= $stLabel ?>
              </span>
            </div>
          </div>

          <hr style="border-color:#e9ecef;">

          <!-- Products -->
          <?php if(!empty($product[$item['id']])):
              foreach ($product[$item['id']] as $item2) : ?>
          <div class="product-row">
            <?php if (!empty($item2['foto'])) : ?>
              <img src="<?= base_url('NiceAdmin/assets/img/' . $item2['foto']) ?>" alt="<?= esc($item2['nama']) ?>">
            <?php else: ?>
              <div class="ph-icon"><i class="bi bi-box-seam"></i></div>
            <?php endif; ?>
            <div class="flex-grow-1">
              <div class="p-name"><?= esc($item2['nama'] ?? 'Produk Dihapus') ?></div>
              <div class="p-price">IDR <?= number_format($item2['harga'] ?? 0, 0, ',', '.') ?> /pcs</div>
            </div>
            <div class="text-end">
              <span class="p-qty"><?= $item2['jumlah'] ?>x</span>
              <div class="p-sub mt-1">IDR <?= number_format($item2['subtotal_harga'], 0, ',', '.') ?></div>
              <?php if ($item['status'] == 3): ?>
                <?php 
                    $db = \Config\Database::connect();
                    $hasReviewed = $db->table('product_reviews')
                                      ->where('product_id', $item2['product_id'])
                                      ->where('transaction_id', $item['id'])
                                      ->where('username', session()->get('username'))
                                      ->countAllResults() > 0;
                ?>
                <?php if (!$hasReviewed): ?>
                    <button type="button" class="btn btn-sm btn-outline-success mt-2" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#reviewModal-<?= $item['id'] ?>-<?= $item2['product_id'] ?>" style="font-size:0.75rem; border-radius: 6px;">
                      <i class="bi bi-star-fill text-warning"></i> Beri Ulasan
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" disabled style="font-size:0.75rem; border-radius: 6px;">
                      <i class="bi bi-check-circle"></i> Ulasan Terkirim
                    </button>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>


          <?php endforeach; endif; ?>

          <!-- Summary -->
          <div class="mt-3">
            <div class="summary-line ongkir">
              <span>
                <i class="bi bi-truck me-2"></i>Ongkos Kirim 
                <?php if (!empty($item['layanan'])): ?>
                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.65rem;"><?= esc($item['layanan']) ?></span>
                <?php endif; ?>
              </span>
              <span>IDR <?= number_format($item['ongkir'], 0, ',', '.') ?></span>
            </div>
            <div class="summary-line total">
              <span><i class="bi bi-cash-stack me-2"></i>Total Pembayaran</span>
              <span>IDR <?= number_format($item['total_harga'], 0, ',', '.') ?></span>
            </div>
          </div>

          <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
            <div class="d-flex justify-content-between w-100">
                <div>
                    <a href="<?= base_url('invoice/' . $item['id']) ?>" target="_blank" class="btn btn-outline-primary" style="font-weight: 600; border-radius: 8px;"><i class="bi bi-printer me-1"></i> Cetak Invoice</a>
                </div>
                <div>
                    <?php if ($item['status'] == 1 || $item['status'] == 2): ?>
                        <a href="<?= base_url('transaction/complete/' . $item['id']) ?>" class="btn btn-success" style="font-weight: 600; border-radius: 8px;" onclick="return confirm('Apakah Anda yakin pesanan sudah diterima dan ingin menyelesaikannya?')"><i class="bi bi-check-circle me-1"></i> Pesanan Diterima</a>
                    <?php endif; ?>
                    <button type="button" class="btn fw-bold ms-1" style="border-radius: 8px; background: #e2e8f0; color: #475569; border: none; transition: all 0.3s ease;" onmouseover="this.style.background='#cbd5e1'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='#e2e8f0'; this.style.transform='scale(1)';" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Review -->
  <?php if ($item['status'] == 3 && !empty($product[$item['id']])):
      foreach ($product[$item['id']] as $item2) : ?>
  <div class="modal fade" id="reviewModal-<?= $item['id'] ?>-<?= $item2['product_id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius: 16px;">
        <form action="<?= base_url('review') ?>" method="post" onsubmit="const btn = this.querySelector('button[type=submit]'); btn.disabled = true; btn.innerHTML = '<span class=\'spinner-border spinner-border-sm me-2\'></span>Mengirim...';">
          <?= csrf_field() ?>
          <input type="hidden" name="transaction_id" value="<?= $item['id'] ?>">
          <input type="hidden" name="product_id" value="<?= $item2['product_id'] ?>">
          <div class="modal-header border-0">
            <h5 class="modal-title fw-bold">Ulas Produk</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body pt-0">
            <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3">
              <?php if (!empty($item2['foto'])) : ?>
                <img src="<?= base_url('NiceAdmin/assets/img/' . $item2['foto']) ?>" width="40" height="40" class="rounded object-fit-cover">
              <?php endif; ?>
              <div class="fw-semibold text-dark"><?= esc($item2['nama']) ?></div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Penilaian Bintang</label>
              <select name="rating" class="form-select" required>
                <option value="5">⭐⭐⭐⭐⭐ Sangat Bagus</option>
                <option value="4">⭐⭐⭐⭐ Bagus</option>
                <option value="3">⭐⭐⭐ Lumayan</option>
                <option value="2">⭐⭐ Kurang</option>
                <option value="1">⭐ Buruk</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Ulasan Anda (Opsional)</label>
              <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan kepuasan Anda terhadap jam tangan ini..."></textarea>
            </div>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['id'] ?>">Kembali</button>
            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #d4af37, #8b5a2b); border: none;">Kirim Ulasan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; endif; ?>

  <!-- Modal Upload Bukti -->
  <?php if ($item['status'] == 0): ?>
  <div class="modal fade" id="uploadModal-<?= $item['id'] ?>" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content" style="border-radius: 16px; border: none;">
              <form action="<?= base_url('upload-bukti') ?>" method="post" enctype="multipart/form-data">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id_pembelian" value="<?= $item['id'] ?>">
                  <div class="modal-header" style="background-color: #f8f9fa; border-radius: 16px 16px 0 0; border-bottom: 1px solid #e9ecef;">
                      <h5 class="modal-title fw-bold" style="color: #000000;"><i class="bi bi-upload me-2"></i>Upload Bukti Pembayaran</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body p-4">
                      <div class="alert alert-primary mb-4" style="border-radius: 12px; border: 1px solid #cce5ff; background-color: #f8fbff;">
                          <h6 class="fw-bold mb-2" style="color: #3b6ef8 !important;"><i class="bi bi-info-circle me-2"></i>Instruksi Pembayaran</h6>
                          <p class="mb-2" style="font-size: 14px; color: #4a5568;">Silakan transfer pembayaran sebesar <strong class="text-dark">IDR <?= number_format($item['total_harga'], 0, ',', '.') ?></strong> ke salah satu rekening berikut atau scan QRIS:</p>
                          <ul class="list-unstyled mb-0" style="font-size: 14px; color: #4a5568;">
                              <li class="mb-1"><i class="bi bi-bank me-2" style="color: #3b6ef8 !important;"></i><strong>BCA:</strong> 1234567890 a.n. Sebul Watch Co.</li>
                              <li class="mb-1"><i class="bi bi-bank me-2" style="color: #3b6ef8 !important;"></i><strong>Mandiri:</strong> 0987654321 a.n. Sebul Watch Co.</li>
                              <li>
                                  <i class="bi bi-qr-code-scan me-2" style="color: #3b6ef8 !important;"></i><strong>QRIS:</strong> 
                                  <?php if(file_exists(FCPATH . 'uploads/qris.png')): ?>
                                      <br>
                                      <img src="<?= base_url('uploads/qris.png?v=' . time()) ?>" alt="QRIS" style="max-width: 200px; margin-top: 10px; border-radius: 8px; border: 1px solid #ccc;">
                                  <?php else: ?>
                                      Tersedia di kasir / CS
                                  <?php endif; ?>
                              </li>
                          </ul>
                      </div>
                      <div class="mb-3">
                          <label for="bukti" class="form-label fw-semibold">Upload File Bukti (Gambar)</label>
                          <input class="form-control form-control-lg" type="file" id="bukti" name="bukti" accept="image/*" required style="border-radius: 8px;">
                          <div class="form-text mt-2">Pastikan gambar jelas dan bukti transfer valid (Format: JPG, PNG). Setelah diupload, Admin akan memverifikasi pembayaran Anda.</div>
                      </div>
                  </div>
                  <div class="modal-footer" style="border-top: 1px solid #e9ecef;">
                      <button type="button" class="btn fw-bold" style="border-radius: 8px; background: #e2e8f0; color: #475569; border: none; transition: all 0.3s ease;" onmouseover="this.style.background='#cbd5e1'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='#e2e8f0'; this.style.transform='scale(1)';" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary" style="font-weight: 600; border-radius: 8px;">Kirim Bukti</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
