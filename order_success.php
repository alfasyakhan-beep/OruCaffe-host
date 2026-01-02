<?php
session_start();
include 'config/koneksi.php';

// 1. Ambil Kode Pesanan dari URL
if (!isset($_GET['code'])) {
  header("Location: menu.php");
  exit;
}

$code = mysqli_real_escape_string($koneksi, $_GET['code']);

// 2. Ambil Data Pesanan dari Database
// Kita butuh tau: Total Harga & Metode Pembayarannya apa?
$query = mysqli_query($koneksi, "SELECT * FROM orders WHERE kode_pesanan = '$code'");
$order = mysqli_fetch_assoc($query);

// Validasi: Kalau pesanan tidak ditemukan (user iseng ngetik url ngawur)
if (!$order) {
  echo "<script>alert('Pesanan tidak ditemukan!'); window.location='menu.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Berhasil | OurCoffee</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    body {
      background: #f9f6f3;
    }

    .success-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .ticket-card {
      background: #fff;
      width: 100%;
      max-width: 420px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      position: relative;
    }

    /* Efek Bergerigi ala Kertas Struk (Optional Hiasan) */
    .ticket-card::after {
      content: "";
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 100%;
      height: 20px;
      background: radial-gradient(circle, transparent 70%, #fff 75%) 0 -10px;
      background-size: 20px 20px;
      transform: rotate(180deg);
    }

    .ticket-header {
      background: #8b5e3c;
      color: #fff;
      padding: 30px 20px;
      text-align: center;
    }

    .ticket-body {
      padding: 30px;
      text-align: center;
    }

    .order-code-box {
      background: #fdf2e9;
      border: 2px dashed #8b5e3c;
      border-radius: 12px;
      padding: 15px;
      margin: 20px 0;
    }

    .order-code-text {
      font-size: 24px;
      font-weight: 800;
      color: #4a3228;
      letter-spacing: 1px;
    }

    .qr-image {
      width: 200px;
      height: 200px;
      margin: 0 auto 15px;
      display: block;
      border: 1px solid #eee;
      padding: 10px;
      border-radius: 10px;
    }

    .payment-status-badge {
      display: inline-block;
      padding: 5px 15px;
      border-radius: 50px;
      font-size: 12px;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .badge-pending {
      background: #fff3cd;
      color: #856404;
    }

    .badge-success {
      background: #d4edda;
      color: #155724;
    }

    .btn-home {
      background: #4a3228;
      color: #fff;
      border-radius: 50px;
      padding: 12px 30px;
      text-decoration: none;
      display: inline-block;
      margin-top: 20px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-home:hover {
      background: #3e2723;
      color: #fff;
    }
  </style>
</head>

<body>

  <div class="success-container">
    <div class="ticket-card">

      <div class="ticket-header">
        <i class="fas fa-check-circle fa-4x mb-3"></i>
        <h4 class="fw-bold mb-0">Pesanan Diterima!</h4>
        <p class="mb-0 opacity-75 small">Mohon selesaikan pembayaran</p>
      </div>

      <div class="ticket-body">

        <span class="payment-status-badge badge-pending">
          STATUS: MENUNGGU PEMBAYARAN
        </span>

        <?php if ($order['payment_method'] == 'qris'): ?>

          <h5 class="fw-bold text-dark">Scan QRIS</h5>
          <p class="text-muted small">Scan QR di bawah ini menggunakan GoPay, OVO, Dana, atau ShopeePay.</p>

          <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=PAY-<?= $order['kode_pesanan']; ?>" class="qr-image" alt="QRIS Code">

          <h3 class="fw-bold text-danger mb-0">Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?></h3>
          <p class="small text-muted mt-2">Otomatis terverifikasi setelah pembayaran.</p>

        <?php elseif ($order['payment_method'] == 'cashier'): ?>

          <h5 class="fw-bold text-dark">Bayar di Kasir</h5>
          <p class="text-muted small">Tunjukkan Barcode atau Kode Pesanan ini kepada kasir kami.</p>

          <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= $order['kode_pesanan']; ?>" class="qr-image" alt="Barcode Pesanan">

          <div class="order-code-box">
            <small class="text-muted d-block mb-1">KODE PESANAN</small>
            <div class="order-code-text"><?= $order['kode_pesanan']; ?></div>
          </div>

          <p class="mb-0 fw-bold">Total: Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?></p>

        <?php endif; ?>

        <hr class="my-4">

        <a href="menu.php" class="btn-home">
          <i class="fas fa-utensils me-2"></i> Pesan Lagi
        </a>

        <div class="mt-3">
          <a href="index.php" class="text-muted small text-decoration-none">Kembali ke Beranda</a>
        </div>

      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>


</body>

</html>