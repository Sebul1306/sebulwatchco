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
</style>

<div class="custom-container">
    <div class="header-banner text-center">
        <h2 class="fw-bold mb-0 text-white"><i class="bi bi-bar-chart-line me-2"></i>Laporan Laba Rugi</h2>
        <p class="mt-2 text-white-50">Monitoring rekapitulasi laba rugi bulanan Sebul Watch Co.</p>
    </div>

    <div class="laporan-card mx-3 p-5">
                        <form action="<?= current_url() ?>" method="get" class="d-flex flex-wrap gap-3 align-items-end mb-4 pb-4" id="filterForm" style="border-bottom: 1px solid #f1f5f9;">
            <input type="hidden" name="tanggal_awal" id="tanggal_awal" value="<?= esc($tanggal_awal ?? '') ?>">
            <input type="hidden" name="tanggal_akhir" id="tanggal_akhir" value="<?= esc($tanggal_akhir ?? '') ?>">
            <input type="hidden" name="filter_type" id="filter_type_hidden" value="<?= esc($_GET['filter_type'] ?? '') ?>">
            
            <div style="min-width: 220px;">
                <label class="form-label fw-semibold text-muted mb-1" style="font-size: 13px;">Filter Cepat</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-funnel"></i></span>
                    <select id="quickFilter" class="form-select border-start-0" style="border-radius: 0 8px 8px 0;">
                        <option value="">-- Pilih Periode --</option>
                        <option value="today">Hari Ini</option>
                        <option value="this_week">Minggu Ini</option>
                        <option value="this_month">Bulan Ini</option>
                        <option value="3_months">3 Bulan Terakhir</option>
                        <option value="6_months">6 Bulan Terakhir</option>
                        <option value="this_year">Tahun Ini</option>
                        <option value="all_time">Selama Ini</option>
                        <option value="custom_bulan">Custom Bulan</option>
                        <option value="custom">Custom Tanggal</option>
                    </select>
                </div>
            </div>
            
            <div class="custom-bulan-ui" style="display: none; min-width: 160px;">
                <label class="form-label fw-semibold text-muted mb-1" style="font-size: 13px;">Bulan</label>
                <select id="bulanSelect" class="form-select" style="border-radius: 8px;">
                    <?php 
                    $months = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                    $selectedBulan = $_GET['bulan'] ?? date('m');
                    foreach($months as $num => $name): ?>
                        <option value="<?= $num ?>" <?= $selectedBulan == $num ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="custom-bulan-ui" style="display: none; min-width: 120px;">
                <label class="form-label fw-semibold text-muted mb-1" style="font-size: 13px;">Tahun</label>
                <select id="tahunSelect" class="form-select" style="border-radius: 8px;">
                    <?php 
                    $selectedTahun = $_GET['tahun'] ?? date('Y');
                    for($y = 2023; $y <= date('Y'); $y++): ?>
                        <option value="<?= $y ?>" <?= $selectedTahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="custom-tanggal-ui" style="display: none; min-width: 180px;">
                <label for="ui_tanggal_awal" class="form-label fw-semibold text-muted mb-1" style="font-size: 13px;">Tanggal Awal</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar"></i></span>
                    <input type="date" id="ui_tanggal_awal" class="form-control border-start-0" style="border-radius: 0 8px 8px 0;" value="<?= esc($tanggal_awal ?? '') ?>">
                </div>
            </div>
            <div class="custom-tanggal-ui" style="display: none; min-width: 180px;">
                <label for="ui_tanggal_akhir" class="form-label fw-semibold text-muted mb-1" style="font-size: 13px;">Tanggal Akhir</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-check"></i></span>
                    <input type="date" id="ui_tanggal_akhir" class="form-control border-start-0" style="border-radius: 0 8px 8px 0;" value="<?= esc($tanggal_akhir ?? '') ?>">
                </div>
            </div>
            
            <div>
                <button type="button" id="btnSubmitFilter" class="btn btn-primary px-4 shadow-sm" style="background: #8B5A2B; border: none; border-radius: 8px; height: 38px;">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
                <a type="button" class="btn btn-danger d-inline-flex align-items-center gap-1 text-white shadow-sm ms-2" href="<?= base_url('laporan/laba-rugi/export-pdf' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" target="_blank" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600;">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
                <a type="button" class="btn btn-success d-inline-flex align-items-center gap-1 text-white shadow-sm ms-2" href="<?= base_url('laporan/laba-rugi/export-excel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600; ">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
        </form>

        <div class="mb-4">
            <h5 class="fw-bold border-bottom pb-2" style="color: #059669;"><i class="bi bi-arrow-down-circle me-2"></i>Pendapatan</h5>
            <div class="d-flex justify-content-between mb-2">
                <span class="ps-4 text-dark fw-semibold">Penjualan</span>
                <span class="fw-bold text-dark">Rp <?= number_format($penjualan, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold border-bottom pb-2" style="color: #dc2626;"><i class="bi bi-arrow-up-circle me-2"></i>Harga Pokok Penjualan (HPP)</h5>
            <div class="d-flex justify-content-between mb-2">
                <span class="ps-4 text-dark fw-semibold">Pembelian Barang</span>
                <span class="fw-bold text-dark">Rp <?= number_format($hpp, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="p-3 rounded-3 mb-4 d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-left: 4px solid #8B5A2B;">
            <h5 class="m-0 fw-bold text-dark">Laba Kotor</h5>
            <h5 class="m-0 fw-bold" style="color: <?= $labaKotor >= 0 ? '#059669' : '#dc2626' ?>;">Rp <?= number_format($labaKotor, 0, ',', '.') ?></h5>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold border-bottom pb-2" style="color: #d97706;"><i class="bi bi-journal-minus me-2"></i>Beban-Beban</h5>
            <?php if(!empty($beban)): ?>
                <?php foreach($beban as $b): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span class="ps-4 text-dark fw-semibold"><?= esc($b['nama_beban']) ?></span>
                    <span class="text-dark">Rp <?= number_format($b['nominal'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ps-4 text-muted fst-italic">Tidak ada beban tercatat.</div>
            <?php endif; ?>
            <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                <span class="ps-4 fw-bold text-dark">Total Beban</span>
                <span class="fw-bold text-dark">Rp <?= number_format($totalBeban, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="p-4 rounded-4 d-flex justify-content-between align-items-center shadow-sm" style="background: <?= $labaBersih >= 0 ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)' ?>; color: white;">
            <h4 class="m-0 fw-bold text-white"><i class="bi <?= $labaBersih >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' ?> me-2"></i><?= $labaBersih >= 0 ? 'Laba Bersih' : 'Rugi Bersih' ?></h4>
            <h3 class="m-0 fw-bold text-white">Rp <?= number_format($labaBersih, 0, ',', '.') ?></h3>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
