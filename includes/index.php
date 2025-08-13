<?php include 'includes/header.php'; ?>

<div class="container">
    <h1 class="text-center my-4">Sistem Pembayaran</h1>
    
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>Buat Transaksi Baru</h4>
                </div>
                <div class="card-body">
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Pembayaran</label>
                            <input type="number" class="form-control" id="amount" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="paymentMethod" required>
                                <option value="">Pilih Metode</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="virtual_account">Virtual Account</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="customerName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label">Email Pelanggan</label>
                            <input type="email" class="form-control" id="customerEmail" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Proses Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
