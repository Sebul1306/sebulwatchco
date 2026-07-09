<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <table>
        <tr><th colspan="6" style="text-align: center; font-size: 20px; font-weight: bold; color: #8B5A2B;">SEBUL WATCH CO.</th></tr>
        <tr><th colspan="6" style="text-align: center; font-size: 12px;">Jalan Horologi, Grogol, Jakarta Barat | Telp: (021) 555-1234</th></tr>
        <tr><th colspan="6" style="text-align: center; font-size: 12px; border-bottom: 2px solid #000;">Email: hasbulwafi1306@gmail.com | Website: www.sebulwatch.co</th></tr>
        <tr><th colspan="6" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px 0;">LAPORAN PIUTANG PELANGGAN</th></tr>
    </table>
    <table class="data-table" border="1" cellpadding="5">
        <thead><tr style="background-color: #8B5A2B; color: #ffffff;"><th>No</th><th>Pelanggan</th><th>Invoice</th><th>Total Tagihan</th><th>Sudah Dibayar</th><th>Sisa Piutang</th></tr></thead>
        <tbody>
            <?php foreach ($piutang as $index => $item): 
                $dibayar = $item['total'] - $item['sisa'];
            ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td><?= esc($item["pelanggan"]) ?></td>
                <td class="text-center"><?= esc($item["invoice"]) ?></td>
                <td class="text-right">Rp <?= number_format($item["total"], 0, ",", ".") ?></td>
                <td class="text-right text-success">Rp <?= number_format($dibayar, 0, ",", ".") ?></td>
                <td class="text-right text-danger" style="font-weight: bold;">Rp <?= number_format($item["sisa"], 0, ",", ".") ?></td>
            </tr>
            <?php endforeach; ?>
            
        </tbody>
    </table>
    <br><p style="font-style: italic; font-size: 10px;">Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <?= date("d F Y, H:i:s") ?></p>
</body>
</html>