<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* FORCE Plus Jakarta Sans */
body { font-family: 'Plus Jakarta Sans', sans-serif !important; }

/* ── Hero ── */
.dashboard-hero { border-radius: 28px !important; padding: 32px 36px !important; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); margin-bottom: 24px; position: relative; overflow: hidden; }
.dashboard-hero::before { content: ''; position: absolute; right: -50px; top: -50px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(212,175,55,0.15) 0%, transparent 70%); pointer-events: none; }
.dashboard-hero-title { font-size: 2rem !important; font-weight: 800 !important; line-height: 1.2 !important; letter-spacing: -.4px !important; margin-bottom: 12px; }
.dashboard-hero-text  { font-size: .93rem !important; line-height: 1.7 !important; font-weight: 400 !important; color: #cbd5e1; max-width: 500px; margin-bottom: 0; }
.hero-mini-badge { font-size: 11px !important; padding: 5px 12px !important; background: rgba(212,175,55,0.2); border: 1px solid rgba(212,175,55,0.3); color: #fde047; border-radius: 20px; display: inline-flex; align-items: center; font-weight: 700; margin-bottom: 16px; letter-spacing: 0.5px; text-transform: uppercase; }
.hero-summary-box { display: flex; flex-direction: column; gap: 12px; align-items: stretch; margin-top: 10px; }
.hero-summary-item { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(212,175,55,0.2); border-radius: 20px; padding: 20px 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); min-width: 190px; transition: transform 0.3s ease; }
.hero-summary-item:hover { transform: translateY(-5px); border-color: rgba(212,175,55,0.5); background: rgba(255,255,255,0.08); }
.hero-summary-item span { font-size: 11.5px !important; font-weight: 700 !important; color: #cbd5e1 !important; text-transform: uppercase !important; letter-spacing: 1px !important; display: block; margin-bottom: 8px; }
.hero-summary-item span i { color: #d4af37; font-size: 14px; }
.hero-summary-item h3 { font-size: 1.5rem !important; font-weight: 800 !important; margin: 0 !important; color: #fff; letter-spacing: -0.5px; }

/* ── Stat cards ── */
.dashboard-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px; }
.stat-card {
    border: none !important;
    border-radius: 20px !important;
    padding: 24px !important;
    display: flex !important;
    align-items: center !important;
    gap: 20px !important;
    color: #fff !important;
    box-shadow: 0 10px 20px rgba(0,0,0,.08) !important;
    transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow .3s !important;
    position: relative !important;
    overflow: hidden !important;
    background: #fff !important; /* fallback */
}
.stat-card::after {
    content: ''; position: absolute; top: -50%; right: -10%;
    width: 150px; height: 150px; background: rgba(255,255,255,0.15);
    border-radius: 50%;
}
.stat-card.blue  { background: linear-gradient(135deg, #2876f9 0%, #6d17cb 100%) !important; box-shadow: 0 8px 24px rgba(40,118,249,.3) !important; }
.stat-card.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; box-shadow: 0 8px 24px rgba(16,185,129,.3) !important; }
.stat-card.amber { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%) !important; box-shadow: 0 8px 24px rgba(255,8,68,.3) !important; }
.stat-card.purple { background: linear-gradient(135deg, #b822f6 0%, #7d2ae8 100%) !important; box-shadow: 0 8px 24px rgba(125,42,232,.3) !important; }

.stat-card:hover { transform: translateY(-6px) scale(1.02) !important; box-shadow: 0 16px 32px rgba(0,0,0,.15) !important; }

.stat-icon {
    width: 60px !important; height: 60px !important; border-radius: 16px !important;
    background: rgba(255, 255, 255, 0.2) !important;
    display: flex !important; align-items: center !important; justify-content: center !important;
    font-size: 28px !important; flex-shrink: 0 !important; backdrop-filter: blur(4px) !important;
    border: 1px solid rgba(255,255,255,0.3) !important;
    color: #fff !important;
}
.stat-label { font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 13px !important; font-weight: 600 !important; letter-spacing: 1px !important; text-transform: uppercase !important; opacity: 0.9 !important; margin-bottom: 4px !important; color: #fff !important; line-height: 1 !important;}
.stat-value { font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 26px !important; font-weight: 800 !important; line-height: 1 !important; letter-spacing: -1px !important; text-shadow: 0 2px 8px rgba(0,0,0,0.2) !important; margin: 0 !important; color: #fff !important;}

/* ── Content cards ── */
.content-card { background: #ffffff !important; border: 1.5px solid #eef2f7 !important; border-radius: 24px !important; padding: 24px 26px !important; box-shadow: 0 4px 15px rgba(15,23,42,.03) !important; height: 100%; }
.section-title { font-size: 16px !important; font-weight: 800 !important; color: #1e293b !important; letter-spacing: -.2px !important; margin-bottom: 4px !important; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; gap: 8px; }
.text-primary-custom { color: #8B5A2B !important; }

/* ── Recent items ── */
.recent-order-item { background: #faf9f7 !important; border: 1.5px solid #f3e8dd !important; border-radius: 16px !important; padding: 14px 16px !important; transition: all .18s !important; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
.recent-order-item:hover { background: #fdfaf6 !important; border-color: #e5d3b3 !important; transform: translateX(3px); }
.recent-order-code { font-size: 14px !important; font-weight: 800 !important; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif; }
.recent-order-customer { font-size: 12px !important; color: #64748b !important; margin-top: 2px; font-weight: 600; }
.recent-order-price { font-size: 14.5px !important; font-weight: 800 !important; color: #8B5A2B !important; font-family: 'Plus Jakarta Sans', sans-serif; }
.recent-order-date { font-size: 11px !important; color: #94a3b8 !important; font-weight: 500; }

.badge-soft-warning { background: rgba(245,158,11,0.1); color: #d97706; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.badge-soft-info { background: rgba(56,189,248,0.1); color: #0284c7; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.badge-soft-primary { background: rgba(139,90,43,0.1); color: #8B5A2B; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.badge-soft-success { background: rgba(16,185,129,0.1); color: #059669; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.badge-soft-danger { background: rgba(239,68,68,0.1); color: #dc2626; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
</style>

<!-- ── HERO ── -->
<div class="dashboard-hero">
    <div class="dashboard-hero-left">
        <span class="hero-mini-badge"><i class="bi bi-stars me-1"></i>E-Commerce System</span>
        <h1 class="dashboard-hero-title">Pengelolaan Keuangan<br>Sebul Watch Co.</h1>
        <p class="dashboard-hero-text">Pantau penjualan, pengguna, transaksi, dan pelaporan piutang pelanggan secara real-time dari satu dashboard terintegrasi.</p>
        <div class="mt-4">
            <a href="<?= base_url('penjualan') ?>" class="btn btn-primary fw-bold rounded-pill px-4 me-2" style="background: linear-gradient(135deg, #d4af37 0%, #8B5A2B 100%); border: none; box-shadow: 0 4px 12px rgba(139,90,43,0.4);"><i class="bi bi-card-list me-2"></i>Data Penjualan</a>
            <a href="<?= base_url('laporan/laba-rugi') ?>" class="btn btn-outline-light fw-bold rounded-pill px-4"><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Keuangan</a>
        </div>
    </div>
    <div class="dashboard-hero-right d-flex align-items-center">
        <div class="hero-summary-box">
            <div class="hero-summary-item">
                <span><i class="bi bi-wallet2 me-1"></i>Total Omzet</span>
                <h3>Rp <?= number_format($totalOmzet, 0, ',', '.') ?></h3>
            </div>
            <div class="hero-summary-item">
                <span><i class="bi bi-receipt me-1"></i>Total Transaksi</span>
                <h3><?= number_format($totalTransaksi) ?> Pesanan</h3>
            </div>
        </div>
    </div>
</div>

<!-- ── STAT CARDS ── -->
<div class="dashboard-stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
        <div><p class="stat-label">Total Omzet</p><h3 class="stat-value" style="font-size: 22px !important;">Rp <?= number_format($totalOmzet, 0, ',', '.') ?></h3></div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
        <div><p class="stat-label">Total Produk</p><h3 class="stat-value"><?= $totalProduk ?></h3></div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon"><i class="bi bi-cart-check"></i></div>
        <div><p class="stat-label">Total Transaksi</p><h3 class="stat-value"><?= $totalTransaksi ?></h3></div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon"><i class="bi bi-people"></i></div>
        <div><p class="stat-label">Total Pengguna</p><h3 class="stat-value"><?= isset($totalUser) ? $totalUser : 0 ?></h3></div>
    </div>
</div>

<!-- ── CHARTS ── -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="section-title"><i class="bi bi-graph-up-arrow text-primary-custom"></i> Grafik Penjualan Bulanan</h5>
                    <small class="text-muted">Pergerakan total penjualan selama periode berjalan</small>
                </div>
            </div>
            <div style="height:300px; position:relative;">
                <canvas id="penjualanChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="content-card">
            <h5 class="section-title mb-4"><i class="bi bi-pie-chart text-success"></i> Status Pesanan</h5>
            <div style="height:300px; position:relative;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ── LISTS ── -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="section-title"><i class="bi bi-clock-history text-warning"></i> 5 Transaksi Terbaru</h5>
                    <small class="text-muted">Daftar pesanan terbaru yang masuk ke sistem</small>
                </div>
                <a href="<?= base_url('penjualan') ?>" class="btn btn-sm btn-light rounded-pill fw-bold" style="font-size: 12px; color: #8B5A2B;">Lihat Semua</a>
            </div>
            
            <div class="recent-order-list">
                <?php if(!empty($transaksiTerbaru)): ?>
                    <?php foreach($transaksiTerbaru as $trx): ?>
                    <div class="recent-order-item">
                        <div>
                            <div class="recent-order-code">#<?= sprintf('%04d', $trx['id']) ?></div>
                            <div class="recent-order-customer"><?= esc($trx['username']) ?></div>
                        </div>
                        <div class="text-end">
                            <div class="recent-order-price mb-1">Rp <?= number_format($trx['total_harga'], 0, ',', '.') ?></div>
                            <?php 
                            switch($trx['status']){
                                case 0: echo '<span class="badge-soft-warning">Belum Bayar</span>'; break;
                                case 1: echo '<span class="badge-soft-info">Dibayar</span>'; break;
                                case 2: echo '<span class="badge-soft-primary">Dikirim</span>'; break;
                                case 3: echo '<span class="badge-soft-success">Selesai</span>'; break;
                                case 4: echo '<span class="badge-soft-danger">Batal</span>'; break;
                            }
                            ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                        <span style="font-size: 13px;">Belum ada transaksi terbaru.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="section-title"><i class="bi bi-exclamation-circle text-danger"></i> Pelanggan Belum Bayar</h5>
                    <small class="text-muted">Status pembayaran belum lunas (Piutang)</small>
                </div>
                <a href="<?= base_url('laporan/piutang') ?>" class="btn btn-sm btn-light rounded-pill fw-bold" style="font-size: 12px; color: #dc2626;">Laporan</a>
            </div>
            
            <div class="recent-order-list">
                <?php if(!empty($pelangganBelumBayar)): ?>
                    <?php foreach($pelangganBelumBayar as $p): ?>
                    <div class="recent-order-item" style="border-left: 3px solid #f59e0b !important;">
                        <div>
                            <div class="recent-order-code"><?= esc($p['username']) ?></div>
                            <div class="recent-order-date mt-1"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($p['created_at'])) ?></div>
                        </div>
                        <div class="text-end">
                            <div class="recent-order-price text-danger mb-1">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></div>
                            <span class="badge-soft-warning">Belum Lunas</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle text-success fs-1 d-block mb-2 opacity-50"></i>
                        <span style="font-size: 13px;">Tidak ada tagihan tertunda!</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-12">
        <div class="content-card">
            <h5 class="section-title mb-4"><i class="bi bi-trophy text-warning"></i> Produk Terlaris</h5>
            <div style="height: 350px;">
                <canvas id="produkChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
Chart.defaults.font.family = "'Plus Jakarta Sans', 'Segoe UI', sans-serif";
Chart.defaults.font.size = 12;

document.addEventListener("DOMContentLoaded", () => {
    // PENJUALAN CHART
    const ctxPenjualan = document.getElementById('penjualanChart').getContext('2d');
    
    // Create Gradient
    let gradientPenjualan = ctxPenjualan.createLinearGradient(0, 0, 0, 300);
    gradientPenjualan.addColorStop(0, 'rgba(212,175,55,0.4)');
    gradientPenjualan.addColorStop(1, 'rgba(212,175,55,0.0)');

    new Chart(ctxPenjualan, {
        type: 'line',
        data: {
            labels: <?= $bulan ?>,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: <?= $totalPenjualan ?>,
                borderColor: '#d4af37',
                backgroundColor: gradientPenjualan,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#d4af37',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12, cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { 
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: { callback: function(value) { return 'Rp ' + (value/1000).toLocaleString('id-ID') + 'k'; } }
                }
            }
        }
    });

    // PRODUK TERLARIS CHART
    const ctxProduk = document.getElementById('produkChart').getContext('2d');
    new Chart(ctxProduk, {
        type: 'bar',
        data: {
            labels: <?= $namaProduk ?>,
            datasets: [{
                label: 'Qty Terjual',
                data: <?= $qtyProduk ?>,
                backgroundColor: '#8B5A2B',
                hoverBackgroundColor: '#d4af37',
                borderRadius: 6,
                barThickness: 30
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: 'rgba(0,0,0,0.04)' } }
            }
        }
    });

    // STATUS PESANAN CHART
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: <?= $labelStatus ?>,
            datasets: [{
                data: <?= $jumlahStatus ?>,
                backgroundColor: ['#f59e0b', '#38bdf8', '#8B5A2B', '#10b981', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true, pointStyle: 'circle' }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
