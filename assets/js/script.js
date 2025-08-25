document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Ambil data form
            const formData = {
                amount: document.getElementById('amount').value,
                payment_method: document.getElementById('paymentMethod').value,
                customer_name: document.getElementById('customerName').value,
                customer_email: document.getElementById('customerEmail').value
            };
            
            // Kirim ke API
            fetch('api/payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect ke halaman pembayaran
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error: ' + (data.error || 'Gagal memproses pembayaran'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses pembayaran');
            });
        });
    }
    
    // Format input credit card
    const cardNumber = document.getElementById('cardNumber');
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formatted = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += value[i];
            }
            
            e.target.value = formatted.substring(0, 19);
        });
    }
    
    // Format tanggal kadaluarsa
    const expiryDate = document.getElementById('expiryDate');
    if (expiryDate) {
        expiryDate.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            
            e.target.value = value.substring(0, 5);
        });
    }
    
    // Format CVV
    const cvv = document.getElementById('cvv');
    if (cvv) {
        cvv.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/gi, '').substring(0, 4);
        });
    }
});
