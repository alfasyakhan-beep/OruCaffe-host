<?php
session_start();
include 'config/koneksi.php';

// Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Query Ambil Data Order (Diurutkan dari yang terbaru)
$query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan | OurCoffee</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/history.css">

  <style>


  </style>
</head>

<body>

  <?php include 'includes/navbar.php'; ?>

  <section class="history-section">
    <div class="container">
      <h2 class="fw-bold mb-4" style="color: #4a3228;">Riwayat Pesanan</h2>

      <?php if (mysqli_num_rows($result) > 0): ?>

        <div class="row">
          <div class="col-lg-8">
            <?php while ($row = mysqli_fetch_assoc($result)):
              // Tentukan warna status
              $status_class = 'status-pending';
              $badge_color = 'bg-warning text-dark';
              $status_label = 'Menunggu Pembayaran';

              if ($row['status'] == 'paid') {
                $status_class = 'status-paid';
                $badge_color = 'bg-success';
                $status_label = 'Selesai / Dibayar';
              } elseif ($row['status'] == 'cancelled') {
                $status_class = 'status-cancelled';
                $badge_color = 'bg-danger';
                $status_label = 'Dibatalkan';
              }
            ?>
              <div class="history-card">
                <div class="status-line <?= $status_class; ?>"></div>

                <div class="d-flex justify-content-between align-items-start mb-3">
                  <div>
                    <div class="order-date mb-1">
                      <i class="far fa-calendar-alt me-1"></i>
                      <?= date('d M Y, H:i', strtotime($row['created_at'])); ?>
                    </div>
                    <div class="order-code">
                      <?= $row['kode_pesanan']; ?>
                      <span class="badge <?= $badge_color; ?> ms-2 text-white badge-status">
                        <?= $status_label; ?>
                      </span>
                    </div>
                  </div>
                  <div class="text-end">
                    <div class="small text-muted">Total Belanja</div>
                    <div class="total-price">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></div>
                  </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <div class="text-muted small">
                    Metode:
                    <strong>
                      <?= ($row['payment_method'] == 'qris') ? 'QRIS Scan' : 'Bayar di Kasir'; ?>
                    </strong>
                    <?php if (!empty($row['no_meja'])): ?>
                      | Meja: <strong><?= $row['no_meja']; ?></strong>
                    <?php endif; ?>
                  </div>

                  <a href="order_success.php?code=<?= $row['kode_pesanan']; ?>" class="btn-detail">
                    <i class="fas fa-receipt me-1"></i> Lihat Nota
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>

      <?php else: ?>

        <div class="text-center py-5">
          <i class="fas fa-history fa-4x text-muted mb-3 opacity-25"></i>
          <h4 class="text-muted">Belum ada riwayat pesanan</h4>
          <a href="menu.php" class="btn btn-outline-dark rounded-pill mt-3">Pesan Sekarang</a>
        </div>

      <?php endif; ?>

    </div>
  </section>

  <?php include 'includes/footer.php'; ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>