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
            width: 45%;
            text-align: center;
            float: right;
        }

        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #000;
        }

        .page-break {
            page-break-after: always;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SULTAN WEDDING ORGANIZER</h1>
        <p>Jl. Raya Padang Panjang No. 123, Kota Padang Panjang</p>
        <p>Telp: (0752) 123456 | Email: info@sultanwedding.com</p>
        <h2><?= $title ?></h2>
        <p>Tanggal Cetak: <?= $tanggal ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Barang</th>
                <th width="15%">Satuan</th>
                <th width="10%">Jumlah</th>
                <th width="20%">Harga Sewa</th>
                <th width="20%">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $totalNilai = 0;
            $totalBarang = 0;

            foreach ($barangs as $barang) :
                $nilaiBarang = $barang['jumlah'] * $barang['hargasewa'];
                $totalNilai += $nilaiBarang;
                $totalBarang += $barang['jumlah'];
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $barang['namabarang'] ?></td>
                    <td><?= $barang['satuan'] ?></td>
                    <td><?= $barang['jumlah'] ?></td>
                    <td>Rp <?= number_format($barang['hargasewa'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($nilaiBarang, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th><?= $totalBarang ?></th>
                <th></th>
                <th>Rp <?= number_format($totalNilai, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Jumlah Jenis Barang: <?= count($barangs) ?> | Total Stok: <?= $totalBarang ?> | Total Nilai: Rp <?= number_format($totalNilai, 0, ',', '.') ?></p>
    </div>

    <div class="signature-box">
        <p>Padang Panjang, <?= $tanggal ?></p>
        <p>Mengetahui,</p>
        <div class="signature-line"></div>
        <p>( _________________ )</p>
        <p>Manager</p>
    </div>
</body>

</html>