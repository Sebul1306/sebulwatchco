<?php
header("Content-Type: application/vnd.ms-excel");
$dateStr = date("d-m-Y");
header("Content-Disposition: attachment; filename=Laporan_Hutang_$dateStr.xls");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hutang Dagang</title>
</head>
<body>
    <h2 style="text-align: center;">Laporan Hutang Dagang (Belum Lunas) - Sebul Watch Co.</h2>
    <p style="text-align: center;">Waktu Cetak: <?= date("d F Y, H:i:s") ?> WIB</p>
    <table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #8B5A2B; color: #ffffff;">
                <th>No</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Produk Restock</th>
                <th>Keterangan</th>
                <th>Total Tagihan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php $totalSemua = 0; foreach ($hutang as $index => $item): 
                $totalSemua += $item['total_harga'];
            ?>
            <tr>
                <td style="text-align: center;"><?= $index + 1 ?></td>
                <td style="text-align: center;"><?= date('d/m/Y H:i', strtotime($item['tanggal'])) ?></td>
                <td><?= esc($item["nama_supplier"] ?? 'Tidak Diketahui') ?></td>
                <td><?= esc($item["nama_produk"]) ?> (<?= $item['jumlah_restock'] ?> pcs)</td>
                <td><?= esc($item["keterangan"]) ?></td>
                <td style="text-align: right;"><?= $item["total_harga"] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold; background-color: #fceea7;">TOTAL KESELURUHAN HUTANG:</td>
                <td style="text-align: right; font-weight: bold; background-color: #fceea7;"><?= $totalSemua ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
