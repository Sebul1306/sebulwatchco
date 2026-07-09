<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Penjualan - Sebul Watch Co.</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .judul-laporan { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #ddd; padding: 8px; }
        table.data-table th { background-color: #f4f6f9; color: #8B5A2B; text-transform: uppercase; font-size: 10px; }
        table.data-table tr:nth-child(even) { background-color: #f9f9f9; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #777; font-style: italic; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <?php
    $logoPath = FCPATH . "NiceAdmin/assets/img/logo51.png";
    $logoBase64 = "";
    if (file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        $logoBase64 = "data:image/" . $type . ";base64," . base64_encode($data);
    }
    ?>
    
    <table style="width: 100%; border-bottom: 3px solid #8B5A2B; padding-bottom: 10px; margin-bottom: 20px; border: none;">
        <tr>
            <td style="width: 20%; text-align: left; border: none; padding: 0;">
                <?php if ($logoBase64): ?>
                    <img src="<?= $logoBase64 ?>" width="150px">
                <?php endif; ?>
            </td>
            <td style="width: 60%; text-align: center; border: none; padding: 0;">
                <h1 style="margin: 0; color: #8B5A2B; font-size: 24px; text-transform: uppercase; letter-spacing: 2px;">Sebul Watch Co.</h1>
                <p style="margin: 5px 0 0; font-size: 11px; color: #555;">Jalan Horologi, Grogol, Jakarta Barat | Telp: (021) 555-1234</p>
                <p style="margin: 2px 0 0; font-size: 11px; color: #555;">Email: hasbulwafi1306@gmail.com | Website: www.sebulwatch.co</p>
            </td>
            <td style="width: 20%; border: none; padding: 0;"></td>
        </tr>
    </table>

    <div class="judul-laporan">Laporan Data Penjualan (Pesanan)</div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">ID TRX</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Username</th>
                <th width="20%">Total Harga</th>
                <th width="25%">Alamat</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($transactions)):
                foreach ($transactions as $index => $trx): 
                    $stLabels = [
                        0 => 'Menunggu Pembayaran',
                        1 => 'Sudah Dibayar',
                        2 => 'Sedang Dikirim',
                        3 => 'Sudah Selesai',
                    ];
                    $statusText = $stLabels[$trx['status']] ?? 'Unknown';
            ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td class="text-center">#<?= $trx["id"] ?></td>
                <td class="text-center"><?= date('d M Y', strtotime($trx['created_at'])) ?></td>
                <td class="text-center"><?= esc($trx["username"]) ?></td>
                <td class="text-right">
                    Rp <?= number_format($trx["total_harga"] ?? 0, 0, ',', '.') ?>
                    <?php if(!empty($trx['layanan'])): ?><br><small>(<?= esc($trx['layanan']) ?>)</small><?php endif; ?>
                </td>
                <td><?= esc($trx["alamat"]) ?></td>
                <td class="text-center"><?= $statusText ?></td>
            </tr>
            <?php 
                endforeach; 
            else:
            ?>
            <tr>
                <td colspan="7" class="text-center">Tidak ada data penjualan</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <br>
        Waktu Cetak: <?= date("d F Y, H:i:s") ?> WIB
    </div>

</body>
</html>
