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

    /* ── Stats Row ── */
    .stat-card {
      border: none;
      border-radius: 20px;
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 20px;
      color: #fff;
      box-shadow: 0 10px 20px rgba(0,0,0,.08);
      transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow .3s;
      position: relative;
      overflow: hidden;
    }
    .stat-card::after {
      content: ''; position: absolute; top: -50%; right: -10%;
      width: 150px; height: 150px; background: rgba(255,255,255,0.15);
      border-radius: 50%;
    }
    .stat-card.blue  { background: linear-gradient(135deg, #2876f9 0%, #6d17cb 100%); box-shadow: 0 8px 24px rgba(40,118,249,.3); }
    .stat-card.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 8px 24px rgba(16,185,129,.3); }
    .stat-card.amber { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%); box-shadow: 0 8px 24px rgba(255,8,68,.3); }
    .stat-card:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 16px 32px rgba(0,0,0,.15); }
    
    .stat-icon {
      width: 60px; height: 60px; border-radius: 16px;
      background: rgba(255, 255, 255, 0.2);
      display: flex; align-items: center; justify-content: center;
      font-size: 28px; flex-shrink: 0; backdrop-filter: blur(4px);
      border: 1px solid rgba(255,255,255,0.3);
    }
    .stat-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; opacity: 0.9; margin-bottom: 4px; }
    .stat-value { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; line-height: 1.2; letter-spacing: -0.5px; text-shadow: 0 2px 8px rgba(0,0,0,0.2); }
</style>

<div class="custom-container">
    <div class="header-banner text-center">
        <h2 class="fw-bold mb-0 text-white"><i class="bi bi-arrow-left-right me-2"></i>Laporan Arus Kas</h2>
        <p class="mt-2 text-white-50">Monitoring rekapitulasi arus kas masuk dan keluar Sebul Watch Co.</p>
    </div>

    <div class="laporan-card mx-3">
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
                <a type="button" class="btn btn-danger d-inline-flex align-items-center gap-1 text-white shadow-sm ms-2" href="<?= base_url('laporan/arus-kas/export-pdf' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" target="_blank" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600;">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
                <a type="button" class="btn btn-success d-inline-flex align-items-center gap-1 text-white shadow-sm ms-2" href="<?= base_url('laporan/arus-kas/export-excel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600; ">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
        </form>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card green">
                    <div class="stat-icon"><i class="bi bi-arrow-down-circle"></i></div>
                    <div>
                        <div class="stat-label">Total Kas Masuk</div>
                        <div class="stat-value">Rp <?= number_format($totalMasuk, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card amber">
                    <div class="stat-icon"><i class="bi bi-arrow-up-circle"></i></div>
                    <div>
                        <div class="stat-label">Total Kas Keluar</div>
                        <div class="stat-value">Rp <?= number_format($totalKeluar, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card blue">
                    <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <div class="stat-label">Saldo Akhir</div>
                        <div class="stat-value">Rp <?= number_format($saldoAkhir, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive mt-2">
            <table class="table text-center table-borderless table-laporan">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($arusKas)): ?>
                        <?php foreach($arusKas as $k): ?>
                        <tr>
                            <td class="text-center"><?= date('d M Y H:i', strtotime($k['tanggal'])) ?></td>
                            <td class="text-start fw-bold text-dark"><?= esc($k['keterangan']) ?></td>
                            <td class="text-center">
                                <?php if($k['jenis'] == 'masuk'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Masuk</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Keluar</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold <?= $k['jenis'] == 'masuk' ? 'text-success' : 'text-danger' ?>">
                                <?= $k['jenis'] == 'masuk' ? '+' : '-' ?> Rp <?= number_format($k['nominal'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-muted py-4"><i class="bi bi-info-circle d-block mb-2 fs-2 text-primary"></i>Belum ada data arus kas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
