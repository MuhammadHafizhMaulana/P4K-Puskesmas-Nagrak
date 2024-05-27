<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: landing.php');
    exit();
}

// Sambungan ke koneksi
include '../../proses/koneksi.php';

// Inisialisasi data dari POST
if (isset($_POST['goldar'], $_POST['id'])) {
    $goldar = $_POST['goldar'];
    $id = $_POST['id'];
} else {
    // Jika salah satu data POST tidak ada, maka berikan pesan kesalahan dan redireksi
    header("Location: ../detailPendonor.php?id=$id&error=missing_data");

    exit();
}

$status = ($goldar !== '-') ? 'diketahui' : 'menunggu'; // Tentukan status berdasarkan golongan darah

// Query untuk melakukan update golongan darah
$query = "UPDATE pendonor SET goldar = ?, status = ?, tanggal_input = NOW() WHERE id = ?";

$stmt = mysqli_prepare($connect, $query);
if ($stmt) {
    // Bind parameter untuk UPDATE
    mysqli_stmt_bind_param($stmt, "ssi", $goldar, $status, $id);

    // Jalankan prepared statement
    $result = mysqli_stmt_execute($stmt);

    // Periksa apakah ada baris yang terpengaruh oleh update
    $rowsAffected = mysqli_stmt_affected_rows($stmt);

    // Tutup statement
    mysqli_stmt_close($stmt);

    if ($result && $rowsAffected > 0) {
        // Redirect jika berhasil
        header("Location: ../detailPendonor.php?id=$id&success=update_successful");
        exit();
    } else {
        // Redirect jika gagal atau tidak ada baris yang terpengaruh
        header("Location: ../detailPendonor.php?id=$id&error=update_failed");
        exit();
    }
} else {
    // Tampilkan pesan jika persiapan statement gagal
    header("Location: ../detailPendonor.php?id=$id&error=prepare_statement_failed");
    exit();
}
