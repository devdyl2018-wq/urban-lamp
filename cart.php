<?php
session_start();
include 'db.php';
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
if (isset($_POST['add_to_cart'])) {
    $cid = $_POST['course_id'];
    if (!in_array($cid, $_SESSION['cart'])) { $_SESSION['cart'][] = $cid; echo "<script>alert('Masuk Keranjang!'); window.location='cart.php';</script>"; } 
    else { echo "<script>alert('Sudah ada!'); window.location='cart.php';</script>"; }
    exit;
}
if (isset($_GET['remove'])) {
    $rem = $_GET['remove'];
    if (($k = array_search($rem, $_SESSION['cart'])) !== false) { unset($_SESSION['cart'][$k]); $_SESSION['cart'] = array_values($_SESSION['cart']); }
    header("Location: cart.php"); exit;
}
$cart_courses = []; $total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', $_SESSION['cart']);
    $res = $conn->query("SELECT * FROM courses WHERE id IN ($ids)");
    while($row = $res->fetch_assoc()) { $cart_courses[] = $row; $total += $row['price']; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Cart</title><link rel="stylesheet" href="style.css"></head>
<body>
    <nav>
        <h2>E-Learning Academy</h2>
        <ul>
            <li><a href="courses.php">Katalog</a></li>
            <li><a href="cart.php" class="text-yellow font-bold">Keranjang (<?php echo count($_SESSION['cart']); ?>)</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="my_courses.php">Kelas Saya</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="container">
        <h1 class="section-title">Keranjang Belanja</h1>
        <?php if(empty($cart_courses)): ?>
            <div class="text-center p-40">
                <h3>Keranjang Anda kosong 🛒</h3>
                <a href="courses.php" class="btn mt-20">Lihat Katalog</a>
            </div>
        <?php else: ?>
            <table>
                <thead><tr><th>Course</th><th>Mentor</th><th>Harga</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach($cart_courses as $item): ?>
                    <tr>
                        <td class="font-bold"><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo htmlspecialchars($item['mentor']); ?></td>
                        <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                        <td><a href="cart.php?remove=<?php echo $item['id']; ?>" class="delete-btn" onclick="return confirm('Hapus?')">Hapus</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="revenue-box">
                <p>Total Pembayaran:</p>
                <h2>Rp <?php echo number_format($total, 0, ',', '.'); ?></h2>
                
                <div class="mt-20">
                    <?php if(isset($_SESSION['user_id'])): ?>   
                        <form action="checkout.php" method="POST">
                            <button type="submit" name="checkout" class="btn bg-yellow text-dark font-bold" style="padding: 15px 40px; font-size: 1.2em;">
                                Checkout Sekarang ➔
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="login.php" class="btn bg-red">Login untuk Checkout</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>