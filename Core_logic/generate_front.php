<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get POST data
$sodinhdanh = $_POST['sodinhdanh'] ?? '';
$hoten = $_POST['hoten'] ?? '';
$ngaysinh = $_POST['ngaysinh'] ?? '';
$gioitinh = $_POST['gioitinh'] ?? '';
$quoctich = $_POST['quoctich'] ?? '';

// Get photo data (optional)
$photoBase64 = $_POST['photo'] ?? '';
$photoX = (int)($_POST['photoX'] ?? 75);
$photoY = (int)($_POST['photoY'] ?? 325);
$photoWidth = (int)($_POST['photoWidth'] ?? 300);
$photoHeight = (int)($_POST['photoHeight'] ?? 370);

// Validate required fields
if (empty($sodinhdanh) || empty($hoten) || empty($ngaysinh) || empty($gioitinh) || empty($quoctich)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Load template image
$templatePath = __DIR__ . '/front.png';
if (!file_exists($templatePath)) {
    echo json_encode(['success' => false, 'error' => 'Template image not found']);
    exit;
}

$image = imagecreatefrompng($templatePath);
if (!$image) {
    echo json_encode(['success' => false, 'error' => 'Failed to load template image']);
    exit;
}

// Enable alpha blending and save alpha channel for transparency
imagealphablending($image, true);
imagesavealpha($image, true);

// Set up colors
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);

// Font settings - using built-in GD fonts for now
// For better Vietnamese support, you should use a TrueType font with imagettftext()
$fontPath = __DIR__ . '/arial.ttf'; // You may need to upload a font file

// Format date from YYYY-MM-DD to DD/MM/YYYY
if (!empty($ngaysinh)) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $ngaysinh);
    if ($dateObj) {
        $ngaysinh = $dateObj->format('d/m/Y');
    }
}

// Text positions (estimated based on standard CCCD layout)
// You may need to adjust these coordinates based on your actual template
$positions = [
    'sodinhdanh' => ['x' => 400, 'y' => 280, 'size' => 24],
    'hoten' => ['x' => 400, 'y' => 360, 'size' => 22],
    'ngaysinh' => ['x' => 400, 'y' => 440, 'size' => 20],
    'gioitinh' => ['x' => 700, 'y' => 440, 'size' => 20],
    'quoctich' => ['x' => 400, 'y' => 520, 'size' => 20]
];

// GLOW OPACITY SETTING (0-100%) - Adjust this to change glow intensity
$GLOW_OPACITY = 15; // 15% = subtle glow, 30% = medium, 50% = strong

// Function to write text on image with outer glow effect
function writeText($image, $text, $x, $y, $size, $color, $fontPath, $glowOpacity = 15) {
    if (file_exists($fontPath)) {
        // Calculate alpha values based on opacity percentage (0-100% -> 127-0 alpha)
        // Alpha in GD: 0 = opaque, 127 = transparent
        $alpha1 = (int)(127 - ($glowOpacity / 100 * 127 * 0.2)); // Outer glow: 20% of opacity
        $alpha2 = (int)(127 - ($glowOpacity / 100 * 127 * 0.4)); // Inner glow: 40% of opacity
        
        // Create outer glow effect with multiple layers
        // Layer 1: Outermost glow (most transparent, widest spread - 3 pixel radius)
        $glow1 = imagecolorallocatealpha($image, 100, 100, 100, $alpha1);
        for ($dx = -3; $dx <= 3; $dx++) {
            for ($dy = -3; $dy <= 3; $dy++) {
                if ($dx != 0 || $dy != 0) {
                    imagettftext($image, $size, 0, $x + $dx, $y + $dy, $glow1, $fontPath, $text);
                }
            }
        }
        
        // Layer 2: Inner glow (more visible)
        $glow2 = imagecolorallocatealpha($image, 90, 90, 90, $alpha2);
        $offsets = [
            [-1, -1], [0, -1], [1, -1],
            [-1,  0],          [1,  0],
            [-1,  1], [0,  1], [1,  1]
        ];
        foreach ($offsets as $offset) {
            imagettftext($image, $size, 0, $x + $offset[0], $y + $offset[1], $glow2, $fontPath, $text);
        }
        
        // Draw main text on top (sharp and clear)
        imagettftext($image, $size, 0, $x, $y, $color, $fontPath, $text);
    } else {
        // Fallback to built-in font (no glow for built-in fonts)
        $text = mb_strtoupper($text, 'UTF-8');
        imagestring($image, 5, $x, $y, $text, $color);
    }
}

// Write text on image
writeText($image, $sodinhdanh, 486, 530, 45, $black, 'SVN-Arial 2 bold.ttf', $GLOW_OPACITY);
writeText($image, mb_strtoupper($hoten, 'UTF-8'), 486,655, 34, $black, 'SVN-Arial Regular.ttf', $GLOW_OPACITY);
writeText($image, $ngaysinh, 543, 770, 29, $black, 'SVN-Arial Regular.ttf', $GLOW_OPACITY);
writeText($image, $gioitinh, 1127, 770, 29, $black, 'SVN-Arial Regular.ttf', $GLOW_OPACITY);
writeText($image, $quoctich, 530, 870, 29, $black, 'SVN-Arial Regular.ttf', $GLOW_OPACITY);

// Overlay photo if provided
if (!empty($photoBase64)) {
    // Remove base64 header if present
    $photoBase64 = preg_replace('/^data:image\/[a-z]+;base64,/', '', $photoBase64);
    $photoData = base64_decode($photoBase64);
    
    if ($photoData !== false) {
        // Create image from string
        $photoImage = imagecreatefromstring($photoData);
        
        if ($photoImage !== false) {
            // Create a new image with the desired dimensions
            $resizedPhoto = imagecreatetruecolor($photoWidth, $photoHeight);
            
            // Enable alpha blending for transparency
            imagealphablending($resizedPhoto, false);
            imagesavealpha($resizedPhoto, true);
            
            // Get original dimensions
            $originalWidth = imagesx($photoImage);
            $originalHeight = imagesy($photoImage);
            
            // Resize and copy the photo
            imagecopyresampled(
                $resizedPhoto, $photoImage,
                0, 0, 0, 0,
                $photoWidth, $photoHeight,
                $originalWidth, $originalHeight
            );
            
            // Overlay the photo onto the main image
            imagecopy($image, $resizedPhoto, $photoX, $photoY, 0, 0, $photoWidth, $photoHeight);
            
            // Clean up
            imagedestroy($photoImage);
            imagedestroy($resizedPhoto);
        }
    }
}

// Capture image as base64
ob_start();
imagepng($image, null, 9);
$imageData = ob_get_clean();
$base64 = base64_encode($imageData);

// Clean up
imagedestroy($image);

// Return JSON response with base64 image
echo json_encode([
    'success' => true,
    'image' => 'data:image/png;base64,' . $base64,
    'message' => 'Front image generated successfully'
]);
