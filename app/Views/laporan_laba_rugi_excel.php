<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <table>
        <tr><th colspan="4" style="text-align: center; font-size: 20px; font-weight: bold; color: #8B5A2B;">SEBUL WATCH CO.</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 12px;">Jalan Horologi, Grogol, Jakarta Barat | Telp: (021) 555-1234</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 12px; border-bottom: 2px solid #000;">Email: hasbulwafi1306@gmail.com | Website: www.sebulwatch.co</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px 0;">LAPORAN LABA RUGI</th></tr>
    </table>
        <table class="data-table" border="1" cellpadding="5">
        <tbody>
            <tr style="background-color: #e9ecef;"><td colspan="2"><strong>I. Pendapatan</strong></td></tr>
            <tr><td>Total Penjualan</td><td class="text-right">Rp <?= number_format($penjualan, 0, ',', '.') ?></td></tr>
            <tr><td>Harga Pokok Penjualan (HPP)</td><td class="text-right text-danger">(Rp <?= number_format($hpp, 0, ',', '.') ?>)</td></tr>
            <tr style="background-color: #f4f6f9;"><td><strong>Laba Kotor</strong></td><td class="text-right text-success"><strong>Rp <?= number_format($labaKotor, 0, ',', '.') ?></strong></td></tr>
            
            <tr style="background-color: #e9ecef;"><td colspan="2"><strong>II. Beban Operasional</strong></td></tr>
            <?php foreach($beban as $b): ?>
            <tr><td><?= esc($b['nama_beban']) ?></td><td class="text-right text-danger">(Rp <?= number_format($b['nominal'], 0, ',', '.') ?>)</td></tr>
            <?php endforeach; ?>
            <tr style="background-color: #f4f6f9;"><td><strong>Total Beban Operasional</strong></td><td class="text-right text-danger"><strong>(Rp <?= number_format($totalBeban, 0, ',', '.') ?>)</strong></td></tr>
            
            <tr style="background-color: #d1e7dd; color: #0f5132;"><td style="font-size: 14px;"><strong>LABA BERSIH</strong></td><td class="text-right" style="font-size: 14px;"><strong>Rp <?= number_format($labaBersih, 0, ',', '.') ?></strong></td></tr>
        </tbody>
    </table>
    <br><p style="font-style: italic; font-size: 10px;">Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <?= date("d F Y, H:i:s") ?></p>
</body>
</html>