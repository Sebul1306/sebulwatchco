<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hutang Dagang - Sebul Watch Co.</title>
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
        .text-danger { color: #dc3545; }
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
    <div class="judul-laporan">Laporan Hutang Dagang (Belum Lunas)</div>
    <table class="data-table" border="1" cellpadding="5">
        <thead><tr style="background-color: #8B5A2B; color: #ffffff;"><th>No</th><th>Tanggal</th><th>Supplier</th><th>Produk Restock</th><th>Keterangan</th><th>Total Tagihan</th></tr></thead>
        <tbody>
            <?php $totalSemua = 0; foreach ($hutang as $index => $item): 
                $totalSemua += $item['total_harga'];
            ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td class="text-center"><?= date('d/m/Y H:i', strtotime($item['tanggal'])) ?></td>
                <td><?= esc($item["nama_supplier"] ?? 'Tidak Diketahui') ?></td>
                <td><?= esc($item["nama_produk"]) ?> (<?= $item['jumlah_restock'] ?> pcs)</td>
                <td><?= esc($item["keterangan"]) ?></td>
                <td class="text-right text-danger" style="font-weight: bold;">Rp <?= number_format($item["total_harga"], 0, ",", ".") ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5" class="text-right" style="font-weight: bold; font-size: 12px; background-color: #fceea7;">TOTAL KESELURUHAN HUTANG:</td>
                <td class="text-right text-danger" style="font-weight: bold; font-size: 12px; background-color: #fceea7;">Rp <?= number_format($totalSemua, 0, ",", ".") ?></td>
            </tr>
        </tbody>
    </table>
    <div class="footer">
        Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <br>
        Waktu Cetak: <?= date("d F Y, H:i:s") ?> WIB
    </div>
</body>
</html>
