<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validasi input
    if (empty($data['amount']) || empty($data['payment_method']) || 
        empty($data['customer_name']) || empty($data['customer_email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Data tidak lengkap']);
        exit;
    }
    
    // Generate order ID unik
    $orderId = 'PAY-' . time() . '-' . rand(1000, 9999);
    
    try {
        // Simpan ke database
        $stmt = $db->prepare("
            INSERT INTO transactions 
            (order_id, amount, payment_method, customer_name, customer_email, status) 
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        
        $stmt->execute([
            $orderId,
            $data['amount'],
            $data['payment_method'],
            $data['customer_name'],
            $data['customer_email']
        ]);
        
        // Simulasikan integrasi dengan payment gateway
        $paymentResponse = simulatePaymentGateway($orderId, $data['amount'], $data['payment_method']);
        
        // Response untuk client
        echo json_encode([
            'success' => true,
            'order_id' => $orderId,
            'payment_data' => $paymentResponse,
            'redirect_url' => 'payment.php?order_id=' . $orderId
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

// Fungsi simulasi payment gateway
function simulatePaymentGateway($orderId, $amount, $method) {
    // Ini hanya simulasi, pada implementasi nyata akan berintegrasi dengan:
    // - Midtrans
    // - Xendit
    // - Doku
    // - atau payment gateway lainnya
    
    $simulatedData = [
        'transaction_id' => 'SIM-' . time(),
        'payment_url' => '#',
        'qr_code' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($orderId),
        'virtual_account' => '988' . rand(100000000, 999999999),
        'expiry_time' => date('Y-m-d H:i:s', strtotime('+1 hour'))
    ];
    
    return $simulatedData;
}
?>
