<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Cek keranjang
$cek_cart = mysqli_query($koneksi, "SELECT * FROM cart WHERE user_id = '$user_id'");
if (mysqli_num_rows($cek_cart) == 0) {
    header("Location: menu.php");
    exit;
}

// Hitung Total
$q_total = mysqli_query($koneksi, "
    SELECT SUM(c.qty * p.harga) as total 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = '$user_id'
");
$row_total = mysqli_fetch_assoc($q_total);
$grand_total = $row_total['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | OurCoffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: #f9f6f3;
        }

        .checkout-box {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin: 60px auto;
        }

        .payment-option {
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .payment-option:has(input:checked) {
            border-color: #8b5e3c;
            background: #fdf2e9;
        }

        .total-pay {
            font-size: 24px;
            font-weight: 800;
            color: #8b5e3c;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="checkout-box">
            <h2 class="text-center fw-bold mb-4" style="color:#4a3228;">Checkout</h2>

            <form action="actions/checkout_controller.php?act=process" method="POST">
                <input type="hidden" name="total_price" value="<?= $grand_total; ?>">

                <div class="mb-4">
                    <label class="form-label fw-bold">Nomor Meja</label>
                    <input type="text" name="no_meja" class="form-control form-control-lg" placeholder="Contoh: A-05" required style="border-radius: 10px;">
                    <small class="text-muted">Lihat stiker nomor di meja Anda.</small>
                </div>

                <p class="text-center text-muted mb-0">Total Tagihan:</p>
                <div class="total-pay">Rp <?= number_format($grand_total, 0, ',', '.'); ?></div>

                <h6 class="fw-bold mb-3">Metode Pembayaran</h6>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="qris" class="form-check-input me-3" required>
                    <div class="fs-4 me-3 text-warning"><i class="fas fa-qrcode"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Scan QRIS</h6>
                        <small class="text-muted">GoPay, OVO, Dana, ShopeePay</small>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="cashier" class="form-check-input me-3" required>
                    <div class="fs-4 me-3 text-secondary"><i class="fas fa-cash-register"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Bayar di Kasir</h6>
                        <small class="text-muted">Tunai / Debit di meja kasir</small>
                    </div>
                </label>

                <hr class="my-4">

                <button type="submit" class="btn w-100 py-3 fw-bold text-white" style="background: #8b5e3c; border-radius: 50px;">
                    Buat Pesanan
                </button>

                <a href="cart.php" class="d-block text-center mt-3 text-muted text-decoration-none">Batal</a>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>


</body>

</html>