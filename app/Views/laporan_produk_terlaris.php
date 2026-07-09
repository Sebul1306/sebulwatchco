<?= $this->extend('layout') ?>



<?= $this->section('content') ?>

<style>
    /* Styling khusus agar tampil persis seperti Dashboard Toko */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

    .custom-container {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .header-banner {
        background: linear-gradient(135deg, #1e150b, #0a0805);
        color: white;
        padding: 40px 30px 80px;
        border-radius: 20px;
        margin-bottom: -50px;
        box-shadow: 0 10px 30px rgba(139,90,43,0.2);
    }
    
    .laporan-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
        position: relative;
        z-index: 2;
    }
    
    .table-laporan {
        vertical-align: middle;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .table-laporan thead th {
        border-bottom: none;
        color: #a3aed1;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 15px;
    }
    .table-laporan tbody tr {
        background: #f8f9fc;
        transition: all 0.2s;
    }
    .table-laporan tbody tr:hover {
        background: #f1f4fb;
        transform: translateY(-2px);
    }
    .table-laporan tbody td {
        border: none;
        padding: 16px 15px;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .table-laporan tbody td:first-child { border-radius: 12px 0 0 12px; }
    .table-laporan tbody td:last-child { border-radius: 0 12px 12px 0; }
</style>

<div class="custom-container">
    <div class="header-banner text-center">
        <h2 class="fw-bold mb-0 text-white"><i class="bi bi-star-fill me-2"></i>Produk Terlaris</h2>
        <p class="mt-2 text-white-50">Monitoring rekapitulasi penjualan produk terlaris Sebul Watch Co.</p>
    </div>

    <div class="laporan-card mx-3">
        <div class="d-flex flex-wrap justify-content-end mb-3 gap-2">
            <a type="button" class="btn btn-danger d-inline-flex align-items-center gap-1 text-white shadow-sm" href="<?= base_url('laporan/terlaris/export-pdf') ?>" target="_blank" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600;">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
            <a type="button" class="btn btn-success d-inline-flex align-items-center gap-1 text-white shadow-sm" href="<?= base_url('laporan/terlaris/export-excel') ?>" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600;">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
        </div>
        <div class="table-responsive">
            <table class="table text-center table-borderless table-laporan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-start">Produk</th>
                        <th>Qty Terjual</th>
                        <th class="text-end">Total Omzet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($produk)): ?>
                        <?php foreach($produk as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="text-start fw-bold text-dark"><?= esc($p['nama']) ?></td>
                            <td class="fw-bold text-dark"><?= $p['qty'] ?></td>
                            <td class="text-end fw-bold text-success">Rp <?= number_format($p['omzet'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-muted">Belum ada data penjualan produk.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
