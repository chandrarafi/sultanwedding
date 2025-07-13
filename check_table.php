<?php
// File untuk memeriksa struktur tabel pembayaran
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sultanwedding';

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk memeriksa struktur tabel
$query = "DESCRIBE pembayaran";
$result = $conn->query($query);

// Tampilkan hasil
echo "Struktur tabel pembayaran:\n\n";

if ($result) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error: " . $conn->error;
}

// Periksa juga data dalam tabel
echo "\n\nContoh data dalam tabel pembayaran:\n\n";
$dataQuery = "SELECT * FROM pembayaran LIMIT 1";
$dataResult = $conn->query($dataQuery);

if ($dataResult && $dataResult->num_rows > 0) {
    print_r($dataResult->fetch_assoc());
} else {
    echo "Tidak ada data atau error: " . $conn->error;
}

// Tutup koneksi
$conn->close();
