<?php
require_once 'includes/db.php';

// Form validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$dob = isset($_POST['dob']) && !empty($_POST['dob']) ? $_POST['dob'] : null;
$employee_id = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

$primary_color = isset($_POST['primary_color']) ? trim($_POST['primary_color']) : '#6366F1';
$photo_shape = isset($_POST['photo_shape']) ? trim($_POST['photo_shape']) : 'circle';
$logo_placement = isset($_POST['logo_placement']) ? trim($_POST['logo_placement']) : 'top-left';
$font_family = isset($_POST['font_family']) ? trim($_POST['font_family']) : 'inter';

$qr_code_enabled = isset($_POST['qr_code_enabled']) ? 1 : 0;
$hologram_enabled = isset($_POST['hologram_enabled']) ? 1 : 0;

$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$terms = isset($_POST['terms']) ? trim($_POST['terms']) : '';
$include_emergency = isset($_POST['include_emergency']) ? 1 : 0;
$emergency_contact = isset($_POST['emergency_contact']) ? trim($_POST['emergency_contact']) : '';
$include_back_barcode = isset($_POST['include_back_barcode']) ? 1 : 0;

$status = 'Active';

// Handle Photo Upload
$photo_path = '';

// Create uploads directory if it does not exist
$uploads_dir = __DIR__ . '/uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0777, true);
}

// 1. Direct File Upload Check
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['photo']['tmp_name'];
    $file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES['photo']['name']));
    $dest = $uploads_dir . '/' . $file_name;
    
    if (move_uploaded_file($file_tmp, $dest)) {
        $photo_path = 'uploads/' . $file_name;
    }
}

// 2. Base64 Upload Check (Fallback if JS processed base64 data)
if (empty($photo_path) && isset($_POST['photo_base64']) && strpos($_POST['photo_base64'], 'data:image') === 0) {
    $base64_str = $_POST['photo_base64'];
    $parts = explode(',', $base64_str);
    if (count($parts) > 1) {
        $data = base64_decode($parts[1]);
        $file_name = time() . '_avatar.png';
        $dest = $uploads_dir . '/' . $file_name;
        
        if (file_put_contents($dest, $data)) {
            $photo_path = 'uploads/' . $file_name;
        }
    }
}

// 3. Fallback placeholder image if no upload provided
if (empty($photo_path)) {
    $photo_path = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80';
}

// Save to Database
try {
    $sql = "INSERT INTO cards (
        name, dob, employee_id, company, email, photo_path, 
        primary_color, photo_shape, logo_placement, font_family, 
        qr_code_enabled, hologram_enabled, address, terms, 
        emergency_contact, include_emergency, include_back_barcode, 
        status
    ) VALUES (
        :name, :dob, :employee_id, :company, :email, :photo_path,
        :primary_color, :photo_shape, :logo_placement, :font_family,
        :qr_code_enabled, :hologram_enabled, :address, :terms,
        :emergency_contact, :include_emergency, :include_back_barcode,
        :status
    )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':dob' => $dob,
        ':employee_id' => $employee_id,
        ':company' => $company,
        ':email' => $email,
        ':photo_path' => $photo_path,
        ':primary_color' => $primary_color,
        ':photo_shape' => $photo_shape,
        ':logo_placement' => $logo_placement,
        ':font_family' => $font_family,
        ':qr_code_enabled' => $qr_code_enabled,
        ':hologram_enabled' => $hologram_enabled,
        ':address' => $address,
        ':terms' => $terms,
        ':emergency_contact' => $emergency_contact,
        ':include_emergency' => $include_emergency,
        ':include_back_barcode' => $include_back_barcode,
        ':status' => $status
    ]);
    
    $card_id = $pdo->lastInsertId();
    header("Location: preview.php?id=" . $card_id . "&success=1");
    exit;
} catch (PDOException $e) {
    die("Database save error: " . $e->getMessage());
}
?>
