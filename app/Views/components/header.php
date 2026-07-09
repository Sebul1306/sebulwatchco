<!-- ======= Header ======= -->
<?php
$uri = uri_string();
$segments = explode('/', $uri);
$pageTitle = 'Dashboard';

if ($uri == '') {
    $pageTitle = session()->get('role') == 'admin' ? 'Dashboard Admin' : 'Beranda';
} elseif (isset($segments[0])) {
    switch ($segments[0]) {
        case 'dashboard':
            $pageTitle = 'Dashboard Admin';
            break;
        case 'produk':
            $pageTitle = 'Katalog Produk';
            break;
        case 'keranjang':
            $pageTitle = 'Keranjang Belanja';
            break;
        case 'ongkir':
            $pageTitle = 'Cek Ongkir';
            break;
        case 'penjualan':
            $pageTitle = 'Data Penjualan';
            break;
        case 'laporan':
            $sub = isset($segments[1]) ? $segments[1] : '';
            switch($sub) {
                case 'pendapatan': $pageTitle = 'Laporan Pendapatan'; break;
                case 'produk-terlaris': $pageTitle = 'Laporan Produk Terlaris'; break;
                case 'piutang': $pageTitle = 'Laporan Piutang'; break;
                case 'arus-kas': $pageTitle = 'Laporan Arus Kas'; break;
                case 'laba-rugi': $pageTitle = 'Laporan Laba Rugi'; break;
                case 'hutang': $pageTitle = 'Laporan Hutang'; break;
                default: $pageTitle = 'Laporan Keuangan';
            }
            break;
        case 'settings':
            $pageTitle = 'Pengaturan Sistem';
            break;
        case 'profile':
            $pageTitle = 'Profile Pelanggan';
            break;
        case 'beban':
            $pageTitle = 'Pengeluaran Operasional';
            break;
    }
}

$formatter = new \IntlDateFormatter('id_ID', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
$tgl_lengkap = $formatter->format(new \DateTime());
?>
<header class="topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <!-- Tombol toggle sidebar (mobile) -->
        <button class="btn d-lg-none" id="sidebarToggle" onclick="document.getElementById('sidebar').classList.toggle('show')" style="background:rgba(255,255,255,0.8); border:1px solid #e2e8f0; border-radius:12px; width:44px; height:44px;">
            <i class="bi bi-list fs-5"></i>
        </button>

        <!-- Judul halaman -->
        <div>
            <h4 class="page-title mb-0 fw-bold" style="font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; font-size: 22px;"><?= $pageTitle ?></h4>
            <small class="text-muted" style="font-size:12px; font-weight: 500;">
                Semua fokus <?= strtolower($pageTitle) ?> ada di sini.
            </small>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Date chip -->
        <span class="date-chip d-none d-md-flex align-items-center justify-content-center" style="height: 46px; padding: 0 16px; background:rgba(220,38,38,0.1); color:#dc2626; border:1px solid rgba(220,38,38,0.25); border-radius: 14px; font-weight: 700; font-size: 13px;">
            <i class="bi bi-calendar3 me-2" style="font-size: 14px;"></i><?= $tgl_lengkap ?>
        </span>

        <!-- Notifikasi Pesanan -->
        <?php if(session()->get('role') == 'admin'): ?>
        <?php 
            $db = \Config\Database::connect();
            $pendingCount = $db->table('transaction')
                               ->where('status', 0)
                               ->where('bukti_pembayaran !=', '')
                               ->countAllResults();
        ?>
        <a href="<?= base_url('penjualan') ?>" class="btn d-inline-flex align-items-center justify-content-center position-relative"
                style="height:46px;background:rgba(59,130,246,.1);color:#2563eb;border:1px solid rgba(59,130,246,.25);border-radius:14px;padding:0 16px;font-weight:700;text-decoration:none; transition: all 0.2s;"
                title="Pesanan Menunggu Validasi">
            <i class="bi bi-bell-fill <?= $pendingCount > 0 ? 'text-danger' : '' ?> me-1" style="font-size:16px"></i>
            <span class="d-none d-md-inline" style="font-size:13px">Notifikasi</span>
            <?php if($pendingCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 11px; padding: 4px 6px;">
                <?= $pendingCount ?>
            </span>
            <?php endif; ?>
        </a>
        <?php endif; ?>

        <!-- Dropdown user -->
        <div class="dropdown">
            <button class="btn dropdown-toggle d-flex align-items-center gap-2"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    style="border: 1px solid rgba(0,0,0,0.08); border-radius: 14px; padding: 4px 8px; background: #fff; height: 46px;">
                <span style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #d4af37, #8b5a2b); display: grid; place-items: center; color: #fff; font-weight: 800; font-size: 15px;">
                    <?= strtoupper(substr(session()->get('username') ?? 'U', 0, 1)) ?>
                </span>
                <div class="d-none d-md-block text-start me-2">
                    <div style="font-size:13px;font-weight:700;line-height:1.2;color:#0f172a">
                        <?= esc(session()->get('username') ?? 'Guest') ?>
                    </div>
                    <div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;">
                        <?= esc(session()->get('role') ?? 'USER') ?>
                    </div>
                </div>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 16px; padding: 8px;">
                <li>
                    <div class="px-3 py-2" style="border-bottom:1px solid #f1f5f9; margin-bottom: 8px;">
                        <div style="font-weight:700;font-size:14px;color:#0f172a">
                            <?= esc(session()->get('username') ?? 'Guest') ?>
                        </div>
                        <div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;">
                            <?= esc(session()->get('role') ?? 'USER') ?>
                        </div>
                    </div>
                </li>
                <li>
                    <?php if(session()->get('role') == 'admin'): ?>
                    <a class="dropdown-item d-flex align-items-center rounded-3 mb-1" href="<?= base_url('settings') ?>" style="padding: 10px 15px; font-weight: 600; font-size: 13px;">
                        <i class="bi bi-gear me-3 fs-5" style="color:#8b5a2b"></i>Pengaturan
                    </a>
                    <?php else: ?>
                    <a class="dropdown-item d-flex align-items-center rounded-3 mb-1" href="<?= base_url('profile') ?>" style="padding: 10px 15px; font-weight: 600; font-size: 13px;">
                        <i class="bi bi-person me-3 fs-5" style="color:#8b5a2b"></i>Profil Saya
                    </a>
                    <?php endif; ?>
                </li>
                <li>
                    <a class="dropdown-item text-danger d-flex align-items-center rounded-3" href="<?= base_url('logout') ?>" style="padding: 10px 15px; font-weight: 600; font-size: 13px;">
                        <i class="bi bi-box-arrow-right me-3 fs-5"></i>Keluar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
