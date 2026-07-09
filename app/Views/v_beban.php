<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #2abb61;">
  <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #e4405f;">
  <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0" style="color: #1e150b;"><i class="bi bi-wallet2 me-2" style="color: #d4af37;"></i>Pengeluaran Operasional</h5>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addBebanModal" style="background: linear-gradient(135deg, #d4af37, #8b5a2b); border: none; border-radius: 10px; font-weight: 600;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengeluaran
            </button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover datatable align-middle">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="15%">Tanggal</th>
                        <th width="40%">Nama Pengeluaran (Beban)</th>
                        <th width="25%">Nominal</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($beban)): ?>
                        <?php foreach ($beban as $index => $b): ?>
                        <tr>
                            <td class="text-center fw-semibold"><?= $index + 1 ?></td>
                            <td><?= date('d M Y', strtotime($b['tanggal'])) ?></td>
                            <td class="fw-semibold text-dark"><?= esc($b['nama_beban']) ?></td>
                            <td class="fw-bold text-danger">Rp <?= number_format($b['nominal'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#deleteBebanModal<?= $b['id'] ?>">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Hapus Beban -->
                        <div class="modal fade" id="deleteBebanModal<?= $b['id'] ?>" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
                            <div class="modal-content border-0" style="background:#fff;border-radius:24px;padding:36px 32px;text-align:center;box-shadow:0 30px 60px rgba(0,0,0,.2);">
                              <div class="modal-body p-0">
                                 <div style="width:68px;height:68px;border-radius:50%;background:rgba(220,38,38,.1);display:grid;place-items:center;margin:0 auto 18px">
                                    <i class="bi bi-exclamation-circle" style="font-size:2rem;color:#dc2626"></i>
                                 </div>
                                 <h5 style="font-weight:800;color:#0f172a;margin-bottom:8px;font-size:1.2rem">Konfirmasi Hapus</h5>
                                 <p style="color:#64748b;font-size:14px;margin-bottom:28px;line-height:1.6">
                                     Apakah Anda yakin ingin menghapus pengeluaran<br><strong class="text-dark"><?= esc($b['nama_beban']) ?></strong>?
                                 </p>
                                 <div class="d-flex gap-3">
                                   <button type="button" class="btn-logout-cancel" data-bs-dismiss="modal">
                                       Batal
                                   </button>
                                   <a href="<?= base_url('beban/delete/' . $b['id']) ?>" class="btn-logout-confirm">
                                       Ya, Hapus!
                                   </a>
                                 </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data pengeluaran operasional.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Beban -->
<div class="modal fade" id="addBebanModal" tabindex="-1" aria-labelledby="addBebanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form action="<?= base_url('beban/add') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header" style="background: linear-gradient(135deg, #1e150b, #0a0805); color: #fceea7; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold" id="addBebanModalLabel"><i class="bi bi-plus-circle me-2"></i>Catat Pengeluaran Baru</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info mb-4" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-info-circle-fill me-2"></i> Pengeluaran yang dicatat di sini otomatis memotong Laba Bersih & Arus Kas.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required style="border-radius: 8px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pengeluaran (Beban)</label>
                        <input type="text" name="nama_beban" class="form-control" placeholder="Contoh: Gaji Karyawan, Listrik, Iklan IG" required style="border-radius: 8px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="0" required style="border-radius: 8px;">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #d4af37, #8b5a2b); border: none; border-radius: 8px; font-weight: 600;">Simpan Pengeluaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
