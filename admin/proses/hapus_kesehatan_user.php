<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit(); // tambahkan exit setelah redirect
}

// Sambungan ke database
include '../../proses/koneksi.php';

// Periksa apakah parameter id ada di URL dan valid
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus pengguna berdasarkan id yang sudah didekripsi
    $query = "DELETE FROM kesehatan_user WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);

    if ($stmt) {
        // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Eksekusi statement
        $result = mysqli_stmt_execute($stmt);

        if($result) {
            // Redirect kembali ke halaman data pengguna dengan pesan sukses
            header('Location: ../listKesehatanUser.php?status=deleted');
            exit();
        } else {
            echo "Gagal menghapus pengguna.";
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($connect);
    }
} else {
    // Jika ID tidak valid, redirect kembali ke halaman data pengguna
    header('Location: ../data_user.php?status=invalid_id');
    exit();
}

// Tutup koneksi
mysqli_close($connect);
?>
