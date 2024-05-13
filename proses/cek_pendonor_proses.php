<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Memeriksa apakah nomor HP telah dikirim
    if (isset($_POST['nomorHP'])) {
        // Mendapatkan nomor HP dari formulir
        $nomorHP = $_POST['nomorHP'];
        $nama = $_POST['nama'];
        // Melakukan koneksi ke database
        include 'koneksi.php'; // Sesuaikan dengan lokasi file koneksi.php Anda

        $queryCheck = "SELECT COUNT(*) AS total FROM pendonor WHERE nomorHP = ?";
        $stmtCheck = mysqli_prepare($connect, $queryCheck);
        mysqli_stmt_bind_param($stmtCheck, "s", $nomorHP);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $total);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);

        if ($total > 0) {
            // Jika nomor HP sudah ada, batalkan proses
            header('Location: ../tambah_pendonor.php?gagal=nomorHP');
            exit();
        } else {
            $_SESSION['new_donor_name'] = $nama;
            $_SESSION['new_donor_hp'] = $nomorHP;

            // Redirect with success message (optional)
            header('Location: ../tambah_pendonor.php?success=new_donor');
            exit();
        }
    } else {
        // Jika nomor HP tidak dikirimkan
        echo 'error';
    }
} else {
    // Jika permintaan bukan dari metode POST
    echo 'method_not_allowed';
}
