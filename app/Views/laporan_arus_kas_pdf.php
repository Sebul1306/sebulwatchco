<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arus Kas - Sebul Watch Co.</title>
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
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
        .text-primary { color: #0d6efd; }
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
    <div class="judul-laporan">Laporan Arus Kas</div>
    <table class="data-table" border="1" cellpadding="5">
        <thead><tr style="background-color: #8B5A2B; color: #ffffff;"><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Jenis</th><th>Nominal</th></tr></thead>
        <tbody>
            <?php foreach ($arusKas as $index => $item): ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td class="text-center"><?= date("d M Y", strtotime($item["tanggal"])) ?></td>
                <td><?= esc($item["keterangan"]) ?></td>
                <?php $jColor = strtolower($item["jenis"]) == 'masuk' ? 'color: #198754;' : 'color: #dc3545;'; ?>
                <td class="text-center" style="<?= $jColor ?> font-weight: bold;"><?= ucfirst($item["jenis"]) ?></td>
                <td class="text-right">Rp <?= number_format($item["nominal"], 0, ",", ".") ?></td>
            </tr>
            <?php endforeach; ?>
            
        <tr><td colspan="4" class="text-right"><strong>Total Masuk</strong></td><td class="text-right text-success"><strong>Rp <?= number_format($totalMasuk, 0, ",", ".") ?></strong></td></tr>
        <tr><td colspan="4" class="text-right"><strong>Total Keluar</strong></td><td class="text-right text-danger"><strong>Rp <?= number_format($totalKeluar, 0, ",", ".") ?></strong></td></tr>
        <tr><td colspan="4" class="text-right"><strong>Saldo Akhir</strong></td><td class="text-right text-primary"><strong>Rp <?= number_format($saldoAkhir, 0, ",", ".") ?></strong></td></tr>
        </tbody>
    </table>
    <div class="footer">
        Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <br>
        Waktu Cetak: <?= date("d F Y, H:i:s") ?> WIB
    </div>
</body>
</html>