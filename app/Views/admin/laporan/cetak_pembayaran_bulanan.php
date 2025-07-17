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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .summary {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
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
        <h1>SULTAN WEDDING ORGANIZER</h1>
        <p>Jl. Raya Padang Panjang No. 123, Padang Panjang, Sumatera Barat</p>
        <p>Telp: (0752) 123456 | Email: info@sultanwedding.com</p>
        <h2><?= $title ?></h2>
        <p>Periode: <?= $nama_bulan ?> <?= $tahun ?></p>
    </div>

    <div class="summary">
        <h3>Ringkasan Laporan</h3>
        <p>Total Pembayaran: <?= count($laporan['data']) ?></p>
        <p>Total Pendapatan: Rp <?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Pembayaran</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Pelanggan</th>
                <th width="10%">Jenis</th>
                <th width="10%">Tipe</th>
                <th width="15%">Jumlah</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            if (!empty($laporan['data'])) :
                foreach ($laporan['data'] as $item) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $item['kdpembayaran'] ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($item['tgl'])) ?></td>
                        <td><?= $item['namapelanggan'] ?? 'Pelanggan Walk-in' ?></td>
                        <td class="text-center"><?= $item['jenis_pemesanan'] ?></td>
                        <td class="text-center">
                            <?php if ($item['tipepembayaran'] == 'dp'): ?>
                                DP
                            <?php elseif ($item['tipepembayaran'] == 'dp2'): ?>
                                H-1
                            <?php elseif ($item['tipepembayaran'] == 'lunas'): ?>
                                Pelunasan
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="text-right">Rp <?= number_format($item['jumlahbayar'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <?php if ($item['status'] == 'success' || $item['status'] == 'confirmed'): ?>
                                Dikonfirmasi
                            <?php elseif ($item['status'] == 'partial'): ?>
                                Sebagian
                            <?php elseif ($item['status'] == 'pending'): ?>
                                Pending
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach;
            else : ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data pembayaran</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="table-primary">
                <th colspan="6" class="text-right">Total Pendapatan:</th>
                <th colspan="2">Rp <?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        <p style="margin-top: 50px;">
            (________________________)<br>
            Admin
        </p>
    </div>

    <script>
        // Otomatis fokus ke tombol cetak saat halaman dimuat
        window.onload = function() {
            document.querySelector('.print-button').focus();
        };
    </script>
</body>

</html>