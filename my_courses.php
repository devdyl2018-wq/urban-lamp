<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['user_id'];
$q = "SELECT courses.*, enrollments.enroll_date FROM courses JOIN enrollments ON courses.id=enrollments.course_id WHERE enrollments.user_id=? ORDER BY enrollments.enroll_date DESC";
$stmt=$conn->prepare($q); $stmt->bind_param("i", $uid); $stmt->execute(); $res=$stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Kelas Saya</title><link rel="stylesheet" href="style.css"></head>
<body>
    <nav>
        <h2>E-Learning Academy</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="courses.php">Katalog</a></li>
            <li><a href="cart.php">🛒 Cart (<?php echo isset($_SESSION['cart'])?count($_SESSION['cart']):0; ?>)</a></li>
            <li><a href="my_courses.php" class="text-yellow font-bold">Kelas Saya</a></li>
            <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?><li><a href="admin.php" class="text-red">Admin</a></li><?php endif; ?>
            <li><a href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
        </ul>
    </nav>
    <div class="container">
        <h1 class="section-title">Kelas Saya</h1>
        <div class="course-grid">
            <?php if($res->num_rows > 0): ?>
                <?php while($row = $res->fetch_assoc()): ?>
                    <div class="card card-enrolled">
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <div class="text-small mb-10">
                                <span>👨‍🏫 <?php echo htmlspecialchars($row['mentor']); ?></span><br>
                                <span>📅 <?php echo date('d M Y', strtotime($row['enroll_date'])); ?></span>
                            </div>
                            <p><?php echo substr(htmlspecialchars($row['description']), 0, 80) . "..."; ?></p>
                            <hr class="separator">
                            <a href="learning.php?course_id=<?php echo $row['id']; ?>" class="btn bg-green w-100 mt-10 text-center">
    ▶ Mulai Belajar
</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center p-40">
                    <h3>Anda belum mengambil kelas.</h3>
                    <a href="courses.php" class="btn mt-20">Cari Kelas</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>