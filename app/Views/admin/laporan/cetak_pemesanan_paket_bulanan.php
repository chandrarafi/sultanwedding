<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            position: relative;
        }

        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
            height: auto;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
        }

        .info {
            margin-bottom: 15px;
        }

        .stats {
            margin-bottom: 20px;
            width: 100%;
        }

        .stats th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: left;
        }

        .stats td {
            padding: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .signature {
            margin-top: 60px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }

        .print-button:hover {
            background-color: #45a049;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <button class="print-button" onclick="window.print()">Cetak</button>

    <div class="header">
        <img src="<?= base_url('assets/images/logo.jpeg') ?>" alt="Logo" class="logo">
        <h1>LAPORAN PEMESANAN PAKET BULANAN</h1>
        <p>Sultan Wedding</p>
        <p>Periode: <?= $nama_bulan ?> <?= $tahun ?></p>
    </div>

    <div class="info">
        <table class="stats" border="1">
            <tr>
                <th>Total Pemesanan:</th>
                <td><?= number_format($laporan['total_pemesanan'], 0, ',', '.') ?></td>
                <th>Total Pendapatan:</th>
                <td>Rp <?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Status Lunas:</th>
                <td><?= number_format($laporan['status_count']['success'], 0, ',', '.') ?></td>
                <th>Status Dalam Proses:</th>
                <td><?= number_format($laporan['status_count']['pending'] + $laporan['status_count']['partial'], 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pemesanan</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Paket</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($laporan['data'])) : ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data pemesanan</td>
                </tr>
            <?php else : ?>
                <?php $no = 1;
                foreach ($laporan['data'] as $p) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $p['kdpemesananpaket'] ?></td>
                        <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                        <td><?= $p['namapelanggan'] ?></td>
                        <td><?= $p['namapaket'] ?></td>
                        <td class="text-right">Rp <?= number_format($p['hargapaket'], 0, ',', '.') ?></td>
                        <td><?= ucfirst($p['status']) ?></td>
                        <td><?= ucfirst($p['statuspembayaran'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total Pendapatan:</th>
                <th colspan="3">Rp <?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        <div class="signature">
            <p>Mengetahui,</p>
            <p class="signature">___________________</p>
            <p>Admin</p>
        </div>
    </div>

    <script>
        // Otomatis fokus ke tombol cetak saat halaman dimuat
        window.onload = function() {
            document.querySelector('.print-button').focus();
        };
    </script>
</body>

</html>