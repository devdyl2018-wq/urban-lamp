<?php
session_start();
include 'db.php';

// 1. Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit;
}

// 2. Validasi Parameter URL
if (!isset($_GET['course_id'])) {
    header("Location: my_courses.php"); exit;
}

$course_id = intval($_GET['course_id']);
$user_id = $_SESSION['user_id'];

// 3. Cek Enrollment (Keamanan: User wajib beli kursus ini)
$check_enroll = $conn->query("SELECT id FROM enrollments WHERE user_id = $user_id AND course_id = $course_id");
if ($check_enroll->num_rows == 0) {
    echo "<script>alert('Akses Ditolak! Anda belum mengambil kelas ini.'); window.location='courses.php';</script>";
    exit;
}

// 4. Ambil Daftar Semua Modul (Untuk Sidebar)
$sql_modules = "SELECT * FROM course_modules WHERE course_id = $course_id ORDER BY sort_order ASC";
$res_modules = $conn->query($sql_modules);
$modules = [];
while ($row = $res_modules->fetch_assoc()) {
    $modules[] = $row;
}

// 5. Tentukan Modul Aktif (Current Module)
// Jika ada ?module_id=... pakai itu, jika tidak pakai modul pertama (urutan 1)
$current_module = null;
if (isset($_GET['module_id'])) {
    $mod_id = intval($_GET['module_id']);
    foreach ($modules as $m) {
        if ($m['id'] == $mod_id) {
            $current_module = $m;
            break;
        }
    }
} else {
    // Default ke modul pertama jika tidak ada di URL
    if (!empty($modules)) {
        $current_module = $modules[0];
    }
}

// Jika modul tidak ditemukan (misal course kosong)
if (!$current_module) {
    echo "<script>alert('Materi belum tersedia untuk kursus ini.'); window.location='my_courses.php';</script>";
    exit;
}

// 6. Logika NEXT MODULE
$next_module_id = null;
foreach ($modules as $index => $m) {
    if ($m['id'] == $current_module['id']) {
        // Cek apakah masih ada modul setelah ini?
        if (isset($modules[$index + 1])) {
            $next_module_id = $modules[$index + 1]['id'];
        }
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Belajar: <?php echo htmlspecialchars($current_module['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Style Tambahan Khusus Halaman Ini */
        .module-list { list-style: none; padding: 0; }
        .module-item a { display: block; padding: 12px; border-bottom: 1px solid #eee; transition: 0.2s; }
        .module-item a:hover { background: #f9f9f9; padding-left: 15px; }
        .module-item.active a { background: #e8f8f5; border-left: 4px solid #27ae60; font-weight: bold; color: #27ae60; }
        .video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; background: #000; }
        .video-wrapper iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
    </style>
</head>
<body>

    <nav>
        <h2>E-Learning Academy</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="my_courses.php" class="text-yellow font-bold">Kelas Saya</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container mt-30">
        <div class="mb-20">
            <a href="my_courses.php" class="text-small">← Kembali ke Dashboard</a>
            <h2 class="mt-10"><?php echo htmlspecialchars($current_module['title']); ?></h2>
        </div>

        <div class="d-flex gap-20 flex-wrap">
            
            <div style="flex: 3; min-width: 300px;">
                <div class="card p-20">
                    <?php if ($current_module['video_url']): ?>
                        <div class="video-wrapper mb-20">
                            <iframe src="<?php echo htmlspecialchars($current_module['video_url']); ?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    <?php else: ?>
                        <div class="p-40 bg-grey text-center mb-20 text-white">Tidak ada video untuk materi ini.</div>
                    <?php endif; ?>
                    
                    <h3>Deskripsi Materi</h3>
                    <p class="mt-10 text-dark"><?php echo nl2br(htmlspecialchars($current_module['description'])); ?></p>

                    <hr class="separator mt-30 mb-20">

                    <div class="d-flex" style="justify-content: space-between;">
                        <button class="btn bg-grey" onclick="alert('Fitur Diskusi segera hadir!')">💬 Tanya Mentor</button>
                        
                        <?php if ($next_module_id): ?>
                            <a href="learning.php?course_id=<?php echo $course_id; ?>&module_id=<?php echo $next_module_id; ?>" class="btn bg-green">
                                Lanjut Materi Berikutnya →
                            </a>
                        <?php else: ?>
                            <a href="my_courses.php" class="btn bg-yellow text-dark" onclick="return confirm('Selamat! Anda telah menyelesaikan semua materi.')">
                                🎉 Selesai Kursus
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div style="flex: 1; min-width: 250px;">
                <div class="card p-0">
                    <div class="p-20 bg-dark text-white" style="border-radius: 8px 8px 0 0;">
                        <h3 class="text-white mb-0" style="font-size: 1.1em;">Daftar Materi</h3>
                    </div>
                    <ul class="module-list">
                        <?php foreach ($modules as $m): ?>
                            <li class="module-item <?php echo ($m['id'] == $current_module['id']) ? 'active' : ''; ?>">
                                <a href="learning.php?course_id=<?php echo $course_id; ?>&module_id=<?php echo $m['id']; ?>" class="d-flex gap-10 flex-center">
                                    <span><?php echo ($m['id'] == $current_module['id']) ? '▶' : '📄'; ?></span>
                                    <span><?php echo htmlspecialchars($m['title']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>

</body>
</html>