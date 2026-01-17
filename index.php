<?php
session_start();
include 'db.php';

// Auto Login Logic
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $cid = $_COOKIE['user_id'];
    $check = $conn->query("SELECT * FROM users WHERE id = $cid");
    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home - E-Learning Academy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>
        <h2>E-Learning Academy</h2>
        <ul>
            <li><a href="index.php" class="text-yellow font-bold">Home</a></li>
            <li><a href="courses.php">Katalog</a></li>
            <li>
                <a href="cart.php" class="text-white">
                    🛒 Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
                </a>
            </li>

            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="my_courses.php">Kelas Saya</a></li>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li><a href="admin.php" class="text-red font-bold">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php" onclick="return confirm('Yakin ingin keluar?');">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="hero">
        <h1>Selamat Datang di E-Learning Academy</h1>
        <p>Platform Belajar Coding Terbaik untuk Pemula hingga Mahir</p>
        <br>
        <a href="courses.php" class="btn bg-yellow text-dark">Lihat Semua Kursus</a>
    </div>

    <div class="container mt-30">
        
        <h2 class="section-title text-center mb-20">Kenapa Belajar di Sini?</h2>
        
        <div class="features-grid">
            <div class="feature-item">
                <h3 class="text-center">📚 Materi Terupdate</h3>
                <p class="text-center">Modul pembelajaran selalu diperbarui mengikuti standar industri terkini.</p>
            </div>
            <div class="feature-item">
                <h3 class="text-center">💻 Praktek Langsung</h3>
                <p class="text-center">Belajar dengan studi kasus nyata dan membangun portofolio.</p>
            </div>
            <div class="feature-item">
                <h3 class="text-center">🏆 Sertifikat Digital</h3>
                <p class="text-center">Dapatkan sertifikat kompetensi setelah menyelesaikan kelas.</p>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center p-20 mt-30">
        <p>&copy; 2023 E-Learning Academy. All Rights Reserved.</p>
    </footer>

</body>
</html>