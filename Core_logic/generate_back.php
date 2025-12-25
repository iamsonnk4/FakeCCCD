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
$noicutru = $_POST['noicutru'] ?? '';
$noidangkykhaisinh = $_POST['noidangkykhaisinh'] ?? '';
$ngaycap = $_POST['ngaycap'] ?? '';
$ngayhethan = $_POST['ngayhethan'] ?? '';
$idvnm_code = $_POST['idvnm_code'] ?? '';

// Get additional data for QR code generation
$sodinhdanh = $_POST['sodinhdanh'] ?? '';
$hoten = $_POST['hoten'] ?? '';
$ngaysinh = $_POST['ngaysinh'] ?? '';
$gioitinh = $_POST['gioitinh'] ?? '';

// Validate required fields
if (empty($noicutru) || empty($noidangkykhaisinh) || empty($ngaycap) || empty($ngayhethan) || empty($idvnm_code)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Load template image
$templatePath = __DIR__ . '/back.png';
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

// Font settings
$fontPath = __DIR__ . '/arial.ttf'; // You may need to upload a font file

// Format dates from YYYY-MM-DD to DD/MM/YYYY
if (!empty($ngaycap)) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $ngaycap);
    if ($dateObj) {
        $ngaycap = $dateObj->format('d/m/Y');
    }
}

if (!empty($ngayhethan)) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $ngayhethan);
    if ($dateObj) {
        $ngayhethan = $dateObj->format('d/m/Y');
    }
}

// Text positions (estimated based on standard CCCD back layout)
// You may need to adjust these coordinates based on your actual template
$positions = [
    'noicutru' => ['x' => 300, 'y' => 250, 'size' => 18],
    'noidangkykhaisinh' => ['x' => 300, 'y' => 350, 'size' => 18],
    'ngaycap' => ['x' => 300, 'y' => 450, 'size' => 20],
    'ngayhethan' => ['x' => 600, 'y' => 450, 'size' => 20],
    'idvnm_code' => ['x' => 300, 'y' => 550, 'size' => 16, 'line_height' => 30]
];

// GLOW OPACITY SETTING (0-100%) - Adjust this to change glow intensity
$GLOW_OPACITY = 15; // 15% = subtle glow, 30% = medium, 50% = strong

// QR CODE SETTINGS - Adjust position and size
$QR_X = 1025;        // X position (px)
$QR_Y = 630;         // Y position (px)
$QR_WIDTH = 230;     // QR code width (px)
$QR_HEIGHT = 230;    // QR code height (px)

// Generate QR code URL
function generateQRCodeURL($sodinhdanh, $hoten, $ngaysinh, $gioitinh, $noidangkykhaisinh, $ngayhethan) {
    // Generate random 9-digit number
    $randomNumber = str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
    
    // Format date from YYYY-MM-DD to DDMMYYYY
    $ngaysinhFormatted = '';
    if (!empty($ngaysinh)) {
        $dateObj = DateTime::createFromFormat('Y-m-d', $ngaysinh);
        if ($dateObj) {
            $ngaysinhFormatted = $dateObj->format('dmY');
        }
    }
    
    $ngayhethanFormatted = '';
    if (!empty($ngayhethan)) {
        $dateObj = DateTime::createFromFormat('Y-m-d', $ngayhethan);
        if ($dateObj) {
            $ngayhethanFormatted = $dateObj->format('dmY');
        }
    }
    
    // Build QR data string (pipe-separated)
    $qrData = $sodinhdanh . '|' . 
              $randomNumber . '|' . 
              $hoten . '|' . 
              $ngaysinhFormatted . '|' . 
              $gioitinh . '|' . 
              $noidangkykhaisinh . '|' . 
              $ngayhethanFormatted . '|||';
    
    // URL encode and create QuickChart URL
    $qrUrl = 'https://quickchart.io/qr?text=' . rawurlencode($qrData) . '&light=0000';
    
    return $qrUrl;
}

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

// Function to write multi-line text with glow
function writeMultiLineText($image, $text, $x, $y, $size, $lineHeight, $color, $fontPath, $glowOpacity = 15) {
    $lines = explode("\n", $text);
    $currentY = $y;
    
    foreach ($lines as $line) {
        writeText($image, trim($line), $x, $currentY, $size, $color, $fontPath, $glowOpacity);
        $currentY += $lineHeight;
    }
}

// Write text on image
writeText($image, mb_strtoupper($noicutru, 'UTF-8'), 109, 172, 30, $black, 'SVN-Arial Regular.ttf', $GLOW_OPACITY);
writeText($image, mb_strtoupper($noidangkykhaisinh, 'UTF-8'), 109, 288, 30, $black, 'SVN-Arial Regular.ttf', $GLOW_OPACITY);
writeText($image, $ngaycap, 688, 395, 27, $black, 'SVN-Arial Regular.ttf', $GLOW_OPACITY);
writeText($image, $ngayhethan, 685, 490, 27, $black, 'SVN-Arial Regular.ttf', $GLOW_OPACITY);

// Write IDVNM Code (multi-line)
writeMultiLineText($image, $idvnm_code, 100, 700, 39, 75, $black, 'OCR-B.otf', $GLOW_OPACITY);

// Generate and overlay QR code
$qrGenerated = false;
if (!empty($sodinhdanh) && !empty($hoten) && !empty($ngaysinh) && !empty($gioitinh)) {
    $qrUrl = generateQRCodeURL($sodinhdanh, $hoten, $ngaysinh, $gioitinh, $noidangkykhaisinh, $ngayhethan);
    
    // Fetch QR code image using curl for better reliability
    $ch = curl_init($qrUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $qrImageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($qrImageData !== false && $httpCode == 200 && !empty($qrImageData)) {
        $qrImage = @imagecreatefromstring($qrImageData);
        
        if ($qrImage !== false) {
            // Get QR dimensions
            $qrOriginalWidth = imagesx($qrImage);
            $qrOriginalHeight = imagesy($qrImage);
            
            // Create resized QR code
            $resizedQR = imagecreatetruecolor($QR_WIDTH, $QR_HEIGHT);
            
            // Fill with white background
            $white = imagecolorallocate($resizedQR, 255, 255, 255);
            imagefill($resizedQR, 0, 0, $white);
            
            // Resize QR code
            imagecopyresampled(
                $resizedQR, $qrImage,
                0, 0, 0, 0,
                $QR_WIDTH, $QR_HEIGHT,
                $qrOriginalWidth, $qrOriginalHeight
            );
            
            // Overlay QR code onto the main image
            imagecopy($image, $resizedQR, $QR_X, $QR_Y, 0, 0, $QR_WIDTH, $QR_HEIGHT);
            $qrGenerated = true;
            
            // Clean up
            imagedestroy($qrImage);
            imagedestroy($resizedQR);
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
    'message' => 'Back image generated successfully'
]);
