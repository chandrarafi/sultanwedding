<?php

// Load CodeIgniter dependencies
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/Config/Constants.php';

// Create a simple application instance
$app = \CodeIgniter\Config\Services::codeigniter();
$app->initialize();

// Try-catch to handle potential exceptions
try {
    echo "Testing PembayaranModel...\n";

    // Get database connection
    $db = \Config\Database::connect();
    echo "Database connected!\n";

    // Create model instance
    $model = new \App\Models\PembayaranModel();
    echo "Model instantiated successfully\n";

    // Generate a payment code
    $paymentCode = $model->generatePaymentCode();
    echo "Generated payment code: $paymentCode\n";

    // Try to find a payment record
    $paymentRecords = $db->table('pembayaran')->limit(1)->get()->getResultArray();
    if (count($paymentRecords) > 0) {
        echo "Found existing payment record\n";
        echo "Record: " . json_encode($paymentRecords[0]) . "\n";
    } else {
        echo "No existing payment records found\n";

        // Try to create a test record
        echo "Creating test record...\n";
        $testData = [
            'kdpembayaran' => 'TEST-' . date('YmdHis'),
            'tgl' => date('Y-m-d'),
            'metodepembayaran' => 'test',
            'tipepembayaran' => 'dp',
            'jumlahbayar' => 0,
            'sisa' => 100000,
            'totalpembayaran' => 100000,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $result = $model->insert($testData);
            echo "Test record created with ID: $result\n";

            // Clean up
            $model->delete($testData['kdpembayaran']);
            echo "Test record deleted\n";
        } catch (\Exception $e) {
            echo "Failed to create test record: " . $e->getMessage() . "\n";
        }
    }

    echo "PembayaranModel test completed successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
