<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses Ditolak!'); window.location='index.php';</script>"; exit;
}

if (isset($_POST['add_course'])) {
    $t=$_POST['title']; $d=$_POST['description']; $m=$_POST['mentor']; $du=$_POST['duration']; $l=$_POST['level']; $p=isset($_POST['price'])?$_POST['price']:0;
    $stmt = $conn->prepare("INSERT INTO courses (title, description, mentor, duration, level, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $t, $d, $m, $du, $l, $p);
    if ($stmt->execute()) { echo "<script>alert('Berhasil!'); window.location='admin.php';</script>"; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head><title>Admin</title><link rel="stylesheet" href="style.css"></head>
<body>
    <nav>
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin.php" class="text-yellow font-bold">Dashboard</a></li>
            <li><a href="admin_transactions.php">Transaksi</a></li>
            <li><a href="index.php" target="_blank">Lihat Web</a></li>
            <li><a href="cart.php">🛒 Cart (<?php echo isset($_SESSION['cart'])?count($_SESSION['cart']):0; ?>)</a></li>
            <li><a href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
        </ul>
    </nav>
    <div class="admin-container">
        <h1>Dashboard Administrator</h1>
        <hr class="mb-20">
        <div class="admin-panel">
            <div class="panel-left">
                <h3>+ Tambah Materi</h3>
                <form method="POST">
                    <div class="form-group"><label>Judul</label><input type="text" name="title" required></div>
                    <div class="form-group"><label>Mentor</label><input type="text" name="mentor" required></div>
                    <div class="form-group"><label>Durasi</label><input type="text" name="duration" required></div>
                    <div class="form-group"><label>Harga (Rp)</label><input type="number" name="price" required></div>
                    <div class="form-group"><label>Level</label><select name="level"><option value="Pemula">Pemula</option><option value="Menengah">Menengah</option><option value="Mahir">Mahir</option></select></div>
                    <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="4" required></textarea></div>
                    <button type="submit" name="add_course" class="btn">Simpan</button>
                </form>
            </div>
            <div class="panel-right">
                <h3>Daftar Users</h3>
                <div class="table-scroll mb-20">
                    <table>
                        <thead><tr><th>User</th><th>Role</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php $users=$conn->query("SELECT * FROM users ORDER BY id DESC"); while($u=$users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                <td><?php echo $u['role']; ?></td>
                                <td><?php if($u['id']!=$_SESSION['user_id']): ?><a href="admin.php?delete_user=<?php echo $u['id']; ?>" class="delete-btn" onclick="return confirm('Hapus?')">Hapus</a><?php endif; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <h3>Daftar Courses</h3>
                <div class="table-scroll">
                    <table>
                        <thead><tr><th>Judul</th><th>Harga</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php $courses=$conn->query("SELECT * FROM courses ORDER BY id DESC"); while($c=$courses->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($c['title']); ?></td>
                                <td>Rp <?php echo number_format($c['price'],0,',','.'); ?></td>
                                <td><a href="admin.php?delete_course=<?php echo $c['id']; ?>" class="delete-btn" onclick="return confirm('Hapus?')">Hapus</a></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>