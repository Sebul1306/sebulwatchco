<?php
helper('number');
?>

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
    
    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    .st-0 { background: #ffe4e6; color: #e11d48; }
    .st-1 { background: #fef08a; color: #854d0e; }
    .st-2 { background: #dbeafe; color: #1e40af; }
    .st-3 { background: #dcfce7; color: #166534; }
    .st-4 { background: #f3f4f6; color: #4b5563; }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .user-avatar {
        width: 36px;
        height: 36px;
        background: rgba(139,90,43,0.1);
        color: #8B5A2B;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }
    .total-row td {
        padding: 0 !important;
        border: none !important;
    }
    .total-bar {
        background: rgba(139,90,43,0.1);
        color: #8B5A2B;
        border-radius: 16px;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }
</style>

<div class="custom-container">
    <div class="header-banner text-center">
        <h2 class="fw-bold mb-0 text-white"><i class="bi bi-graph-up-arrow me-2"></i>Laporan Pendapatan</h2>
        <p class="mt-2 text-white-50">Monitoring rekapitulasi transaksi keuangan Sebul Watch Co.</p>
    </div>

    <div class="laporan-card mx-3">
                        <form action="<?= current_url() ?>" method="get" class="d-flex flex-wrap gap-3 align-items-end" id="filterForm">
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
                <a type="button" class="btn btn-danger d-inline-flex align-items-center gap-1 text-white shadow-sm ms-2" href="<?= base_url('laporan/exportPdf' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" target="_blank" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600;">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
                <a type="button" class="btn btn-success d-inline-flex align-items-center gap-1 text-white shadow-sm ms-2" href="<?= base_url('laporan/exportExcel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600; ">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
        </form>
    </div>

    <?php if (isset($laporan)) : ?>
    <div class="laporan-card mx-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0" style="color: #000000;">Hasil Rekapitulasi</h5>
        </div>
        
        <?php if (!empty($laporan)) : ?>
            <div class="table-responsive">
                <table class="table text-center table-borderless table-laporan">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 25%; text-align: left;">Pelanggan</th>
                            <th style="width: 20%; text-align: left;">Tanggal</th>
                            <th style="width: 15%;">Total Harga</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 15%;">ID Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($laporan as $i => $row) :
                            $total += $row['total_harga'];
                            
                            $stLabels = [
                                0 => 'Belum Dibayar',
                                1 => 'Diproses',
                                2 => 'Dikirim',
                                3 => 'Selesai',
                                4 => 'Dibatalkan'
                            ];
                            $statusText = $stLabels[$row['status']] ?? 'Tidak Diketahui';
                            $statusClass = 'st-' . $row['status'];
                            $initial = strtoupper(substr($row['username'], 0, 1));
                        ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td class="text-start">
                                    <div class="user-info">
                                        <div class="user-avatar"><?= $initial ?></div>
                                        <span class="fw-bold text-dark"><?= esc($row['username']) ?></span>
                                    </div>
                                </td>
                                <td class="text-start text-muted"><i class="bi bi-clock-history me-1"></i> <?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
                                <td class="fw-bold" style="color: #8B5A2B;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td><span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                <td class="text-muted fw-bold">#<?= esc($row['id']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="6">
                                <div class="total-bar">
                                    <span class="fw-bold text-uppercase fs-6" style="letter-spacing: 1px;">Total Pendapatan</span>
                                    <span class="fw-bold fs-4">Rp <?= number_format($total, 0, ',', '.') ?></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inboxes fs-1 opacity-25 mb-3 d-block"></i>
                <p class="fs-5">Tidak ada data pendapatan untuk periode <strong><?= date('d M Y', strtotime($tanggal_awal)) ?></strong> s/d <strong><?= date('d M Y', strtotime($tanggal_akhir)) ?></strong>.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
