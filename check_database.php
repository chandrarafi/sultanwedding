<?php

// Fungsi untuk log output
function log_output($message)
{
    echo $message;
    file_put_contents('db_check.log', $message, FILE_APPEND);
}

// Konfigurasi database langsung
$hostname = "localhost";
$username = "root";
$password = "";
$database = "sultanwedding"; // Sesuaikan dengan nama database

// Koneksi ke database
$conn = new mysqli($hostname, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

log_output("Koneksi ke database berhasil!\n\n");

// 1. Cek struktur tabel pembayaran
log_output("=== STRUKTUR TABEL PEMBAYARAN ===\n");
$result = $conn->query("DESCRIBE pembayaran");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        log_output($row['Field'] . " - " . $row['Type'] . " - " . ($row['Key'] == 'PRI' ? 'PRIMARY KEY' : '') . "\n");
    }
} else {
    log_output("Gagal mendapatkan struktur tabel: " . $conn->error . "\n");
}

log_output("\n");

// 2. Cek jumlah record di tabel pembayaran
$count = $conn->query("SELECT COUNT(*) as total FROM pembayaran");
$countResult = $count->fetch_assoc();
log_output("Jumlah record di tabel pembayaran: " . $countResult['total'] . "\n\n");

// 3. Coba insert record baru
log_output("=== MENCOBA INSERT RECORD BARU ===\n");
$kdpembayaran = 'TEST-' . date('YmdHis');
$tgl = date('Y-m-d');
$now = date('Y-m-d H:i:s');

$sql = "INSERT INTO pembayaran (
    kdpembayaran, tgl, metodepembayaran, tipepembayaran, 
    jumlahbayar, sisa, totalpembayaran, status, created_at, updated_at
) VALUES (
    '$kdpembayaran', '$tgl', 'test', 'dp',
    0, 100000, 100000, 'pending', '$now', '$now'
)";

if ($conn->query($sql) === TRUE) {
    log_output("Record berhasil dibuat dengan ID: $kdpembayaran\n");

    // Hapus record test
    $conn->query("DELETE FROM pembayaran WHERE kdpembayaran = '$kdpembayaran'");
    log_output("Record test berhasil dihapus\n");
} else {
    log_output("Gagal membuat record: " . $conn->error . "\n");
}

// 4. Periksa apakah ada kolom yang kurang di tabel pembayaran
log_output("\n=== CEK KOLOM YANG DIPERLUKAN ===\n");
$requiredColumns = [
    'dp_confirmed',
    'dp_confirmed_at',
    'dp_confirmed_by',
    'h1_paid',
    'h1_confirmed',
    'h1_confirmed_at',
    'h1_confirmed_by',
    'full_paid',
    'full_confirmed',
    'full_confirmed_at',
    'full_confirmed_by'
];

foreach ($requiredColumns as $column) {
    $checkColumn = $conn->query("SHOW COLUMNS FROM pembayaran LIKE '$column'");
    if ($checkColumn->num_rows == 0) {
        log_output("Kolom '$column' tidak ada dalam tabel pembayaran.\n");
    } else {
        log_output("Kolom '$column' ada dalam tabel pembayaran.\n");
    }
}

// Tutup koneksi
$conn->close();

log_output("\nSelesai!");
