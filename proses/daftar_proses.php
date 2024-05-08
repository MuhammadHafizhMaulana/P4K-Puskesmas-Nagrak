<?php

session_start();
if(isset($_SESSION['status']) || $_SESSION['status'] == 'login'){
header('Location: home.php');
}


// Sambungan ke koneksi
include 'koneksi.php';

// Inisialisasi data dari POST
$nama = $_POST['nama'];
$usia = $_POST['usia'];
$nomorHP = $_POST['nomorHP'];
$alamat = $_POST['alamat'];
$password = $_POST['password'];

// Validasi password dengan pola tertentu
$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>])(?=.*[0-9]).{8,}$/';
if (!preg_match($passwordPattern, $password)) {
    header('Location: ../daftar.php');
    exit(); // Batalkan proses jika password tidak sesuai pola
}

// Validasi nomor HP dengan pola tertentu
$nomorHPPattern = '/^[0-9]+$/';
if (!preg_match($nomorHPPattern, $nomorHP)) {
    exit(); // Batalkan proses jika nomor HP tidak sesuai pola
}

// Persiapkan query dengan prepared statement
$query = "INSERT INTO `user`(`nama`, `usia`, `nomorHP`, `alamat`, `password`) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($connect, $query);

if ($stmt) {
    // Bind parameter ke placeholder
    mysqli_stmt_bind_param($stmt, "sisss", $nama, $usia, $nomorHP, $alamat, $password);

    // Jalankan prepared statement
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Redirect jika berhasil
        header('Location: ../index.php?success=1');
        exit;
    } else {
        // Tampilkan pesan jika gagal
        echo "Tambah data gagal: " . mysqli_stmt_error($stmt);
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    // Tampilkan pesan jika persiapan statement gagal
    echo "Error: " . mysqli_error($connect);
}

// Tutup koneksi
mysqli_close($connect);
?>
