<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}

// Sambungan ke koneksi
include 'koneksi.php';

// Inisialisasi data dari POST
$goldar = $_POST['goldar'];
$usia_kandungan = $_POST['usia_kandungan'];
$id = $_SESSION['id'];
$status = "menunggu";
$inputProcess = true;

$queryCheck = "SELECT COUNT(*) AS user FROM kesehatan_user WHERE id_user = ?";
$stmtCheck = mysqli_prepare($connect, $queryCheck);
mysqli_stmt_bind_param($stmtCheck, "i", $id);
mysqli_stmt_execute($stmtCheck);
mysqli_stmt_bind_result($stmtCheck, $user);
mysqli_stmt_fetch($stmtCheck);
mysqli_stmt_close($stmtCheck);


if ($goldar !== '-') {
    $status = "diketahui";
}

if ($user > 0) {
    // Persiapkan query UPDATE dengan prepared statement
    $query = "UPDATE `kesehatan_user` SET `goldar` = ?, `usia_kandungan` = ?, `status` = ?, `tanggal_input` = NOW() WHERE `id_user` = ?";
    $stmt = mysqli_prepare($connect, $query);
} else {
    // Persiapkan query INSERT dengan prepared statement
    $query = "INSERT INTO `kesehatan_user`(`goldar`, `id_user`, `usia_kandungan`, `status`, tanggal_input) VALUES (?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($connect, $query);
}

if ($stmt) {
    if ($user >0 ){
         // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "siis", $goldar, $usia_kandungan, $id, $status);
        $inputProcess = false;
    } else {
        // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "siis", $goldar, $id, $usia_kandungan, $status);
    }

    // Jalankan prepared statement
    $result = mysqli_stmt_execute($stmt);

    // Tutup statement
    mysqli_stmt_close($stmt);

    if ($result) {
        // Redirect jika berhasil
        if ($inputProcess) {
            header('Location: ../donor_darah.php?success=input');
            exit();
        } else {
            header('Location: ../donor_darah.php?success=edit');
            exit();
        }
    } else {
        // Tampilkan pesan jika gagal
        header('Location: ../donor_darah.php?gagal=1');
        exit();
    }
} else {
    // Tampilkan pesan jika persiapan statement gagal
    echo "Error: " . mysqli_error($connect);
}

// Tutup koneksi
mysqli_close($connect);
?>