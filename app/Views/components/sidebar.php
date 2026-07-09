<!-- ======= Sidebar ======= -->
<?php
$role = session()->get('role') ?? 'user';
$username = session()->get('username') ?? 'Guest';
$uri = uri_string();

// Helper active
function _ac($path, $uri) {
    if ($path == '' && $uri == '') return 'active';
    if ($path != '' && strpos($uri, $path) === 0) return 'active';
    return '';
}

$isLaporan = strpos($uri, 'laporan') === 0;
$colLaporan = $isLaporan ? '' : 'collapsed';
$openLaporan = $isLaporan ? 'show' : '';

// Ambil jumlah pesanan baru (status 0)
$pendingOrders = 0;
$needApproval = 0;
if ($role === 'admin') {
    $db = \Config\Database::connect();
    $pendingOrders = $db->table('transaction')->where('status', 0)->orWhere('status', 1)->countAllResults();
    $needApproval = $db->table('transaction')->where('status', 0)->where('bukti_pembayaran !=', null)->where('bukti_pembayaran !=', '')->countAllResults();
}
?>
<style>
.oc-sidebar{width:260px;min-width:260px;max-width:260px;height:calc(100vh - 24px);position:sticky;top:12px;margin:12px 0 12px 12px;overflow-y:auto;overflow-x:hidden;flex-shrink:0;z-index:1040;display:flex;flex-direction:column;font-family:'Plus Jakarta Sans','Inter',sans-serif;transition:transform .28s ease, width .28s ease, min-width .28s ease, max-width .28s ease;scrollbar-width:thin;scrollbar-color:rgba(212,175,55,.25) transparent;background:
    radial-gradient(ellipse 140% 45% at var(--orb1x, 30%) var(--orb1y, 10%), rgba(212,175,55,.22) 0%, transparent 65%),
    radial-gradient(ellipse 100% 40% at var(--orb2x, 70%) var(--orb2y, 85%), rgba(139,90,43,.18) 0%, transparent 60%),
    linear-gradient(180deg,#0a0805 0%,#120e09 50%,#17120c 100%);
box-shadow:6px 0 48px rgba(0,0,0,.55),inset -1px 0 0 rgba(255,255,255,.04);border-radius:24px}
@keyframes shimmerLine{0%{background-position:-250px 0}100%{background-position:250px 0}}
.oc-sidebar::-webkit-scrollbar{width:2px}
.oc-sidebar::-webkit-scrollbar-thumb{background:rgba(212,175,55,.3);border-radius:4px}
.oc-brand{display:flex;align-items:center;gap:12px;padding:26px 20px 22px;text-decoration:none !important;position:relative;flex-shrink:0;border-bottom:1px solid rgba(255,255,255,.05)}
.oc-brand::after{content:'';position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(212,175,55,.15),rgba(212,175,55,.5),rgba(139,90,43,.4),rgba(139,90,43,.1),transparent);background-size:250px 1px;animation:shimmerLine 4s linear infinite}
.oc-brand-img{width:46px;height:46px;border-radius:12px;object-fit:cover;flex-shrink:0;border:1px solid rgba(212,175,55,0.4);box-shadow:0 4px 15px rgba(0,0,0,.3);background:linear-gradient(135deg, #1e150b, #0a0805);padding:2px}
.oc-brand-text h5{font-size:.95rem;font-weight:800;color:#fceea7 !important;margin:0;white-space:nowrap;letter-spacing:0.5px;font-family: 'Plus Jakarta Sans', sans-serif;text-shadow:0 0 8px rgba(212,175,55,0.8),0 0 16px rgba(212,175,55,0.5)}
.oc-brand-text small{font-size:9.5px;color:#c4b58e;opacity:0.85;font-weight:600;display:block;margin-top:3px;letter-spacing:0.5px;text-transform:uppercase}
.oc-user-card{margin:16px 12px 10px;background:linear-gradient(135deg,rgba(255,255,255,.06),rgba(255,255,255,.02));border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:13px 15px;display:flex;align-items:center;gap:12px;flex-shrink:0;position:relative;overflow:hidden}
.oc-user-card::before{content:'';position:absolute;top:-40%;left:-20%;width:80%;height:80%;background:radial-gradient(circle,rgba(212,175,55,.12) 0%,transparent 70%);pointer-events:none}
.oc-user-avatar{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#d4af37,#8b5a2b);display:grid;place-items:center;font-size:15px;font-weight:800;color:#fff;flex-shrink:0;box-shadow:0 4px 14px rgba(212,175,55,.4)}
.oc-user-name{font-size:13px;font-weight:700;color:#f1f5f9;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.oc-role-badge{display:inline-block;font-size:9px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;padding:2px 8px;border-radius:20px;margin-top:4px}
.badge-admin{background:rgba(239,68,68,.18);color:#fca5a5;border:1px solid rgba(239,68,68,.25)}
.badge-user{background:rgba(34,197,94,.18);color:#86efac;border:1px solid rgba(34,197,94,.25)}
.oc-nav-label{font-size:9px;font-weight:800;letter-spacing:2.5px;text-transform:uppercase;color:rgba(255,255,255,.2);padding:20px 20px 8px;display:flex;align-items:center;gap:10px;flex-shrink:0}
.oc-nav-label::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,transparent,rgba(212,175,55,.4),rgba(139,90,43,.2),transparent);background-size:200px 1px;animation:shimmerLine 5s linear infinite}
.oc-nav-items{padding:0 8px;flex-shrink:0}
.oc-flat-link{display:flex;align-items:center;gap:11px;padding:10px 14px;font-size:13px;font-weight:600;color:rgba(255,255,255,.55);text-decoration:none !important;transition:all .2s;border-radius:13px;margin-bottom:2px;position:relative;overflow:hidden;letter-spacing:.1px}
.oc-flat-link::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,#d4af37,#8b5a2b);opacity:0;transition:opacity .2s;border-radius:13px}
.oc-flat-link i,.oc-flat-link span{position:relative;z-index:1}
.oc-flat-link i{font-size:.95rem;width:20px;text-align:center;flex-shrink:0;opacity:.7;transition:opacity .2s}
.oc-flat-link:hover,.oc-flat-link.active{color:#fff;transform:translateX(4px);box-shadow:0 8px 28px rgba(139,90,43,.3)}
.oc-flat-link:hover::before,.oc-flat-link.active::before{opacity:1}
.oc-flat-link.active::after{content:'';position:absolute;right:10px;top:50%;transform:translateY(-50%);width:6px;height:6px;border-radius:50%;background:#fde047;box-shadow:0 0 8px #fde047;z-index:1}
.oc-group-btn{width:100%;display:flex;align-items:center;gap:11px;padding:10px 14px;background:transparent;border:none;border-radius:13px;margin-bottom:2px;color:rgba(255,255,255,.55);font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;text-align:left;position:relative;overflow:hidden;letter-spacing:.1px}
.oc-group-btn::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,#d4af37,#8b5a2b);opacity:0;transition:opacity .2s;border-radius:13px}
.oc-group-btn i,.oc-group-btn span{position:relative;z-index:1}
.oc-group-btn i.group-icon{font-size:.95rem;width:20px;text-align:center;flex-shrink:0;opacity:.7;transition:opacity .2s}
.oc-group-btn .chevron{margin-left:auto;font-size:10px;position:relative;z-index:1;transition:transform .28s;opacity:.35}
.oc-group-btn:hover{color:#fff;transform:translateX(4px);box-shadow:0 8px 28px rgba(139,90,43,.3)}
.oc-group-btn:hover::before{opacity:1}
.oc-group-btn.collapsed .chevron{transform:rotate(0deg)}
.oc-group-btn:not(.collapsed){color:#fde047;background:linear-gradient(135deg,rgba(212,175,55,.15),rgba(139,90,43,.08));border:1px solid rgba(212,175,55,.2)}
.oc-group-btn:not(.collapsed)::before{opacity:0}
.oc-group-btn:not(.collapsed) .group-icon{opacity:1}
.oc-group-btn:not(.collapsed) .chevron{transform:rotate(90deg);opacity:1;color:#d4af37}
.oc-submenu{margin:2px 8px 4px 22px;padding:4px 6px 4px 12px;background:rgba(0,0,0,.18);border-left:2px solid rgba(212,175,55,.2);border-radius:0 12px 12px 0}
.oc-submenu a{display:flex;align-items:center;gap:10px;padding:8px 10px;font-size:12.5px;font-weight:500;color:rgba(255,255,255,.45);text-decoration:none !important;transition:all .18s;border-radius:10px;margin-bottom:1px;position:relative;overflow:hidden}
.oc-submenu a::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,#d4af37,#8b5a2b);opacity:0;transition:opacity .18s;border-radius:10px}
.oc-submenu a i,.oc-submenu a span{position:relative;z-index:1}
.oc-submenu a i{font-size:.85rem;width:16px;text-align:center;flex-shrink:0;opacity:.5;transition:opacity .18s}
.oc-submenu a:hover,.oc-submenu a.active{color:#fff;transform:translateX(3px);box-shadow:0 4px 16px rgba(139,90,43,.28);font-weight:600}
.oc-submenu a:hover::before,.oc-submenu a.active::before{opacity:1}
.oc-submenu a:hover i,.oc-submenu a.active i{opacity:1}
.oc-sidebar-spacer{flex:1;min-height:20px}
.oc-sidebar-footer{padding:14px 12px 22px;flex-shrink:0;position:relative}
.oc-sidebar-footer::before{content:'';position:absolute;top:0;left:12px;right:12px;height:1px;background:linear-gradient(90deg,transparent,rgba(239,68,68,.15),rgba(239,68,68,.4),rgba(212,175,55,.3),rgba(212,175,55,.1),transparent);background-size:250px 1px;animation:shimmerLine 6s linear infinite}
.oc-logout{display:flex;align-items:center;gap:10px;padding:11px 15px;border-radius:13px;background:linear-gradient(135deg,rgba(239,68,68,.08),rgba(220,38,38,.04));border:1px solid rgba(239,68,68,.15);color:rgba(252,165,165,.75);font-size:13px;font-weight:600;text-decoration:none !important;cursor:pointer;transition:all .22s;width:100%}
.oc-logout:hover{color:#fff;transform:translateX(3px);background:linear-gradient(135deg,rgba(239,68,68,.2),rgba(220,38,38,.12));box-shadow:0 8px 24px rgba(239,68,68,.2);border-color:rgba(239,68,68,.3)}
@media(max-width:991px){.oc-sidebar{position:fixed;left:0;top:0;margin:0;height:100vh;border-radius:0 24px 24px 0;transform:translateX(-100%);z-index:1050;width:280px;max-width:280px;}.oc-sidebar.show{transform:translateX(0)}}
</style>

<aside class="oc-sidebar" id="sidebar">

    <a href="<?= base_url() ?>" class="oc-brand">
        <img src="<?= base_url() ?>NiceAdmin/assets/img/logo51.png" alt="Logo" class="oc-brand-img">
        <div class="oc-brand-text">
            <h5>Sebul Watch Co.</h5>
            <small>E-Commerce System</small>
        </div>
    </a>

    <div class="oc-nav-label">Navigation</div>
    <div class="oc-nav-items">
        <a href="<?= base_url() ?>" class="oc-flat-link <?= _ac('', $uri) ?>">
            <i class="bi bi-house-door-fill"></i><span>Beranda</span>
        </a>
        <a href="<?= base_url('produk') ?>" class="oc-flat-link <?= _ac('produk', $uri) ?>">
            <i class="bi bi-shop"></i><span>Katalog Produk</span>
        </a>
        <a href="<?= base_url('keranjang') ?>" class="oc-flat-link <?= _ac('keranjang', $uri) ?>">
            <i class="bi bi-cart-check"></i><span>Keranjang Belanja</span>
        </a>
        <a href="<?= base_url('ongkir') ?>" class="oc-flat-link <?= _ac('ongkir', $uri) ?>">
            <i class="bi bi-truck"></i><span>Cek Ongkir</span>
        </a>
        <?php if ($role != 'admin'): ?>
        <a href="<?= base_url('profile') ?>" class="oc-flat-link <?= _ac('profile', $uri) ?>">
            <i class="bi bi-person-lines-fill"></i><span>Profil Saya</span>
        </a>
        <?php endif; ?>
    </div>

    <?php if ($role == "admin"): ?>
    <div class="oc-nav-label mt-2">Administrasi</div>
    <div class="oc-nav-items">
        <a href="<?= base_url('dashboard') ?>" class="oc-flat-link <?= _ac('dashboard', $uri) ?>">
            <i class="bi bi-speedometer2"></i><span>Dashboard Admin</span>
        </a>
        <a href="<?= base_url('penjualan') ?>" class="oc-flat-link <?= _ac('penjualan', $uri) ?>">
            <i class="bi bi-card-list"></i>
            <div class="d-flex justify-content-between align-items-center w-100 pe-2">
                <span>Data Penjualan</span>
                <?php if ($needApproval > 0): ?>
                <span class="badge text-dark rounded-pill" style="background:#fceea7; font-size: 9px; padding: 3px 6px; box-shadow: 0 0 10px rgba(252, 238, 167, 0.6);"><?= $needApproval ?> Perlu Cek</span>
                <?php elseif ($pendingOrders > 0): ?>
                <span class="badge bg-danger rounded-pill" style="font-size: 9px; padding: 3px 6px; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);"><?= $pendingOrders ?> Baru</span>
                <?php endif; ?>
            </div>
        </a>
        <a href="<?= base_url('supplier') ?>" class="oc-flat-link <?= _ac('supplier', $uri) ?>">
            <i class="bi bi-truck-flatbed"></i><span>Daftar Supplier</span>
        </a>
        <a href="<?= base_url('beban') ?>" class="oc-flat-link <?= _ac('beban', $uri) ?>">
            <i class="bi bi-wallet2"></i><span>Beban (Pengeluaran)</span>
        </a>

        <!-- Laporan Keuangan Group -->
        <button class="oc-group-btn <?= $colLaporan ?>" data-bs-toggle="collapse" data-bs-target="#navLaporan" aria-expanded="<?= $isLaporan ? 'true' : 'false' ?>">
            <i class="bi bi-journal-text group-icon"></i><span>Laporan Keuangan</span>
            <i class="bi bi-chevron-right chevron"></i>
        </button>
        <div class="collapse <?= $openLaporan ?>" id="navLaporan">
            <div class="oc-submenu">
                <a href="<?= base_url('laporan/pendapatan') ?>" class="<?= _ac('laporan/pendapatan', $uri) ?>">
                    <i class="bi bi-circle"></i><span>Pendapatan</span>
                </a>
                <a href="<?= base_url('laporan/produk-terlaris') ?>" class="<?= _ac('laporan/produk-terlaris', $uri) ?>">
                    <i class="bi bi-circle"></i><span>Produk Terlaris</span>
                </a>
                <a href="<?= base_url('laporan/piutang') ?>" class="<?= _ac('laporan/piutang', $uri) ?>">
                    <i class="bi bi-circle"></i><span>Piutang</span>
                </a>
                <a href="<?= base_url('laporan/hutang') ?>" class="<?= _ac('laporan/hutang', $uri) ?>">
                    <i class="bi bi-circle"></i><span>Hutang</span>
                </a>
                <a href="<?= base_url('laporan/arus-kas') ?>" class="<?= _ac('laporan/arus-kas', $uri) ?>">
                    <i class="bi bi-circle"></i><span>Arus Kas</span>
                </a>
                <a href="<?= base_url('laporan/laba-rugi') ?>" class="<?= _ac('laporan/laba-rugi', $uri) ?>">
                    <i class="bi bi-circle"></i><span>Laba Rugi</span>
                </a>
            </div>
        </div>

        <a href="<?= base_url('settings') ?>" class="oc-flat-link <?= _ac('settings', $uri) ?>">
            <i class="bi bi-gear-fill"></i><span>Pengaturan Sistem</span>
        </a>
    </div>
    <?php endif; ?>

    <div class="oc-sidebar-spacer"></div>

    <div class="oc-user-card">
        <div class="oc-user-avatar"><?= strtoupper(substr($username, 0, 1)) ?></div>
        <div style="min-width:0">
            <div class="oc-user-name"><?= esc($username) ?></div>
            <div class="badge-<?= strtolower($role) ?> oc-role-badge"><?= strtoupper($role) ?></div>
        </div>
    </div>

    <div class="oc-sidebar-footer">
        <a href="<?= base_url('logout') ?>" class="oc-logout">
            <i class="bi bi-box-arrow-right"></i><span>Keluar</span>
        </a>
    </div>

</aside>

<script>
// Sidebar Aurora Animation
(function(){
    var sb = document.getElementById('sidebar');
    if (!sb) return;
    var t = 0;
    function animate() {
        t += 0.008;
        var o1x = 30 + Math.sin(t * 0.7) * 25;
        var o1y = 15 + Math.sin(t * 0.5 + 1) * 35;
        var o2x = 65 + Math.cos(t * 0.6 + 2) * 20;
        var o2y = 80 + Math.cos(t * 0.4) * 30;
        sb.style.setProperty('--orb1x', o1x + '%');
        sb.style.setProperty('--orb1y', o1y + '%');
        sb.style.setProperty('--orb2x', o2x + '%');
        sb.style.setProperty('--orb2y', o2y + '%');
        requestAnimationFrame(animate);
    }
    requestAnimationFrame(animate);
})();
</script>
