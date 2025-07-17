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
        <h2>FAKTUR PEMESANAN BARANG</h2>
        <p>No. Faktur: <?= $pemesanan['kdpemesananbarang'] ?></p>
    </div>

    <div class="info-container">
        <div class="info-box">
            <h3>Informasi Pelanggan</h3>
            <p>Nama: <?= isset($pemesanan['namapelanggan']) ? $pemesanan['namapelanggan'] : 'Pelanggan Walk-in' ?></p>
            <p>Alamat Pengiriman: <?= $pemesanan['alamatpesanan'] ?></p>
        </div>
        <div class="info-box">
            <h3>Informasi Pemesanan</h3>
            <p>Tanggal Pemesanan: <?= date('d/m/Y', strtotime($pemesanan['tgl'])) ?></p>
            <p>Lama Sewa: <?= $pemesanan['lamapemesanan'] ?> hari</p>
            <p>Status: <?= ucfirst($pemesanan['status']) ?></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Sewa/Hari</th>
                <th>Lama Sewa</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($detailPemesanan as $detail): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $detail['namabarang'] ?></td>
                    <td><?= $detail['jumlah'] ?> <?= $detail['satuan'] ?></td>
                    <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                    <td><?= $pemesanan['lamapemesanan'] ?> hari</td>
                    <td>Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="total-section">Total</th>
                <th>Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="info-container">
        <div class="info-box">
            <h3>Informasi Pembayaran</h3>
            <p>Status Pembayaran: <?= isset($pemesanan['statuspembayaran']) ? ucfirst($pemesanan['statuspembayaran']) : 'Belum ada' ?></p>
            <p>Metode Pembayaran: <?= isset($pemesanan['metodepembayaran']) ? ucfirst($pemesanan['metodepembayaran']) : 'Belum ada' ?></p>
            <p>Total Pembayaran: Rp <?= isset($pemesanan['totalpembayaran']) ? number_format($pemesanan['totalpembayaran'], 0, ',', '.') : '0' ?></p>
            <p>Sisa Pembayaran: Rp <?= isset($pemesanan['sisa']) ? number_format($pemesanan['sisa'], 0, ',', '.') : number_format($pemesanan['grandtotal'], 0, ',', '.') ?></p>
        </div>
        <div class="info-box">
            <h3>Catatan</h3>
            <p>1. Barang yang disewa harus dikembalikan dalam kondisi baik.</p>
            <p>2. Keterlambatan pengembalian akan dikenakan denda.</p>
            <p>3. Kerusakan/kehilangan barang menjadi tanggung jawab penyewa.</p>
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
        <p>Terima kasih telah mempercayakan kebutuhan Anda kepada Sultan Wedding Organizer</p>
        <p>Dokumen ini dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
    </div>
</body>

</html>