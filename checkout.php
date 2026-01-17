<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
if (empty($_SESSION['cart'])) { header("Location: courses.php"); exit; }

if (isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id'];
    $cart_ids = $_SESSION['cart'];
    $success_count = 0;

    $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");

    foreach ($cart_ids as $course_id) {
        $check = $conn->query("SELECT id FROM enrollments WHERE user_id=$user_id AND course_id=$course_id");
        if ($check->num_rows == 0) {
            $stmt->bind_param("ii", $user_id, $course_id);
            if ($stmt->execute()) {
                $success_count++;
            }
        }
    }
    $_SESSION['cart'] = [];
    echo "<script>alert('Pembayaran Berhasil! $success_count course ditambahkan ke Kelas Saya.'); window.location='my_courses.php';</script>";
}
?>