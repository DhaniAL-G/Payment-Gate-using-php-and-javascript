<?php 
include 'includes/header.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$orderId = $_GET['order_id'];
$stmt = $db->prepare("SELECT * FROM transactions WHERE order_id = ?");
$stmt->execute([$orderId]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    die('Transaksi tidak ditemukan');
}
?>

<div class="container">
    <h1 class="text-center my-4">Proses Pembayaran</h1>
    
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Pembayaran</h4>
                </div>
                <div class="card-body">
                    <div class="payment-details">
                        <div class="detail-item">
                            <span class="label">Order ID:</span>
                            <span class="value"><?= htmlspecialchars($transaction['order_id']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Jumlah:</span>
                            <span class="value">Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Metode:</span>
                            <span class="value"><?= htmlspecialchars($transaction['payment_method']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Status:</span>
                            <span class="badge bg-<?= 
                                $transaction['status'] === 'success' ? 'success' : 
                                ($transaction['status'] === 'failed' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($transaction['status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div id="paymentInstruction" class="mt-4">
                        <?php if ($transaction['payment_method'] === 'virtual_account'): ?>
                            <h5>Virtual Account</h5>
                            <div class="va-number">9881234567890</div>
                            <p>Transfer tepat hingga 3 digit terakhir</p>
                            <p>Berlaku hingga <?= date('d M Y H:i', strtotime('+1 hour')) ?></p>
                            
                        <?php elseif ($transaction['payment_method'] === 'bank_transfer'): ?>
                            <h5>Transfer Bank</h5>
                            <p>Rekening: 1234567890 (Bank Contoh)</p>
                            <p>a.n: Nama Merchant Anda</p>
                            <p>Jumlah: Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></p>
                            
                        <?php elseif ($transaction['payment_method'] === 'ewallet'): ?>
                            <h5>E-Wallet</h5>
                            <div class="text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($transaction['order_id']) ?>" 
                                     alt="QR Code" class="qr-code">
                                <p>Scan QR code untuk pembayaran</p>
                            </div>
                            
                        <?php elseif ($transaction['payment_method'] === 'credit_card'): ?>
                            <h5>Kartu Kredit</h5>
                            <form id="creditCardForm">
                                <div class="mb-3">
                                    <label for="cardNumber" class="form-label">Nomor Kartu</label>
                                    <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="expiryDate" class="form-label">Masa Berlaku</label>
                                        <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" placeholder="123">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Bayar Sekarang</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="status.php?order_id=<?= $orderId ?>" class="btn btn-outline-secondary">
                            Cek Status Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simulasi pembayaran kartu kredit
document.getElementById('creditCardForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simulasi pembayaran berhasil
    fetch('api/transaction.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            order_id: '<?= $orderId ?>',
            status: 'success'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pembayaran berhasil!');
            window.location.href = 'status.php?order_id=<?= $orderId ?>';
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
