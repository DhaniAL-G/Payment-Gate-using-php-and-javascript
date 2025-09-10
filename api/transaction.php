<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['order_id']) || empty($data['status'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Data tidak lengkap']);
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
        
        // Jika berhasil, mengirim notifikasi
        if ($data['status'] === 'success') {
            sendPaymentNotification($data['order_id']);
        }
        
        echo json_encode(['success' => true]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

// Fungsi simulasi pengiriman notifikasi
function sendPaymentNotification($orderId) {
    // Pada implementasi nyata, ini akan mengirim email/SMS/notifikasi
    error_log("Notifikasi pembayaran sukses untuk Order ID: $orderId");
    
    // Contoh: Simpan notifikasi ke database atau kirim email
    return true;
}
?>
