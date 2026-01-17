<?php
include 'db.php';
$error = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'];

    if (empty($username) || strlen($username) < 4 || strlen($username) > 20) {
        $error = "Username harus antara 4 - 20 karakter.";
    } elseif (!str_ends_with($email, '@gmail.com')) {
        $error = "Email harus menggunakan domain '@gmail.com'.";
    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter.";
    } elseif ($password !== $conf_password) {
        $error = "Konfirmasi Password tidak cocok.";
    } elseif (empty($gender)) {
        $error = "Silakan pilih Gender.";
    } elseif (empty($dob) || $dob >= date('Y-m-d')) {
        $error = "Tanggal lahir harus di masa lalu.";
    } else {
        $check = $conn->query("SELECT id FROM users WHERE username='$username' OR email='$email'");
        if ($check->num_rows > 0) {
            $error = "Username atau Email sudah terdaftar!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, gender, dob) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $password, $gender, $dob);
            
            if ($stmt->execute()) {
                echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='login.php';</script>";
                exit;
            } else {
                $error = "Terjadi kesalahan sistem.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2 class="text-center mb-20">Register</h2>
        
        <?php if($error): ?>
            <p class="error-msg"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="conf_password" required>
            </div>
            
            <div class="form-group">
                <label>Gender</label>
                <div class="d-flex gap-10">
                    <label class="d-flex flex-center gap-10">
                        <input type="radio" name="gender" value="Male" class="w-auto"> Male
                    </label>
                    <label class="d-flex flex-center gap-10">
                        <input type="radio" name="gender" value="Female" class="w-auto"> Female
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" required>
            </div>
            
            <button type="submit" name="register" class="btn w-100">Register</button>
        </form>
        
        <p class="text-center mt-20">
            Sudah punya akun? <a href="login.php" class="font-bold">Login</a>
        </p>
    </div>
</body>
</html>