<h2>Invoice Pembelian</h2>

<table border="1" width="100%" cellpadding="8" cellspacing="0">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga Satuan</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $total = 0;
        foreach ($cart as $item):
            $subtotal = $item['harga'] * $item['qty'];
            $total += $subtotal;
        ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $item["nama"] ?></td>
                <td align="right"><?= "Rp " . number_format($item["harga"], 0, ",", ".") ?></td>
                <td align="center"><?= $item["qty"] ?></td>
                <td align="right"><?= "Rp " . number_format($subtotal, 0, ",", ".") ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" align="right">Total Belanja:</th>
            <th align="right">Rp <?= number_format($total, 0, ",", ".") ?></th>
        </tr>
    </tfoot>
</table>
<br>
<p>Terima kasih telah berbelanja di toko kami!</p>
<p style="font-size:12px; color:gray;">Dicetak pada <?= date("Y-m-d H:i:s") ?></p>
