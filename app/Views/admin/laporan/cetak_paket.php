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
            position: relative;
        }

        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 100px;
            height: auto;
        }

        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
        }

        .header-content {
            margin-left: 120px;
            /* Space for logo */
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
        <img src="<?= base_url('assets/images/logo.jpeg') ?>" alt="Logo" class="logo">
        <div class="header-content">
            <h1>SULTAN WEDDING ORGANIZER</h1>
            <p>Jl. Raya Padang Panjang No. 123, Kota Padang Panjang</p>
            <p>Telp: (0752) 123456 | Email: info@sultanwedding.com</p>
            <h2><?= $title ?></h2>
            <p>Tanggal Cetak: <?= $tanggal ?></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Nama Paket</th>
                <th width="25%">Kategori</th>
                <th width="25%">Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            $total = 0;
            foreach ($pakets as $paket) :
                $total += $paket['harga'];
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $paket['namapaket'] ?></td>
                    <td><?= $paket['namakategori'] ?></td>
                    <td>Rp <?= number_format($paket['harga'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Jumlah Paket: <?= count($pakets) ?> paket</p>
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