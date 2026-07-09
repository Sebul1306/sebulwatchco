<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <table>
        <tr><th colspan="4" style="text-align: center; font-size: 20px; font-weight: bold; color: #8B5A2B;">SEBUL WATCH CO.</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 12px;">Jalan Horologi, Grogol, Jakarta Barat | Telp: (021) 555-1234</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 12px; border-bottom: 2px solid #000;">Email: hasbulwafi1306@gmail.com | Website: www.sebulwatch.co</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px 0;">LAPORAN ARUS KAS</th></tr>
    </table>
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
    <br><p style="font-style: italic; font-size: 10px;">Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <?= date("d F Y, H:i:s") ?></p>
</body>
</html>