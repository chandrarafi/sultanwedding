<?php

// Set base path
$FCPATH = __DIR__ . '/';

// Load environment
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $FCPATH . 'vendor/autoload.php';
require $FCPATH . 'app/Config/Constants.php';

// Create CodeIgniter instance
$app = \CodeIgniter\Config\Services::codeigniter();
$app->initialize();

// Get database connection
$db = \Config\Database::connect();

// Get table structure
echo "=== PEMBAYARAN TABLE STRUCTURE ===\n";
$fields = $db->getFieldData('pembayaran');
foreach ($fields as $field) {
    echo $field->name . " - " . $field->type . " - " . ($field->primary_key ? "PK" : "") . " - " . ($field->nullable ? "NULL" : "NOT NULL") . "\n";
}

echo "\n=== TEST INSERT PEMBAYARAN ===\n";
try {
    $kdpembayaran = 'TEST-' . date('YmdHis');
    $result = $db->table('pembayaran')->insert([
        'kdpembayaran' => $kdpembayaran,
        'tgl' => date('Y-m-d'),
        'metodepembayaran' => 'test',
        'tipepembayaran' => 'test',
        'jumlahbayar' => 0,
        'sisa' => 0,
        'totalpembayaran' => 0,
        'status' => 'pending'
    ]);

    echo "Insert result: " . ($result ? "Success" : "Failed") . "\n";
    echo "Inserted ID: " . $kdpembayaran . "\n";

    // Cleanup
    $db->table('pembayaran')->where('kdpembayaran', $kdpembayaran)->delete();
    echo "Test record deleted\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
