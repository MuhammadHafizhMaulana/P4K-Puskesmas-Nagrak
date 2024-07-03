<?php
session_start();
// Memeriksa status sesi admin
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit(); // Keluar dari skrip setelah redirect
}

include '../../proses/koneksi.php';

// Memeriksa metode HTTP POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $id_user = $_POST['id_user'];
    $deskripsi = $_POST['deskripsi'];

    // Menyiapkan statement UPDATE dengan prepared statement
    $query = "UPDATE `kb` SET deskripsi=? WHERE id_user=?";
    $stmt = mysqli_prepare($connect, $query);

    // Memeriksa apakah persiapan statement berhasil
    if ($stmt) {
        // Mengikat parameter ke statement
        mysqli_stmt_bind_param($stmt, "si", $deskripsi, $id_user);

        // Mengeksekusi statement
        $result = mysqli_stmt_execute($stmt);

        // Memeriksa apakah eksekusi berhasil
        if ($result) {
            // Redirect dengan parameter sukses
            header("Location: ../kb_user.php?id=$id_user&success=update_successful");
            exit();
        } else {
            // Redirect dengan parameter gagal
            header("Location: ../kb_user.php?id=$id_user&gagal=update_gagal");
            exit();
        }

    } else {
        // Tampilkan pesan kesalahan jika persiapan statement gagal
        echo "Persiapan statement gagal: " . mysqli_error($connect);
    }
} else {
    // Jika data tidak dikirim melalui metode POST, redirect ke halaman lain atau tampilkan pesan kesalahan
    echo "Data tidak ditemukan.";
}
?>
