<?php
// Load the helper file
require_once 'app/Helpers/image_helper.php';

// Check if the function exists
if (function_exists('get_barang_image_url')) {
    echo "Function get_barang_image_url exists!\n";
} else {
    echo "Function get_barang_image_url does NOT exist!\n";
}

// Test the function
echo "Test function call: " . get_barang_image_url('test.jpg', 1) . "\n";
