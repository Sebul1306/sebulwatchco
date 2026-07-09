<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<style>
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
        padding: 15px;
    }
    .table-laporan tbody tr td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }
    .table-laporan tbody tr td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
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
        <h2 class="fw-bold mb-0 text-white"><i class="bi bi-journal-check me-2"></i>Laporan Hutang</h2>
        <p class="mt-2 text-white-50">Monitoring rekapitulasi hutang Sebul Watch Co.</p>
    </div>

    <div class="laporan-card mx-3">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

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
                <a type="button" class="btn btn-danger d-inline-flex align-items-center gap-1 text-white shadow-sm ms-2" href="<?= base_url('laporan/exportHutangPdf' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" target="_blank" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600;">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
                <a type="button" class="btn btn-success d-inline-flex align-items-center gap-1 text-white shadow-sm ms-2" href="<?= base_url('laporan/exportHutangExcel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" style="border-radius: 8px; height: 38px; font-size: 13px; font-weight: 600; ">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
        </form>
    </div>

    <?php if (isset($hutang)) : ?>
    <div class="laporan-card mx-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0" style="color: #000000;">Hasil Rekapitulasi</h5>
        </div>
        
        <?php if (!empty($hutang)) : ?>
            <div class="table-responsive">
                <table class="table text-center table-borderless table-laporan">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 20%; text-align: left;">Tanggal</th>
                            <th style="width: 20%; text-align: left;">Supplier</th>
                            <th style="width: 25%; text-align: left;">Keterangan</th>
                            <th style="width: 15%;">Total Hutang</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($hutang as $i => $row) :
                            $total += $row['total_harga'];
                        ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td class="text-start text-muted"><i class="bi bi-clock-history me-1"></i> <?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                                <td class="text-start fw-bold text-dark"><?= esc($row['nama_supplier'] ?? 'Tidak Diketahui') ?></td>
                                <td class="text-start"><?= esc($row['keterangan']) ?></td>
                                <td class="fw-bold text-danger">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#bayarModal<?= $row['id'] ?>">
                                        <i class="bi bi-check-circle me-1"></i> Lunasi
                                    </button>
                                </td>
                            </tr>
                            

                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="6">
                                <div class="total-bar">
                                    <span class="fw-bold text-uppercase fs-6" style="letter-spacing: 1px;">Total Hutang Keseluruhan</span>
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
                <p class="fs-5">Tidak ada data hutang untuk periode <strong><?= date('d M Y', strtotime($tanggal_awal)) ?></strong> s/d <strong><?= date('d M Y', strtotime($tanggal_akhir)) ?></strong>.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php if (isset($hutang) && !empty($hutang)) : ?>
    <?php foreach ($hutang as $row) : ?>
        <!-- Modal Lunasi Hutang -->
        <div class="modal fade" id="bayarModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-cash-coin text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Konfirmasi Pembayaran</h5>
                        <p class="text-muted small mb-4">
                            Anda akan melunasi hutang kepada <strong><?= esc($row['nama_supplier'] ?? 'Supplier') ?></strong> sebesar:<br>
                            <span class="fs-4 fw-bold text-dark mt-2 d-block">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></span>
                        </p>
                        <div class="alert alert-warning border-0 small text-start" style="border-radius: 10px;">
                            <i class="bi bi-info-circle me-2"></i>Tindakan ini akan secara otomatis memotong saldo Kas Keluar Anda.
                        </div>
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Batal</button>
                            <a href="<?= base_url('laporan/bayarHutang/' . $row['id']) ?>" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
                                Ya, Bayar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
