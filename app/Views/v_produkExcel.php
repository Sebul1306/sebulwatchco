<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

    <!-- Kop Surat untuk Excel (menggunakan sel yang digabung) -->
    <table>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 20px; font-weight: bold; color: #8B5A2B;">SEBUL WATCH CO.</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 12px;">Jalan Horologi, Grogol, Jakarta Barat | Telp: (021) 555-1234</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 12px; border-bottom: 2px solid #000;">Email: hasbulwafi1306@gmail.com | Website: www.sebulwatch.co</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px 0;">KATALOG PRODUK INTERNAL GUDANG</th>
        </tr>
    </table>

    <table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #8B5A2B; color: #ffffff;">
                <th width="5%">No</th>
                <th width="40%">Nama Produk</th>
                <th width="20%">Modal (HPP)</th>
                <th width="20%">Harga Jual</th>
                <th width="15%">Stok Fisik</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($product as $index => $produk): ?>
            <tr>
                <td align="center"><?= $index + 1 ?></td>
                <td><?= esc($produk["nama"]) ?></td>
                <td align="right">Rp <?= number_format($produk["harga_beli"] ?? 0, 0, ',', '.') ?></td>
                <td align="right">Rp <?= number_format($produk["harga"] ?? 0, 0, ',', '.') ?></td>
                <td align="center"><?= $produk["jumlah"] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <p style="font-style: italic; font-size: 10px;">Di-generate secara otomatis oleh Sistem Sebul Watch Co. pada <?= date("d F Y, H:i:s") ?></p>

</body>
</html>
