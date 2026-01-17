<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location:index.php"); exit; }
$q="SELECT enrollments.enroll_date, users.username, courses.title, courses.price FROM enrollments JOIN users ON enrollments.user_id=users.id JOIN courses ON enrollments.course_id=courses.id ORDER BY enrollments.enroll_date DESC";
$res=$conn->query($q); $total=0;
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Transaksi</title><link rel="stylesheet" href="style.css"></head>
<body>
    <nav>
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="admin_transactions.php" class="text-yellow font-bold">Transaksi</a></li>
            <li><a href="cart.php">🛒 Cart</a></li>
            <li><a href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
        </ul>
    </nav>
    <div class="container mt-30">
        <h1 class="section-title">Laporan Transaksi</h1>
        <?php $trans=[]; if($res){ while($row=$res->fetch_assoc()){ $trans[]=$row; $total+=$row['price']; } } ?>
        <div class="revenue-box">
            <p>Total Pendapatan</p>
            <h2>Rp <?php echo number_format($total,0,',','.'); ?></h2>
        </div>
        <div class="detail-card">
            <h3>Riwayat</h3>
            <?php if($trans): ?>
                <table>
                    <thead><tr><th>No</th><th>Waktu</th><th>User</th><th>Course</th><th>Harga</th></tr></thead>
                    <tbody>
                        <?php $no=1; foreach($trans as $t): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($t['enroll_date'])); ?></td>
                            <td class="font-bold"><?php echo htmlspecialchars($t['username']); ?></td>
                            <td><?php echo htmlspecialchars($t['title']); ?></td>
                            <td class="text-green">Rp <?php echo number_format($t['price'],0,',','.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Belum ada data.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>