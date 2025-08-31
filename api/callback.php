<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

// Ini adalah endpoint yang akan dipanggil oleh payment gateway untuk update status
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validasi signature/callback (contoh simpel)
if (empty($data['order_id']) || empty($data['status']) || empty($data['signature'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid callback data']);
    exit;
}

// Pada implementasi nyata, verifikasi signature dengan payment gateway
$validSignature = verifyCallbackSignature($data);

if (!$validSignature) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

try {
    // Update status transaksi
    $stmt = $db->prepare("
        UPDATE transactions 
        SET status = ? 
        WHERE order_id = ?
    ");
    
    $stmt->execute([$data['status'], $data['order_id']]);
    
    // Response ke payment gateway
    echo json_encode(['success' => true]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

// Fungsi verifikasi signature (contoh)
function verifyCallbackSignature($data) {
    // Pada implementasi nyata, ini akan memverifikasi signature dari payment gateway
    // Contoh: Midtrans, Xendit, dll memiliki metode verifikasi tersendiri
    $expectedSignature = md5($data['order_id'] . $data['status'] . 'YOUR_SECRET_KEY');
    
    return $data['signature'] === $expectedSignature;
}
?>
