<?php
session_start();
include 'db.php';
if (!isset($_GET['id'])) { header("Location: courses.php"); exit; }
$cid = $_GET['id']; $uid = isset($_SESSION['user_id'])?$_SESSION['user_id']:null;
$stmt=$conn->prepare("SELECT * FROM courses WHERE id=?"); $stmt->bind_param("i",$cid); $stmt->execute(); $c=$stmt->get_result()->fetch_assoc();
if(!$c){ header("Location:courses.php"); exit; }

$enrolled=false;
if($uid){ $chk=$conn->prepare("SELECT id FROM enrollments WHERE user_id=? AND course_id=?"); $chk->bind_param("ii",$uid,$cid); $chk->execute(); if($chk->get_result()->num_rows>0)$enrolled=true; }
$incart = (isset($_SESSION['cart']) && in_array($cid, $_SESSION['cart']));
?>
<!DOCTYPE html>
<html>
<head><title><?php echo htmlspecialchars($c['title']); ?></title><link rel="stylesheet" href="style.css"></head>
<body>
    <nav>
        <h2>E-Learning Academy</h2>
        <ul>
            <li><a href="courses.php">Katalog</a></li>
            <li><a href="cart.php" class="text-yellow font-bold">🛒 Cart (<?php echo isset($_SESSION['cart'])?count($_SESSION['cart']):0; ?>)</a></li>
            <?php if($uid): ?><li><a href="my_courses.php">Kelas Saya</a></li><?php endif; ?>
        </ul>
    </nav>
    <div class="container mt-30">
        <div class="detail-card">
            <div class="w-100">
                <h1><?php echo htmlspecialchars($c['title']); ?></h1>
                <h2 class="text-green mt-10">Rp <?php echo number_format($c['price'],0,',','.'); ?></h2>
                <div class="meta-info">
                    <p><strong>Mentor:</strong> <?php echo htmlspecialchars($c['mentor']); ?></p>
                    <p><strong>Durasi:</strong> <?php echo htmlspecialchars($c['duration']); ?></p>
                    <p><strong>Level:</strong> <?php echo htmlspecialchars($c['level']); ?></p>
                </div>
                <p><?php echo nl2br(htmlspecialchars($c['description'])); ?></p>
                <hr class="separator">
                <?php if($enrolled): ?>
                    <div class="success-msg">✅ Anda sudah memiliki kelas ini.</div>
                    <a href="my_courses.php" class="btn bg-green">Lanjut Belajar</a>
                <?php elseif($incart): ?>
                    <div class="info-msg">🛒 Sudah di keranjang.</div>
                    <a href="cart.php" class="btn">Lihat Cart</a>
                <?php else: ?>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="course_id" value="<?php echo $c['id']; ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-large bg-yellow text-dark">+ Keranjang</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 