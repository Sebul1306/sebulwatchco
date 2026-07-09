<?= $this->extend('layout') ?>

<?= $this->section('page_action') ?>
  <a type="button" class="btn btn-danger d-inline-flex align-items-center gap-1 text-white shadow-sm" href="<?= base_url('penjualan/export-pdf') ?>" target="_blank" style="border-radius: 10px; padding: 10px 20px; font-size: 14px; font-weight: 600;">
    <i class="bi bi-file-earmark-pdf"></i> Export PDF
  </a>
  
  <a type="button" class="btn d-inline-flex align-items-center gap-1 text-white shadow-sm" href="<?= base_url('penjualan/export-excel') ?>" style="border-radius: 10px; padding: 10px 20px; font-size: 14px; font-weight: 600; ">
    <i class="bi bi-file-earmark-excel"></i> Export Excel
  </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')) : ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="bi bi-check-circle me-1"></i>
  <?= session()->getFlashdata('success') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="bi bi-exclamation-octagon me-1"></i>
  <?= session()->getFlashdata('error') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
  <div class="card-body p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
      <form method="get" class="d-flex gap-2 align-items-center m-0">
          <label class="fw-bold text-muted small text-nowrap m-0">Filter Status:</label>
          <?php
            $selectBg = '#f8f9fa';
            $selectColor = '#212529';
            if (isset($current_status_filter)) {
                if ($current_status_filter === 'validasi') { $selectBg = '#ffc107'; $selectColor = '#000'; } // warning
                elseif ($current_status_filter === '0') { $selectBg = '#ffedd5'; $selectColor = '#c2410c'; }
                elseif ($current_status_filter === '1') { $selectBg = '#0dcaf0'; $selectColor = '#000'; } // info
                elseif ($current_status_filter === '2') { $selectBg = '#0d6efd'; $selectColor = '#fff'; } // primary
                elseif ($current_status_filter === '3') { $selectBg = '#198754'; $selectColor = '#fff'; } // success
                elseif ($current_status_filter === '4') { $selectBg = '#dc3545'; $selectColor = '#fff'; } // danger
            }
          ?>
          <select name="status" class="form-select form-select-sm border-0 shadow-sm fw-bold" style="border-radius: 8px; width: auto; background-color: <?= $selectBg ?>; color: <?= $selectColor ?>;" onchange="this.form.submit()">
              <option value="" style="background: #fff; color: #000;" <?= (!isset($current_status_filter) || $current_status_filter === '') ? 'selected' : '' ?>>Semua Status</option>
              <option value="validasi" style="background: #fff; color: #000;" <?= (isset($current_status_filter) && $current_status_filter === 'validasi') ? 'selected' : '' ?>>Menunggu Validasi</option>
              <option value="0" style="background: #fff; color: #000;" <?= (isset($current_status_filter) && $current_status_filter === '0') ? 'selected' : '' ?>>Menunggu Bayar</option>
              <option value="1" style="background: #fff; color: #000;" <?= (isset($current_status_filter) && $current_status_filter === '1') ? 'selected' : '' ?>>Sudah Dibayar</option>
              <option value="2" style="background: #fff; color: #000;" <?= (isset($current_status_filter) && $current_status_filter === '2') ? 'selected' : '' ?>>Sedang Dikirim</option>
              <option value="3" style="background: #fff; color: #000;" <?= (isset($current_status_filter) && $current_status_filter === '3') ? 'selected' : '' ?>>Sudah Selesai</option>
              <option value="4" style="background: #fff; color: #000;" <?= (isset($current_status_filter) && $current_status_filter === '4') ? 'selected' : '' ?>>Dibatalkan</option>
          </select>
      </form>
      <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"><?= count($transactions) ?> Pesanan Ditemukan</span>
    </div>

    <div class="table-responsive">
      <table class="table datatable table-hover align-middle">
        <thead style="background: #f7fcf0;">
          <tr>
            <th scope="col" class="text-center" style="text-align: center !important;">#</th>
            <th scope="col" class="text-center" style="text-align: center !important;">ID</th>
            <th scope="col">Username</th>
            <th scope="col">Alamat</th>
            <th scope="col">Ongkir</th>
            <th scope="col">Total Harga</th>
            <th scope="col">Status</th>
            <th scope="col" class="text-center" style="text-align: center !important;">Bukti</th>
            <th scope="col" class="text-center" style="text-align: center !important;">Ubah Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (!empty($transactions)) :
            foreach ($transactions as $index => $item) :
              $stLabels = [
                  0 => 'Menunggu Pembayaran',
                  1 => 'Sudah Dibayar',
                  2 => 'Sedang Dikirim',
                  3 => 'Sudah Selesai',
                  4 => 'Dibatalkan'
              ];
              $statusText = $stLabels[$item['status']] ?? 'Tidak Diketahui';
              $statusColor = 'bg-secondary';
              $rowClass = '';
              if ($item['status'] == 0) {
                  if (!empty($item['bukti_pembayaran'])) {
                      $statusColor = 'bg-warning text-dark';
                      $statusText = 'Menunggu Validasi';
                      $rowClass = 'table-warning';
                  } else {
                      $statusColor = '" style="background-color: #ffedd5; color: #c2410c; border: 1px solid #fdba74;';
                  }
              }
              else if ($item['status'] == 1) $statusColor = 'bg-info text-dark';
              else if ($item['status'] == 2) $statusColor = 'bg-primary';
              else if ($item['status'] == 3) $statusColor = 'bg-success';
              else if ($item['status'] == 4) $statusColor = 'bg-danger';
          ?>
              <tr class="<?= $rowClass ?>">
                <th scope="row" class="text-center align-middle"><?php echo $index + 1 ?></th>
                <td class="text-center align-middle fw-bold text-primary">#<?php echo $item['id'] ?></td>
                <td class="align-middle fw-semibold"><?php echo esc($item['username']) ?></td>
                <td class="align-middle text-muted" style="max-width: 280px; line-height: 1.5; font-size: 13px;"><?php echo nl2br(esc($item['alamat'])) ?></td>
                <td class="align-middle">
                  <div>IDR <?php echo number_format($item['ongkir'], 0, ',', '.') ?></div>
                  <?php if (!empty($item['layanan'])): ?>
                    <span class="badge bg-light text-secondary border mt-1" style="font-size: 11px;"><i class="bi bi-truck me-1"></i><?= esc($item['layanan']) ?></span>
                  <?php endif; ?>
                </td>
                <td class="align-middle fw-bold">IDR <?php echo number_format($item['total_harga'], 0, ',', '.') ?></td>
                <td class="align-middle">
                  <span class="badge <?= $statusColor ?>"><?php echo $statusText ?></span>
                </td>
                <td class="align-middle text-center">
                  <?php if (!empty($item['bukti_pembayaran'])): ?>
                    <a href="<?= base_url('uploads/bukti/' . $item['bukti_pembayaran']) ?>" target="_blank">
                      <img src="<?= base_url('uploads/bukti/' . $item['bukti_pembayaran']) ?>" width="50" class="rounded border" alt="Bukti">
                    </a>
                  <?php else: ?>
                    <span class="text-muted small">-</span>
                  <?php endif; ?>
                </td>
                <td class="align-middle">
                  <form action="<?= base_url('penjualan/updateStatus/' . $item['id']) ?>" method="post" class="d-flex flex-column align-items-center gap-2 m-0 justify-content-center">
                    <?= csrf_field() ?>
                    <select name="status" class="form-select form-select-sm" style="min-width: 140px; border-radius: 8px;" onchange="document.getElementById('resi-container-<?= $item['id'] ?>').style.display = (this.value == '2' ? 'block' : 'none');">
                      <option value="0" <?= $item['status'] == '0' ? 'selected' : '' ?>>Menunggu Bayar</option>
                      <option value="1" <?= $item['status'] == '1' ? 'selected' : '' ?>>Sudah Dibayar</option>
                      <option value="2" <?= $item['status'] == '2' ? 'selected' : '' ?>>Sedang Dikirim</option>
                      <option value="3" <?= $item['status'] == '3' ? 'selected' : '' ?>>Selesai</option>
                      <option value="4" <?= $item['status'] == '4' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                    <div id="resi-container-<?= $item['id'] ?>" style="display: <?= $item['status'] == '2' ? 'block' : 'none' ?>; width: 100%;">
                      <input type="text" name="resi" class="form-control form-control-sm" placeholder="No. Resi" value="<?= esc($item['resi'] ?? '') ?>" style="border-radius: 8px;">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 8px;"><i class="bi bi-check-lg"></i> Update</button>
                  </form>
                  <?php if ($item['status'] == 3 && isset($reviews[$item['id']])): ?>
                    <button type="button" class="btn btn-warning btn-sm w-100 mt-2 text-dark fw-bold d-flex align-items-center justify-content-center gap-1" data-bs-toggle="modal" data-bs-target="#adminReviewModal-<?= $item['id'] ?>" style="border-radius: 8px;"><i class="bi bi-star-fill"></i> Lihat Ulasan</button>
                  <?php endif; ?>
                </td>
              </tr>
          <?php endforeach;
          endif;
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

  <?php if (!empty($transactions)):
      foreach ($transactions as $item):
          if ($item['status'] == 3 && isset($reviews[$item['id']])): ?>
          <div class="modal fade" id="adminReviewModal-<?= $item['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header border-0">
                  <h5 class="modal-title fw-bold">Ulasan Transaksi #<?= $item['id'] ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                  <?php foreach ($reviews[$item['id']] as $r): ?>
                  <div class="mb-3 p-3 bg-light rounded-3" style="border-left: 4px solid #8b5a2b;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold text-dark"><?= esc($r['product_name']) ?></div>
                        <div>
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="bi bi-star-fill <?= $i <= $r['rating'] ? 'text-warning' : 'text-secondary' ?>" style="font-size:12px;"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="text-muted small mb-1"><i class="bi bi-person-fill"></i> <?= esc($r['username']) ?> &nbsp;&bull;&nbsp; <?= date('d M Y, H:i', strtotime($r['created_at'])) ?></div>
                    <?php if(!empty($r['comment'])): ?>
                        <div class="fst-italic mt-2" style="font-size: 14px;">"<?= esc($r['comment']) ?>"</div>
                    <?php endif; ?>
                  </div>
                  <?php endforeach; ?>
                </div>
                <div class="modal-footer border-0">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
                </div>
              </div>
            </div>
          </div>
  <?php endif; endforeach; endif; ?>

<?= $this->endSection() ?>
