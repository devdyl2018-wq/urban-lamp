<?php
session_start();
include 'db.php';

$search_keyword = isset($_GET['q']) ? $_GET['q'] : "";
if ($search_keyword) {
    $search_query = "%" . $search_keyword . "%";
    $stmt = $conn->prepare("SELECT * FROM courses WHERE title LIKE ? OR description LIKE ? ORDER BY id DESC");
    $stmt->bind_param("ss", $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn, "SELECT * FROM courses ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Katalog</title><link rel="stylesheet" href="style.css"></head>
<body>
    <nav>
        <h2>E-Learning Academy</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="courses.php" class="text-yellow font-bold">Katalog</a></li>
            <li><a href="cart.php" class="text-white font-bold">🛒 Cart (<?php echo isset($_SESSION['cart'])?count($_SESSION['cart']):0; ?>)</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="my_courses.php" class="text-green font-bold">Kelas Saya</a></li>
                <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?><li><a href="admin.php" class="text-red">Admin</a></li><?php endif; ?>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="container">
        <h1 class="section-title">Katalog Kursus</h1>
        <form action="courses.php" method="GET" class="search-container">
            <input type="text" name="q" placeholder="Cari materi..." value="<?php echo htmlspecialchars($search_keyword); ?>">
            <button type="submit" class="search-btn">Cari</button>
        </form>
        <div class="course-grid">
            <?php if($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <div class="card-body">
                            <h3><a href="course_detail.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                            <p class="price-tag">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                            <div class="d-flex gap-10 flex-wrap text-small mb-10">
                                <span>👤 <?php echo htmlspecialchars($row['mentor']); ?></span>
                                <span>⏳ <?php echo htmlspecialchars($row['duration']); ?></span>
                                <span>📊 <?php echo htmlspecialchars($row['level']); ?></span>
                            </div>
                            <p><?php echo substr(htmlspecialchars($row['description']), 0, 90) . "..."; ?></p>
                            <hr class="separator">
                            <a href="course_detail.php?id=<?php echo $row['id']; ?>" class="btn">Lihat Detail</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center w-100 p-40">
                    <h3>Data tidak ditemukan 😔</h3>
                    <a href="courses.php" class="btn bg-grey mt-10">Reset</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>