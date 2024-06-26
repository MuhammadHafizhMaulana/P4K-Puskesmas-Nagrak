<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit(); // Keluar dari skrip setelah redirect
}

// Memeriksa apakah ada data yang dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include 'koneksi.php';
    // Menyiapkan statement UPDATE dengan prepared statement
    $query = "UPDATE `kesehatan_user` SET `goldar`=?, `usia_kandungan`=?, `tanggal_input=NOW()` WHERE id_user=?";
    $stmt = mysqli_prepare($connect, $query);

    // Memeriksa apakah persiapan statement berhasil
    if ($stmt) {
        // Mengikat parameter ke statement
        mysqli_stmt_bind_param($stmt, "sii", $_POST['goldar'], $_POST['usia_kandungan'], $_SESSION['id']);

        // Mengeksekusi statement
        $result = mysqli_stmt_execute($stmt);

        // Memeriksa apakah eksekusi berhasil
        if ($result) {
            header('Location: ../profile.php');
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
