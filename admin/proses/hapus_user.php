<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit();
}

// Sambungan ke database
include '../../proses/koneksi.php';

// Periksa apakah parameter id ada di URL dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Mulai transaksi
    mysqli_begin_transaction($connect, MYSQLI_TRANS_START_READ_WRITE);

    try {
        // Query untuk mendapatkan nama file yang terkait dengan pengguna
        $queryFiles = "SELECT ktp, kk, rujukan, pas_foto, rekomendasi FROM pembiayaan WHERE id_user = ?";
        $stmtFiles = mysqli_prepare($connect, $queryFiles);
        mysqli_stmt_bind_param($stmtFiles, "i", $id);
        mysqli_stmt_execute($stmtFiles);
        mysqli_stmt_bind_result($stmtFiles, $ktp, $kk, $rujukan, $pas_foto, $rekomendasi);
        mysqli_stmt_fetch($stmtFiles);
        mysqli_stmt_close($stmtFiles);

        // Hapus file dari sistem file
        $base_dir = "../data_user/";
        $file_paths = [
            'ktp' => $ktp ? $base_dir . "ktp/" . $ktp : null,
            'kk' => $kk ? $base_dir . "kk/" . $kk : null,
            'rujukan' => $rujukan ? $base_dir . "rujukan/" . $rujukan : null,
            'pas_foto' => $pas_foto ? $base_dir . "pas_foto/" . $pas_foto : null,
            'rekomendasi' => $rekomendasi ? $base_dir . "rekomendasi/" . $rekomendasi : null
        ];
        
        foreach ($file_paths as $file) {
            if ($file && file_exists($file)) {
                unlink($file);
            }
        }

        // Hapus data dari tabel terkait
        $tables = ['pembiayaan', 'kesehatan_user', 'pendonor'];
        foreach ($tables as $table) {
            $query = "DELETE FROM $table WHERE id_user = ?";
            $stmt = mysqli_prepare($connect, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Hapus pengguna dari tabel user
        $queryUser = "DELETE FROM user WHERE id = ?";
        $stmtUser = mysqli_prepare($connect, $queryUser);
        mysqli_stmt_bind_param($stmtUser, "i", $id);
        mysqli_stmt_execute($stmtUser);
        mysqli_stmt_close($stmtUser);

        // Commit transaksi
        mysqli_commit($connect);

        // Redirect kembali ke halaman data pengguna dengan pesan sukses
        header('Location: ../data_user.php?status=deleted');
        exit();
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($connect);
        echo "Gagal menghapus data pengguna: " . $e->getMessage();
    }
} else {
    // Jika ID tidak valid, redirect kembali ke halaman data pengguna
    header('Location: ../data_user.php?status=invalid_id');
    exit();
}

// Tutup koneksi
mysqli_close($connect);
?>
