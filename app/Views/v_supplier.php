<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-truck-flatbed text-primary me-2"></i> Daftar Supplier</h5>
                <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#tambahSupplierModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Supplier
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Nama Supplier</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($suppliers as $s): ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td class="fw-semibold"><?= esc($s['nama']) ?></td>
                                <td><?= esc($s['kontak']) ?></td>
                                <td><?= esc($s['alamat']) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-warning" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#editSupplierModal<?= $s['id'] ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#deleteSupplierModal<?= $s['id'] ?>">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Modal Edit -->
                            <div class="modal fade" id="editSupplierModal<?= $s['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Edit Supplier</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?= base_url('supplier/edit/' . $s['id']) ?>" method="post">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Supplier</label>
                                                    <input type="text" class="form-control" name="nama" value="<?= esc($s['nama']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kontak (No HP / Email)</label>
                                                    <input type="text" class="form-control" name="kontak" value="<?= esc($s['kontak']) ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alamat</label>
                                                    <textarea class="form-control" name="alamat" rows="2"><?= esc($s['alamat']) ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Hapus -->
                            <div class="modal fade" id="deleteSupplierModal<?= $s['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
                                    <div class="modal-content border-0" style="background:#fff;border-radius:24px;padding:36px 32px;text-align:center;box-shadow:0 30px 60px rgba(0,0,0,.2);">
                                        <div class="modal-body p-0">
                                            <div style="width:68px;height:68px;border-radius:50%;background:rgba(220,38,38,.1);display:grid;place-items:center;margin:0 auto 18px">
                                                <i class="bi bi-exclamation-circle" style="font-size:2rem;color:#dc2626"></i>
                                            </div>
                                            <h5 style="font-weight:800;color:#0f172a;margin-bottom:8px;font-size:1.2rem">Hapus Supplier?</h5>
                                            <p style="color:#64748b;font-size:14px;margin-bottom:28px;line-height:1.6">
                                                Anda yakin ingin menghapus supplier<br><strong class="text-dark"><?= esc($s['nama']) ?></strong>?
                                            </p>
                                            <div class="d-flex gap-3">
                                                <button type="button" class="btn-logout-cancel" data-bs-dismiss="modal">
                                                    Batal
                                                </button>
                                                <a href="<?= base_url('supplier/delete/' . $s['id']) ?>" class="btn-logout-confirm">
                                                    Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Supplier Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('supplier/add') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak (No HP / Email)</label>
                        <input type="text" class="form-control" name="kontak">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
