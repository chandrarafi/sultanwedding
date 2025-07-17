<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
        }

        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-box {
            width: 48%;
        }

        .info-box h3 {
            margin: 0 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .total-section {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 30%;
            text-align: center;
        }

        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SULTAN WEDDING ORGANIZER</h1>
        <p>Jl. Raya Padang Panjang No. 123, Kota Padang Panjang</p>
        <p>Telp: (0752) 123456 | Email: info@sultanwedding.com</p>
        <h2>FAKTUR PEMESANAN PAKET</h2>
        <p>No. Faktur: <?= $pemesanan['kdpemesananpaket'] ?></p>
    </div>

    <div class="info-container">
        <div class="info-box">
            <h3>Informasi Pelanggan</h3>
            <p>Nama: <?= isset($pemesanan['namapelanggan']) ? $pemesanan['namapelanggan'] : 'Pelanggan Walk-in' ?></p>
            <p>Alamat Acara: <?= $pemesanan['alamatpesanan'] ?></p>
        </div>
        <div class="info-box">
            <h3>Informasi Pemesanan</h3>
            <p>Tanggal Acara: <?= date('d/m/Y', strtotime($pemesanan['tgl'])) ?></p>
            <p>Jumlah Hari: <?= $pemesanan['jumlahhari'] ?> hari</p>
            <p>Luas Lokasi: <?= $pemesanan['luaslokasi'] ?></p>
            <p>Status: <?= ucfirst($pemesanan['status']) ?></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Paket</th>
                <th>Harga Paket</th>
                <th>Jumlah Hari</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $pemesanan['namapaket'] ?></td>
                <td>Rp <?= number_format($pemesanan['hargapaket'], 0, ',', '.') ?></td>
                <td><?= $pemesanan['jumlahhari'] ?> hari</td>
                <td>Rp <?= number_format($pemesanan['hargapaket'], 0, ',', '.') ?></td>
            </tr>
            <?php if ($pemesanan['jumlahhari'] > 4) : ?>
                <tr>
                    <td colspan="3">Biaya Tambahan (10% dari harga paket) untuk jumlah hari > 4</td>
                    <td>Rp <?= number_format($pemesanan['hargapaket'] * 0.1, 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="total-section">Total</th>
                <th>Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="info-container">
        <div class="info-box">
            <h3>Informasi Pembayaran</h3>
            <p>Status Pembayaran:
                <?php
                $paymentStatus = isset($pemesanan['statuspembayaran']) ? $pemesanan['statuspembayaran'] : 'pending';
                $paymentText = 'Menunggu Pembayaran';

                // First check if full payment is confirmed
                if (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1) {
                    $paymentText = 'Lunas';
                }
                // Then check if full payment is pending confirmation
                else if ((isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') ||
                    (isset($pemesanan['full_paid']) && $pemesanan['full_paid'] == 1)
                ) {
                    $paymentText = 'Pelunasan Menunggu Konfirmasi';
                }
                // Then check other payment statuses
                else {
                    switch ($paymentStatus) {
                        case 'pending':
                            $paymentText = 'Menunggu Pembayaran';
                            break;
                        case 'partial':
                            if (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1) {
                                $paymentText = 'Pembayaran H-1 Dikonfirmasi';
                            } else if (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1) {
                                $paymentText = 'Pembayaran H-1 Menunggu Konfirmasi';
                            } else {
                                $paymentText = 'DP Dibayar';
                            }
                            break;
                        case 'success':
                            $paymentText = 'Lunas';
                            break;
                        case 'failed':
                        case 'rejected':
                            $paymentText = 'Ditolak';
                            break;
                    }
                }
                echo $paymentText;
                ?>
            </p>
            <p>Metode Pembayaran: <?= isset($pemesanan['metodepembayaran']) ? ucfirst($pemesanan['metodepembayaran']) : 'Belum ada' ?></p>
            <p>Total Pembayaran: Rp <?= isset($pemesanan['totalpembayaran']) ? number_format($pemesanan['totalpembayaran'], 0, ',', '.') : '0' ?></p>
            <p>Sisa Pembayaran: Rp <?= isset($pemesanan['sisa']) ? number_format($pemesanan['sisa'], 0, ',', '.') : number_format($pemesanan['grandtotal'], 0, ',', '.') ?></p>
        </div>
        <div class="info-box">
            <h3>Catatan</h3>
            <p>1. Pembayaran DP sebesar 10% dari total harga paket.</p>
            <p>2. Pembayaran H-1 sebesar 10% dari total harga paket.</p>
            <p>3. Pelunasan sebesar 80% dari total harga paket.</p>
            <p>4. Pembatalan pemesanan akan dikenakan biaya sesuai ketentuan.</p>
        </div>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>Penerima</p>
            <div class="signature-line"></div>
            <p>( ........................... )</p>
        </div>
        <div class="signature-box">
            <p>Hormat Kami,</p>
            <div class="signature-line"></div>
            <p>( ........................... )</p>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih telah mempercayakan acara pernikahan Anda kepada Sultan Wedding Organizer</p>
        <p>Dokumen ini dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
    </div>
</body>

</html>