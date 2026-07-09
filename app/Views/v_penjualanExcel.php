<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

    <!-- Kop Surat untuk Excel (menggunakan sel yang digabung) -->
    <table>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 20px; font-weight: bold; color: #8B5A2B;">SEBUL WATCH CO.</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 12px;">Jalan Horologi, Grogol, Jakarta Barat | Telp: (021) 555-1234</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 12px; border-bottom: 2px solid #000;">Email: hasbulwafi1306@gmail.com | Website: www.sebulwatch.co</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px 0;">LAPORAN DATA PENJUALAN (PESANAN)</th>
        </tr>
    </table>

    <table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #8B5A2B; color: #ffffff;">
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
                <td align="center"><?= $index + 1 ?></td>
                <td align="center">#<?= $trx["id"] ?></td>
                <td align="center"><?= date('d M Y', strtotime($trx['created_at'])) ?></td>
                <td align="center"><?= esc($trx["username"]) ?></td>
                <td align="right">
                    Rp <?= number_format($trx["total_harga"] ?? 0, 0, ',', '.') ?>
                    <?php if(!empty($trx['layanan'])): ?><br>(<?= esc($trx['layanan']) ?>)<?php endif; ?>
                </td>
                <td><?= esc($trx["alamat"]) ?></td>
                <td align="center"><?= $statusText ?></td>
            </tr>
            <?php 
                endforeach; 
            else:
            ?>
            <tr>
                <td colspan="7" align="center">Tidak ada data penjualan</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <p style="font-style: italic; font-size: 10px;">Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <?= date("d F Y, H:i:s") ?></p>

</body>
</html>
