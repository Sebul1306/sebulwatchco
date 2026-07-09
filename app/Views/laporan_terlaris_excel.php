<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <table>
        <tr><th colspan="4" style="text-align: center; font-size: 20px; font-weight: bold; color: #8B5A2B;">SEBUL WATCH CO.</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 12px;">Jalan Horologi, Grogol, Jakarta Barat | Telp: (021) 555-1234</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 12px; border-bottom: 2px solid #000;">Email: hasbulwafi1306@gmail.com | Website: www.sebulwatch.co</th></tr>
        <tr><th colspan="4" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px 0;">LAPORAN PRODUK TERLARIS</th></tr>
    </table>
    <table class="data-table" border="1" cellpadding="5">
        <thead><tr style="background-color: #8B5A2B; color: #ffffff;"><th>No</th><th>Nama Produk</th><th>Terjual (Qty)</th><th>Total Omzet (Rp)</th></tr></thead>
        <tbody>
            <?php foreach ($produk as $index => $item): ?>
            <tr><td class="text-center"><?= $index + 1 ?></td><td><?= esc($item["nama"]) ?></td><td class="text-center"><?= $item["qty"] ?></td><td class="text-right">Rp <?= number_format($item["omzet"], 0, ",", ".") ?></td></tr>
            <?php endforeach; ?>
            
        </tbody>
    </table>
    <br><p style="font-style: italic; font-size: 10px;">Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <?= date("d F Y, H:i:s") ?></p>
</body>
</html>