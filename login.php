<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] == 'admin' ? 'admin.php' : 'index.php'));
    exit;
}
$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        $error = "Username dan Password harus diisi.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Cek Password Plain Text (Sesuai request terakhir)
            if ($password === $row['password']) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                if ($remember) {
                    setcookie('user_id', $row['id'], time() + 604800, "/");
                    setcookie('username', $row['username'], time() + 604800, "/");
                }
                header("Location: " . ($row['role'] == 'admin' ? 'admin.php' : 'index.php'));
                exit;
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2 class="text-center mb-20">Login</h2>
        
        <?php if($error): ?>
            <p class="error-msg"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group d-flex flex-center gap-10">
                <input type="checkbox" name="remember" id="rem" class="w-auto">
                <label for="rem" class="mb-0">Remember Me</label>
            </div>
            
            <button type="submit" name="login" class="btn w-100">Login</button>
        </form>
        
        <p class="text-center mt-20">
            Belum punya akun? <a href="register.php" class="text-blue font-bold">Register</a>
        </p>
    </div>
</body>
</html>