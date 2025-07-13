<?php

/**
 * Image Helper Functions
 */

if (!function_exists('get_barang_image_url')) {
    /**
     * Get the URL for a barang image
     *
     * @param string|null $filename The image filename
     * @param int $kdbarang The barang ID (used for fallback image)
     * @return string The complete URL to the image
     */
    function get_barang_image_url($filename = null, $kdbarang = 0)
    {
        if (!empty($filename)) {
            return site_url("uploads/barang/{$filename}");
        } else {
            // Fallback to gallery image
            $imageIndex = ($kdbarang % 37) + 1;
            $imageIndex = str_pad($imageIndex, 2, '0', STR_PAD_LEFT);
            return base_url("assets/images/gallery/{$imageIndex}.png");
        }
    }
}

if (!function_exists('get_paket_image_url')) {
    /**
     * Get the URL for a paket image
     *
     * @param string|null $filename The image filename
     * @param int $kdpaket The paket ID (used for fallback image)
     * @return string The complete URL to the image
     */
    function get_paket_image_url($filename = null, $kdpaket = 0)
    {
        if (!empty($filename)) {
            return site_url("uploads/paket/{$filename}");
        } else {
            // Fallback to gallery image
            $imageIndex = ($kdpaket % 37) + 1;
            $imageIndex = str_pad($imageIndex, 2, '0', STR_PAD_LEFT);
            return base_url("assets/images/gallery/{$imageIndex}.png");
        }
    }
}
