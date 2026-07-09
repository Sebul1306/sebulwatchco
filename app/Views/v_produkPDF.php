<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Produk Sebul Watch Co.</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .judul-laporan { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f4f6f9; color: #8B5A2B; text-transform: uppercase; font-size: 11px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #777; font-style: italic; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
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

    <div class="judul-laporan">Katalog Produk</div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Produk</th>
                <th width="15%">Modal (HPP)</th>
                <th width="15%">Harga Jual</th>
                <th width="10%">Stok Fisik</th>
                <th width="20%">Foto Produk</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($product as $index => $produk): 
                $path = FCPATH . "NiceAdmin/assets/img/" . $produk["foto"];
                $base64 = "";
                if (!empty($produk["foto"]) && file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = "data:image/" . $type . ";base64," . base64_encode($data);
                }
            ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td><strong><?= esc($produk["nama"]) ?></strong></td>
                <td class="text-right">Rp <?= number_format($produk["harga_beli"], 0, ",", ".") ?></td>
                <td class="text-right">Rp <?= number_format($produk["harga"], 0, ",", ".") ?></td>
                <td class="text-center" style="font-weight: bold; color: <?= $produk['jumlah'] <= 10 ? 'red' : 'green' ?>"><?= $produk["jumlah"] ?></td>
                <td class="text-center">
                    <?php if (!empty($base64)): ?>
                        <img src="<?= $base64 ?>" height="40px" style="border-radius: 4px;">
                    <?php else: ?>
                        <span style="font-size: 10px; color: #999;">-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh Sistem Sebul Watch Co.<br>
        Waktu Cetak: <?= date("d F Y, H:i:s") ?> WIB
    </div>

</body>
</html>
