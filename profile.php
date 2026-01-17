<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['user_id'];
$msg = ""; $error = "";

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
                            
if (isset($_POST['update_profile'])) {
    $u=$_POST['username']; $e=$_POST['email']; $g=$_POST['gender']; $d=$_POST['dob'];
    $check = $conn->query("SELECT id FROM users WHERE (username='$u' OR email='$e') AND id != $uid");
    if ($check->num_rows > 0) { $error = "Username/Email sudah terpakai."; } 
    else {
        $stmt=$conn->prepare("UPDATE users SET username=?, email=?, gender=?, dob=? WHERE id=?");
        $stmt->bind_param("ssssi", $u, $e, $g, $d, $uid);
        if($stmt->execute()){ $_SESSION['username']=$u; $user['username']=$u; $user['email']=$e; $user['gender']=$g; $user['dob']=$d; echo "<script>alert('Profil Updated');</script>"; }
    }
}

if (isset($_POST['update_password'])) {
    $curr = $_POST['current_password']; $new = $_POST['new_password']; $conf = $_POST['confirm_password'];
    if ($curr !== $user['password']) { $error = "Password lama salah."; } 
    elseif (strlen($new) < 8) { $error = "Password baru minimal 8 karakter."; } 
    elseif ($new !== $conf) { $error = "Konfirmasi password baru tidak cocok."; } 
    else {
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $new, $uid);
        if ($stmt->execute()) { $user['password'] = $new; echo "<script>alert('Password berhasil diubah!');</script>"; }
    }
}

if (isset($_POST['delete_account'])) {
    $pass = $_POST['delete_password_confirm'];
    if ($pass === $user['password']) {
        $conn->query("DELETE FROM users WHERE id=$uid");
        session_destroy();
        setcookie('user_id', '', time() - 3600, "/");
        setcookie('username', '', time() - 3600, "/");
        echo "<script>alert('Akun dihapus.'); window.location='login.php';</script>"; exit;
    } else { $error = "Password salah."; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <h2>E-Learning Academy</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="courses.php">Katalog</a></li>
            <li><a href="cart.php" class="text-white font-bold">🛒 Cart (<?php echo isset($_SESSION['cart'])?count($_SESSION['cart']):0; ?>)</a></li>
            <li><a href="my_courses.php">Kelas Saya</a></li>
            <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?>
                <li><a href="admin.php" class="text-red font-bold">Admin Panel</a></li>
            <?php endif; ?>
            <li><a href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
        </ul>
    </nav>
    
    <div class="container" style="max-width: 800px;">
        <h1 class="section-title">Manage Profile</h1>
        
        <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>

        <div class="form-container w-100 mb-20 mt-0 p-40">
            <h3>Update Info</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="Male" <?php if($user['gender']=='Male')echo'selected';?>>Male</option>
                        <option value="Female" <?php if($user['gender']=='Female')echo'selected';?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="<?php echo $user['dob']; ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn">Simpan Perubahan</button>
            </form>
        </div>

        <div class="form-container w-100 mb-20 mt-0 p-40">
            <h3>Ganti Password</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" name="update_password" class="btn bg-yellow text-dark">Ubah Password</button>
            </form>
        </div>

        <div class="form-container w-100 mb-20 mt-0 p-40" style="border: 1px solid #e74c3c;">
            <h3 class="text-red">Hapus Akun</h3>
            <form method="POST" onsubmit="return confirm('Yakin hapus akun?');">
                <div class="form-group">
                    <label>Password Konfirmasi</label>
                    <input type="password" name="delete_password_confirm" required>
                </div>
                <button type="submit" name="delete_account" class="btn bg-red">Hapus Akun Permanen</button>
            </form>
        </div>
    </div>
</body>
</html>