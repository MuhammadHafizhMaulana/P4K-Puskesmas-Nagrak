<?php
session_start();

include '../../proses/koneksi.php';

// Ambil nomor HP dan password dari POST
$username = $_POST['username'];
$password = $_POST['password'];

// Persiapkan query dengan prepared statement
$query = "SELECT * FROM `admin` WHERE `username` = ?";
$stmt = mysqli_prepare($connect, $query);

if ($stmt) {
    // Bind parameter ke placeholder
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Eksekusi prepared statement
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah ada baris hasil
    if(mysqli_num_rows($result) > 0) {
        // Ambil data pengguna dari hasil query
        $data = mysqli_fetch_assoc($result);
        
        // Periksa apakah nomor HP sesuai dengan data dari hasil query
        if ($_POST['username'] === $data['username'] && password_verify($_POST['password'], $data['password'])) {
            // Cek Session
            $_SESSION['id'] = $data['id'];
            $_SESSION['status'] = 'login_admin';

            // Cek Cookie
            if(isset($_POST['remember'])){ 
                // buat cookie
                setcookie('yadi', $data['id'], time() + (86400 * 30), "/");
                setcookie('keyli', hash('sha256', $data['username']), time() + (86400 * 30), "/");
            }

            // Jika sesuai, redirect ke halaman home
            header('Location: ../landing.php');
            exit();
        } else {
            // Jika tidak sesuai, redirect ke halaman login dengan pesan gagal
            header('Location: ../login_admin.php?pesan=gagal');
            exit();
        }
    } else {
        // Jika tidak ada baris hasil, redirect ke halaman login dengan pesan gagal
        header('Location: ../login_admin.php?pesan=gagal');
        exit();
    }
} else {
    // Jika persiapan statement gagal, tangani kesalahan
    echo "Error: " . mysqli_error($connect);
}

// Tutup koneksi
mysqli_close($connect);
?>
