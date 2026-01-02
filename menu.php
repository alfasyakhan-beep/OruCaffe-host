<?php
session_start();
include 'config/koneksi.php';

$is_logged_in = isset($_SESSION['status']) && $_SESSION['status'] == "login";
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;


$cart_map = [];
$total_item_cart = 0;

if ($is_logged_in) {
    $q_cart = mysqli_query($koneksi, "SELECT product_id, qty FROM cart WHERE user_id = '$user_id'");
    while ($c = mysqli_fetch_assoc($q_cart)) {
        $cart_map[$c['product_id']] = $c['qty'];
        $total_item_cart += $c['qty']; // Hitung total item
    }
}

// LOGIKA FILTER
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$where_sql = "";
$title_menu = "All Menu";

if ($kategori == 'minuman') {
    $where_sql = "WHERE kategori = 'minuman'";
    $title_menu = "Coffee & Drinks";
} elseif ($kategori == 'makanan') {
    $where_sql = "WHERE kategori = 'makanan'";
    $title_menu = "Food & Snacks";
}

$query = "SELECT * FROM products $where_sql ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | OurCoffee</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="menu-header">
        <h1><?= $title_menu; ?></h1>
        <p>Discover your favorite flavors from our selection of the finest coffee beans and delicious snacks.</p>

        <div class="filter-container">
            <a href="menu.php" class="filter-btn <?= ($kategori == '') ? 'active' : ''; ?>">All Menus</a>
            <a href="menu.php?kategori=minuman" class="filter-btn <?= ($kategori == 'minuman') ? 'active' : ''; ?>">Coffee & Drinks</a>
            <a href="menu.php?kategori=makanan" class="filter-btn <?= ($kategori == 'makanan') ? 'active' : ''; ?>">Food & Snacks</a>
        </div>
    </div>

    <div class="menu-container">

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)):
                $p_id = $row['id'];
                // Cek apakah produk ini ada di keranjang user?
                $in_cart = isset($cart_map[$p_id]);
                $qty_now = $in_cart ? $cart_map[$p_id] : 0;
            ?>

                <div class="product-card" onclick="showDetail(
                    '<?= htmlspecialchars($row['nama_produk'], ENT_QUOTES); ?>', 
                    '<?= htmlspecialchars($row['deskripsi'], ENT_QUOTES); ?>', 
                    'Rp <?= number_format($row['harga'], 0, ',', '.'); ?>', 
                    'assets/img/products/<?= $row['foto']; ?>'
                )">

                    <div class="product-img-box">
                        <img src="assets/img/products/<?= $row['foto']; ?>" alt="<?= $row['nama_produk']; ?>">
                        <span class="category-badge"><?= ucfirst($row['kategori']); ?></span>
                    </div>

                    <div class="product-info">
                        <h3 class="product-title"><?= htmlspecialchars($row['nama_produk']); ?></h3>
                        <p class="product-desc"><?= htmlspecialchars(substr($row['deskripsi'], 0, 60)) . '...'; ?></p>

                        <div class="product-footer">
                            <span class="product-price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>

                            <div class="product-actions" onclick="event.stopPropagation()"> <?php if ($is_logged_in): ?>

                                    <?php if ($in_cart): ?>
                                        <div class="qty-control-card">
                                            <a href="actions/cart_controller.php?act=decrease_product&product_id=<?= $p_id; ?>" class="btn-qty-card">
                                                <i class="fas fa-minus"></i>
                                            </a>

                                            <span class="qty-val-card"><?= $qty_now; ?></span>

                                            <a href="actions/cart_controller.php?act=add&product_id=<?= $p_id; ?>" class="btn-qty-card">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>

                                    <?php else: ?>
                                        <a href="actions/cart_controller.php?act=add&product_id=<?= $p_id; ?>" class="btn-action-cart">
                                            <i class="fas fa-plus"></i> </a>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <a href="login.php" class="btn-action-cart" onclick="alertLogin(event)">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-mug-hot fa-3x" style="margin-bottom: 15px; opacity: 0.3;"></i>
                <h3>Menu tidak ditemukan</h3>
                <p>Belum ada produk untuk kategori ini.</p>
                <a href="menu.php" style="color: #8b5e3c;">Lihat semua menu</a>
            </div>
        <?php endif; ?>

    </div>

    <?php if ($is_logged_in && $total_item_cart > 0): ?>
        <a href="cart.php" class="floating-cart">
            <div class="cart-icon-bubble">
                <i class="fas fa-shopping-basket"></i>
                <span class="cart-count-badge"><?= $total_item_cart; ?></span>
            </div>
            <div class="cart-text-info">
                <span class="total-items-text"><?= $total_item_cart; ?> Item</span>
                <span class="view-cart-text">See My Cart</span>
            </div>
        </a>
    <?php endif; ?>

    <div class="modal-overlay" id="productModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <img src="" alt="Product Image" class="modal-img" id="modalImg">
            <h3 class="modal-title" id="modalTitle">Nama Produk</h3>
            <span class="modal-price" id="modalPrice">Rp 0</span>
            <p class="modal-desc" id="modalDesc"></p>

            <?php if ($is_logged_in): ?>
                <a href="cart.php" class="filter-btn active w-100" style="display:block; text-align:center; text-decoration:none; border:none;">Lihat di Keranjang</a>
            <?php else: ?>
                <a href="login.php" class="filter-btn active w-100" style="display:block; text-align:center; text-decoration:none; border:none;">Login untuk Pesan</a>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // 1. CEK POSISI: Saat halaman dimuat, cek apakah ada posisi scroll yang disimpan?
            const scrollPos = localStorage.getItem('scrollPosition');

            if (scrollPos) {
                // Kembalikan ke posisi semula
                window.scrollTo(0, parseInt(scrollPos));
                // Hapus data agar tidak mengganggu jika user refresh manual
                localStorage.removeItem('scrollPosition');
            }

            // 2. SIMPAN POSISI: Saat tombol Add/Plus/Minus diklik
            // Kita targetkan semua elemen yang punya class tombol aksi keranjang
            const actionButtons = document.querySelectorAll('.btn-action-cart, .btn-qty-card');

            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Simpan posisi scroll vertikal (Y) ke penyimpanan browser
                    localStorage.setItem('scrollPosition', window.scrollY);
                });
            });

        });

        function showDetail(nama, deskripsi, harga, gambar) {
            document.getElementById('modalTitle').innerText = nama;
            document.getElementById('modalDesc').innerText = deskripsi;
            document.getElementById('modalPrice').innerText = harga;
            document.getElementById('modalImg').src = gambar;

            const modal = document.getElementById('productModal');
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('productModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target == modal) closeModal();
        }

        function alertLogin(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Belum Login',
                text: "Silahkan login terlebih dahulu untuk memesan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Login Sekarang',
                confirmButtonColor: '#8b5e3c'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = 'login.php';
            });
        }
    </script>

    <?php if (isset($_SESSION['success'])): ?>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

</body>

</html>