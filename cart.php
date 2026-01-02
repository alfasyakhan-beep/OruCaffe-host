<?php
session_start();
include 'config/koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
  $_SESSION['error'] = "Please login to view your cart.";
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// 2. Query Data Keranjang
$query = "SELECT c.id as cart_id, c.qty, p.nama_produk, p.harga, p.foto 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = '$user_id'";
$result = mysqli_query($koneksi, $query);

$total_bayar = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart | OurCoffee</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/cart.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

  <section class="cart-section">
    <div class="container">

      <?php if (mysqli_num_rows($result) > 0): ?>

        <div class="cart-header-row">
          <div class="cart-title-block">
            <h1 class="cart-title">Your Cart</h1>
            <p class="cart-subtitle">Complete your order and enjoy your coffee.</p>
          </div>

          <a href="menu.php" class="btn-header-back">
            <i class="fas fa-times"></i>
            <span class="d-none d-md-inline ms-2">Back to Menu</span>
          </a>
        </div>

        <div class="row">
          <div class="col-lg-8">
            <div class="cart-list">
              <?php while ($row = mysqli_fetch_assoc($result)):
                $subtotal = $row['harga'] * $row['qty'];
                $total_bayar += $subtotal;
              ?>
                <div class="cart-item">
                  <div class="cart-img-wrapper">
                    <img src="assets/img/products/<?= $row['foto']; ?>" alt="<?= $row['nama_produk']; ?>">
                  </div>

                  <div class="cart-details">
                    <h3 class="product-name"><?= $row['nama_produk']; ?></h3>
                    <p class="product-price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                  </div>

                  <div class="cart-qty-control">
                    <a href="actions/cart_controller.php?act=update&type=minus&cart_id=<?= $row['cart_id']; ?>" class="qty-btn minus"><i class="fas fa-minus"></i></a>
                    <span class="qty-val"><?= $row['qty']; ?></span>
                    <a href="actions/cart_controller.php?act=update&type=plus&cart_id=<?= $row['cart_id']; ?>" class="qty-btn plus"><i class="fas fa-plus"></i></a>
                  </div>

                  <div class="cart-action-right">
                    <p class="subtotal-text">Rp <?= number_format($subtotal, 0, ',', '.'); ?></p>
                    <a href="actions/cart_controller.php?act=delete&cart_id=<?= $row['cart_id']; ?>" class="btn-delete">
                      <i class="fas fa-trash-alt"></i>
                    </a>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="cart-summary">
              <h3>Order Summary</h3>
              <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp <?= number_format($total_bayar, 0, ',', '.'); ?></span>
              </div>
              <div class="summary-row">
                <span>Tax (0%)</span>
                <span>Rp 0</span>
              </div>
              <hr>
              <div class="summary-row total">
                <span>Total</span>
                <span>Rp <?= number_format($total_bayar, 0, ',', '.'); ?></span>
              </div>

              <a href="checkout.php" class="btn-checkout">Checkout Now</a>
            </div>
          </div>
        </div>

      <?php else: ?>

        <div class="empty-cart-wrapper">
          <div class="empty-cart-icon">
            <i class="fas fa-shopping-basket"></i>
          </div>
          <h3 class="empty-cart-title">Your Cart Is Still Empty</h3>
          <p class="empty-cart-desc">
            Browse our menu and add your favorite items to the cart.
          </p>
          <a href="menu.php" class="btn-back-menu">
            <i class="fas fa-mug-hot"></i> See Menu
          </a>
        </div>

      <?php endif; ?>

    </div>
  </section>

  <?php include 'includes/footer.php'; ?>


  <script>
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        Swal.fire({
          title: 'Yakin mau hapus?',
          text: "Item ini akan dihapus dari keranjangmu.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) window.location.href = href;
        });
      });
    });
  </script>

  <?php if (isset($_SESSION['success'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= $_SESSION['success']; ?>',
        timer: 1500,
        showConfirmButton: false
      });
    </script>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>