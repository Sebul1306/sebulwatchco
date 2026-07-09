<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <table>
        <tr><th colspan="6" style="text-align: center; font-size: 20px; font-weight: bold; color: #8B5A2B;">SEBUL WATCH CO.</th></tr>
        <tr><th colspan="6" style="text-align: center; font-size: 12px;">Jalan Horologi, Grogol, Jakarta Barat | Telp: (021) 555-1234</th></tr>
        <tr><th colspan="6" style="text-align: center; font-size: 12px; border-bottom: 2px solid #000;">Email: hasbulwafi1306@gmail.com | Website: www.sebulwatch.co</th></tr>
        <tr><th colspan="6" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px 0;">LAPORAN PENDAPATAN</th></tr>
        <tr><th colspan="6" style="text-align: center; font-size: 12px; font-weight: normal; padding-bottom: 15px;">Periode: <?= date('d M Y', strtotime($tanggal_awal)) ?> s/d <?= date('d M Y', strtotime($tanggal_akhir)) ?></th></tr>
    </table>
    <table class="data-table" border="1" cellpadding="5">
        <thead><tr style="background-color: #8B5A2B; color: #ffffff;"><th>No</th><th>Pelanggan</th><th>Alamat</th><th>Total Belanja</th><th>Status</th><th>Waktu Transaksi</th></tr></thead>
        <tbody>
            <?php foreach ($laporan as $index => $item): ?>
            <tr><?php
            $stLabels = [0 => "Menunggu Pembayaran", 1 => "Sudah Dibayar", 2 => "Sedang Dikirim", 3 => "Sudah Selesai"];
            $statusText = $stLabels[$item["status"]] ?? "Unknown";
        ?><td class="text-center"><?= $index + 1 ?></td><td><?= esc($item["username"]) ?></td><td><?= esc($item["alamat"]) ?></td><td class="text-right">Rp <?= number_format($item["total_harga"] ?? 0, 0, ",", ".") ?></td><td class="text-center"><?= $statusText ?></td><td class="text-center"><?= date("d M Y H:i", strtotime($item["created_at"])) ?></td></tr>
            <?php endforeach; ?>
            
        <?php $total = 0; foreach($laporan as $i) $total += $i["total_harga"]; ?>
        <tr><td colspan="5" class="text-right"><strong>TOTAL PENDAPATAN</strong></td><td class="text-right text-success"><strong>Rp <?= number_format($total, 0, ",", ".") ?></strong></td></tr>
        </tbody>
    </table>
    <br><p style="font-style: italic; font-size: 10px;">Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <?= date("d F Y, H:i:s") ?></p>
</body>
</html>