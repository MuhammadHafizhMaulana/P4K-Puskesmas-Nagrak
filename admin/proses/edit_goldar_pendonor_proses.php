<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: landing.php');
    exit();
}

// Sambungan ke koneksi
include '../../proses/koneksi.php';

// Inisialisasi data dari POST
if(isset($_POST['goldar'], $_POST['id'], $_POST['id_user'])) {
    $goldar = $_POST['goldar'];
    $id = $_POST['id'];
    $id_user = $_POST['id_user'];
} else {
    // Jika salah satu data POST tidak ada, maka berikan pesan kesalahan dan redireksi
    header("Location: ../edit_goldar_pendonor.php?id=$id&id_user=$id_user&error=missing_data");

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
        header("Location: ../edit_goldar_pendonor.php?id=$id&id_user=$id_user&success=update_successful");
        exit();
    } else {
        // Redirect jika gagal atau tidak ada baris yang terpengaruh
        header("Location: ../edit_goldar_pendonor.php?id=$id&id_user=$id_user&error=update_failed");
        exit();
    }
} else {
    // Tampilkan pesan jika persiapan statement gagal
    header("Location: ../edit_goldar_pendonor.php?id=$id&id_user=$id_user&error=prepare_statement_failed");
    exit();
}


?>
