<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit(); // Keluar dari skrip setelah redirect
}

include '../../proses/koneksi.php';

// Memeriksa apakah ada data yang dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menyiapkan statement UPDATE dengan prepared statement
    $query = "UPDATE `user` SET `nama`=?, `usia`=?, `nomorHP`=?, `alamat`=? WHERE id=?";
    $stmt = mysqli_prepare($connect, $query);

    // Memeriksa apakah persiapan statement berhasil
    if ($stmt) {
        // Mengikat parameter ke statement
        mysqli_stmt_bind_param($stmt, "sissi", $_POST['nama'], $_POST['usia'], $_POST['nomorHP'], $_POST['alamat'], $_POST['id']);

        // Mengeksekusi statement
        $result = mysqli_stmt_execute($stmt);

        // Memeriksa apakah eksekusi berhasil
        if ($result) {
            header('Location: ../data_user.php?succes=1');
            exit();
        } else {
            echo "Update data gagal: " . mysqli_error($connect);
        }
        // Menutup statement setelah digunakan
        mysqli_stmt_close($stmt);
    } else {
        echo "Persiapan statement gagal: " . mysqli_error($connect);
    }
} else {
    // Jika data tidak dikirim melalui metode POST, redirect ke halaman lain atau tampilkan pesan kesalahan
    echo "Data tidak ditemukan.";
}
?>
