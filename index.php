<?php
session_start();
include 'config/koneksi.php';

// Cek status login agar kodingan HTML di bawah lebih bersih
$is_logged_in = isset($_SESSION['status']) && $_SESSION['status'] == "login";
$user_name = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OruCoffee - Brewed with Passion</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <section class="hero" id="home">
        <div class="hero-content">
            <div class="hero-text">
                <h1>A Coffee Ritual<br>for Every Mood</h1>
                <p>
                    Crafted with passion, brewed with purpose.
                    Discover coffee that brings warmth, balance,
                    and comfort in every single cup at <strong>OruCoffee</strong>.
                </p>
                <a href="#menu" class="btn-primary">Order Now</a>
            </div>

            <div class="hero-image">
                <img src="assets/img/hero-image.png" alt="OruCoffee Hero Image">
            </div>
        </div>
    </section>

    <section class="best-seller" id="best-seller">
        <div class="container">

            <div class="best-seller-header">
                <h2>Best Seller Choices</h2>
                <p>Our most loved coffee and food, chosen by our loyal customers.</p>
            </div>

            <div class="best-seller-grid">

                <?php
                $query_best = "SELECT * FROM products ORDER BY RAND() LIMIT 5";
                $result_best = mysqli_query($koneksi, $query_best);

                if (mysqli_num_rows($result_best) > 0) {
                    while ($row = mysqli_fetch_assoc($result_best)) {
                ?>
                        <div class="menu-card" onclick="showDetail(
                        '<?= htmlspecialchars($row['nama_produk'], ENT_QUOTES); ?>', 
                        '<?= htmlspecialchars($row['deskripsi'], ENT_QUOTES); ?>', 
                        'Rp <?= number_format($row['harga'], 0, ',', '.'); ?>', 
                        'assets/img/products/<?= $row['foto']; ?>',
                        '<?= $row['id']; ?>'
                    )">

                            <div class="card-img-wrapper">
                                <img src="assets/img/products/<?= $row['foto']; ?>" alt="<?= $row['nama_produk']; ?>">
                            </div>

                            <div class="card-content">
                                <h3><?= htmlspecialchars($row['nama_produk']); ?></h3>
                                <span class="price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>

                                <div class="card-footer-simple">
                                    <span class="link-text">Lihat Detail</span>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>

                        </div>
                <?php
                    }
                } else {
                    echo "<p style='text-align:center; width:100%; color:#999;'>Belum ada menu tersedia.</p>";
                }
                ?>

            </div>
        </div>

        <div class="modal-overlay" id="productModal">
            <div class="modal-content">
                <span class="modal-close" onclick="closeModal()">&times;</span>

                <img src="" alt="Product Image" class="modal-img" id="modalImg">

                <h3 class="modal-title" id="modalTitle">Product Name</h3>
                <span class="modal-price" id="modalPrice">Rp 0</span>
                <p class="modal-desc" id="modalDesc">Product Descript... </p>

                <div id="modalBtnContainer">
                </div>
            </div>
        </div>
    </section>

    <section class="our-menu" id="menu" style="background-color: #F9F4F0;">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-5 text-center mb-5 mb-lg-0">
                    <div class="position-relative">
                        <img src="assets/img/ourmenu-img.png"
                            alt="Menu Favorit"
                            class="img-fluid floating-animate"
                            style="filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1)); max-height: 450px;">
                    </div>
                </div>

                <div class="col-lg-6 offset-lg-1 text-start">
                    <h2 class="section-title">Our Complete Menu</h2>

                    <p class="section-desc">
                        Discover our carefully curated selections crafted to satisfy every taste. From warm pastries to premium coffee, we have everything to make your day better.
                    </p>

                    <div class="menu-action">
                        <a href="menu.php?kategori=minuman" class="btn-outline">
                            Coffee & Drinks
                        </a>
                        <a href="menu.php?kategori=makanan" class="btn btn-primary btn-lg rounded-pill px-4 border-0 shadow-sm" style="background: #8b5e3c;">
                            Food & Snacks
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="gallery" id="gallery">
        <h2 class="section-title">Places</h2>
        <p class="section-desc">A glimpse of our cozy space at OurCoffee.</p>

        <div class="gallery-grid">
            <div class="gallery-item big">
                <img src="assets/img/gallery1.jpg" alt="Interior 1">
            </div>
            <div class="gallery-item">
                <img src="assets/img/gallery2.jpg" alt="Interior 2">
            </div>
            <div class="gallery-item">
                <img src="assets/img/gallery3.jpg" alt="Detail Coffee">
            </div>
            <div class="gallery-item wide">
                <img src="assets/img/gallery4.jpg" alt="Barista">
            </div>
        </div>
    </section>

    <section class="about-us" id="about">
        <div class="about-us-container">
            <div class="about-us-image">
                <img src="assets/img/about-img.jpg" alt="About OurCoffee">
            </div>
            <div class="about-us-text">
                <h2>About OruCoffee</h2>
                <p class="about-preview">
                    Founded in 2024, OruCoffee was built from a deep passion for crafting
                    high-quality coffee and creating a warm, welcoming space.
                </p>

                <div class="about-more" id="aboutMore">
                    <p>
                        We select premium beans from trusted farmers to ensure rich aroma.
                        Our mission is to build meaningful connections.
                    </p>
                </div>

                <button class="about-toggle" onclick="toggleAbout()">Read More</button>
            </div>
        </div>
    </section>

    <section class="testimonial" id="testimonial">
        <h2 class="section-title">Customer Stories</h2>
        <p class="section-desc">Hear what they say about OruCoffee.</p>

        <div class="testi-grid">
            <div class="testi-card">
                <div class="testi-avatar">
                    <img src="assets/img/user1.jpg" alt="User 1">
                </div>
                <p class="testi-text">"The atmosphere feels so warm and cozy!"</p>
                <h4>Sarah Johnson</h4>
            </div>
            <div class="testi-card">
                <div class="testi-avatar">
                    <img src="assets/img/user2.jpg" alt="User 2">
                </div>
                <p class="testi-text">"Best Americano in town. Friendly staff too."</p>
                <h4>Michael Lee</h4>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <h2>Contact Us</h2>
        <p>Have questions? Leave us a message.</p>

        <form class="contact-form">
            <div class="form-row">
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Your Email" required>
            </div>
            <textarea placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </section>

    <?php include 'includes/footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const userLoggedIn = <?= $is_logged_in ? 'true' : 'false'; ?>;

        function showDetail(nama, deskripsi, harga, gambar, productId) {
            // 1. Isi Data ke Modal
            document.getElementById('modalTitle').innerText = nama;
            document.getElementById('modalDesc').innerText = deskripsi;
            document.getElementById('modalPrice').innerText = harga;
            document.getElementById('modalImg').src = gambar;

            // 2. Atur Tombol Aksi
            const btnContainer = document.getElementById('modalBtnContainer');

            if (userLoggedIn) {
                // Jika Login: Tombol langsung tambah ke keranjang (via Controller)
                btnContainer.innerHTML = `
                <a href="actions/cart_controller.php?act=add&product_id=${productId}" class="btn-modal-action">
                    <i class="fas fa-shopping-cart me-2"></i> Pesan Sekarang
                </a>
            `;
            } else {
                // Jika Belum Login: Tombol arahkan ke Login page
                btnContainer.innerHTML = `
                <a href="login.php" class="btn-modal-action">
                    Login untuk Memesan
                </a>
            `;
            }

            // 3. Tampilkan Modal dengan Animasi
            const modal = document.getElementById('productModal');
            modal.style.display = 'flex';
            // Delay dikit biar transisi CSS jalan
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('productModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300); // Sesuaikan durasi transition di CSS
        }

        // Tutup jika klik di luar area putih
        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        if (document.querySelector('#home')) {

            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.scroll-link');

            window.addEventListener('scroll', () => {
                let current = '';

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;

                    // Angka 150 adalah kompensasi tinggi navbar biar akurat
                    if (pageYOffset >= (sectionTop - 150)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    // Cek apakah href link mengandung ID section yang sedang aktif
                    if (link.getAttribute('href').includes(current)) {
                        // Pastikan 'current' tidak kosong (untuk menghindari home nyala terus)
                        if (current !== '') {
                            link.classList.add('active');
                        }
                    }
                });

                // Khusus: Jika scroll paling atas, nyalakan Home secara paksa
                if (window.scrollY < 100) {
                    navLinks.forEach(link => link.classList.remove('active'));
                    document.querySelector('a[href*="#home"]').classList.add('active');
                }
            });
        }
        /* === TOGGLE MENU MOBILE === */
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }

        /* === TOGGLE READ MORE (ABOUT) === */
        function toggleAbout() {
            const moreText = document.getElementById('aboutMore');
            const btnText = document.querySelector('.about-toggle');

            if (moreText.style.display === "none" || moreText.style.display === "") {
                moreText.style.display = "block";
                btnText.innerHTML = "Read Less";
            } else {
                moreText.style.display = "none";
                btnText.innerHTML = "Read More";
            }
        }

        /* === TOGGLE DROPDOWN PROFILE === */
        function toggleProfile() {
            const dropdown = document.getElementById("profileDropdown");
            dropdown.classList.toggle("show-dropdown");
        }

        /* === TUTUP DROPDOWN KALAU KLIK DI LUAR === */
        window.onclick = function(event) {
            // Jika yang diklik BUKAN tombol profile atau elemen di dalamnya
            if (!event.target.matches('.profile-btn') && !event.target.closest('.profile-btn')) {
                const dropdown = document.getElementById("profileDropdown");
                if (dropdown && dropdown.classList.contains('show-dropdown')) {
                    dropdown.classList.remove('show-dropdown');
                }
            }
        }

        /* === LOGIKA POPUP LOGOUT (DARI URL) === */
        // Cek apakah ada ?pesan=logout di URL
        const urlParams = new URLSearchParams(window.location.search);
        const pesan = urlParams.get('pesan');

        if (pesan === 'logout') {
            Swal.fire({
                icon: 'success',
                title: 'Sampai Jumpa!',
                text: 'Anda berhasil logout.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Bersihkan URL supaya kalau direfresh popup ga muncul lagi
                window.history.replaceState(null, null, window.location.pathname);
            });
        }
    </script>

    <?php if (isset($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $_SESSION['success']; ?>', // Mengambil pesan dari PHP
                timer: 2000,
                showConfirmButton: false
            });
        </script>
        <?php unset($_SESSION['success']); // Hapus session biar ga muncul terus 
        ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= $_SESSION['error']; ?>',
                confirmButtonColor: '#8b5e3c'
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</body>

</html>