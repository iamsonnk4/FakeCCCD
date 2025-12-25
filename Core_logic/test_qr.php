<?php
// Simple QR test script
header('Content-Type: text/plain');

// Test data
$testData = '087204004456|342176076|Test Name|23122004|Nam|Test Place|22092024|||';
$qrUrl = 'https://quickchart.io/qr?text=' . rawurlencode($testData) . '&light=0000';

echo "QR URL: " . $qrUrl . "\n\n";

// Test curl
$ch = curl_init($qrUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$qrImageData = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Curl Error: " . ($curlError ?: 'None') . "\n";
echo "Data received: " . (empty($qrImageData) ? 'No' : 'Yes (' . strlen($qrImageData) . ' bytes)') . "\n\n";

if ($qrImageData !== false && $httpCode == 200) {
    $qrImage = @imagecreatefromstring($qrImageData);
    if ($qrImage !== false) {
        echo "✓ QR image created successfully!\n";
        echo "Dimensions: " . imagesx($qrImage) . " x " . imagesy($qrImage) . "\n";
        imagedestroy($qrImage);
    } else {
        echo "✗ Failed to create image from data\n";
    }
} else {
    echo "✗ Failed to fetch QR code\n";
}
