<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $transaction['id'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #e2e8f0;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }
        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header-left {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        .header-left-text h1 {
            margin: 0;
            font-size: 28px;
            color: #3b6ef8;
            letter-spacing: -0.5px;
        }
        .header-left-text p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 14px;
        }
        .logo-img {
            width: 100px;
            height: auto;
            margin-top: 5px;
        }
        .header-right {
            text-align: right;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .invoice-number {
            font-size: 16px;
            color: #64748b;
            margin: 0;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-block {
            flex: 1;
        }
        .info-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .info-content {
            font-size: 14px;
            line-height: 1.5;
            color: #334155;
        }
        .info-content strong {
            color: #0f172a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .totals {
            width: 50%;
            margin-left: auto;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            font-size: 14px;
            color: #475569;
        }
        .total-row.grand-total {
            background-color: #f8fafc;
            border-radius: 6px;
            font-weight: 700;
            color: #0f172a;
            font-size: 18px;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid #e2e8f0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .invoice-wrapper {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .print-btn-container {
                display: none !important;
            }
        }
        
        .print-btn-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-btn {
            background: #3b6ef8;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(59, 110, 248, 0.3);
            transition: all 0.2s;
            font-family: inherit;
        }
        .print-btn:hover {
            background: #2c38a1;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()"><i class="bi bi-printer"></i> Cetak PDF</button>
    </div>
    
    <div class="invoice-wrapper">
        <div class="header">
            <div class="header-left">
                <img src="<?= base_url('NiceAdmin/assets/img/logo51.png') ?>" alt="Logo" class="logo-img">
                <div class="header-left-text">
                    <h1>SEBUL WATCH CO.</h1>
                    <p><?= esc($store_address['name'] ?? 'Gegerkalong, Sukasari, Kota Bandung (Pusat)') ?></p>
                    <p>Email: admin@sebulwatch.com | Telp: 0812-3456-7890</p>
                </div>
            </div>
            <div class="header-right">
                <h2 class="invoice-title">INVOICE</h2>
                <p class="invoice-number">#<?= str_pad($transaction['id'], 6, '0', STR_PAD_LEFT) ?></p>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-block">
                <div class="info-title">Ditagihkan Kepada:</div>
                <div class="info-content">
                    <strong><?= esc($username) ?></strong><br>
                    <?= esc($transaction['alamat']) ?>
                </div>
            </div>
            <div class="info-block" style="text-align: right;">
                <div class="info-title">Detail Pesanan:</div>
                <div class="info-content">
                    Tanggal: <strong><?= date('d M Y, H:i', strtotime($transaction['created_at'])) ?> WIB</strong><br>
                    <?php
                        $statusMap = [
                            0 => 'Menunggu Pembayaran',
                            1 => 'Sudah Dibayar',
                            2 => 'Sedang Dikirim',
                            3 => 'Selesai',
                            4 => 'Dibatalkan'
                        ];
                        $statusText = $statusMap[$transaction['status']] ?? 'Tidak Diketahui';
                    ?>
                    Status: <strong><?= $statusText ?></strong>
                </div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 45%">Deskripsi Produk</th>
                    <th style="width: 15%" class="text-center">Kuantitas</th>
                    <th style="width: 15%" class="text-right">Harga</th>
                    <th style="width: 20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $subtotal = 0;
                $no = 1;
                foreach($details as $d): 
                    $subtotal += $d['subtotal_harga'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= esc($d['nama'] ?? 'Produk Dihapus') ?></strong></td>
                    <td class="text-center"><?= $d['jumlah'] ?></td>
                    <td class="text-right">Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($d['subtotal_harga'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>
            <div class="total-row">
                <span>Ongkos Kirim</span>
                <span>Rp <?= number_format($transaction['ongkir'], 0, ',', '.') ?></span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL PEMBAYARAN</span>
                <span>Rp <?= number_format($transaction['total_harga'], 0, ',', '.') ?></span>
            </div>
        </div>
        
        <div class="footer">
            <p>Terima kasih atas pesanan Anda! Jika ada pertanyaan, silakan hubungi kami.</p>
            <p>&copy; <?= date('Y') ?> Sebul Watch Co.. Hak Cipta Dilindungi.</p>
        </div>
    </div>
    
    <script>
        // Cetak otomatis saat load (bisa diaktifkan jika perlu)
        // window.onload = function() { window.print(); };
    </script>
</body>
</html>
