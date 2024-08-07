<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit(); // tambahkan exit setelah redirect
}

// Fungsi untuk mengambil konten gambar sebagai respons
function getKKImage() {

    // Cek apakah ID pengguna sesuai dengan ID dalam nama file kk
    if (isset($_GET['id'])) {
        include '../../proses/koneksi.php';
        $userID = $_GET['id'];
        $queryCheck = "SELECT kk FROM pembiayaan WHERE id_user = $userID";
        $stmtCheck = mysqli_prepare($connect, $queryCheck);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $kk);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);
        // Path gambar kk
        $pathToKK = "../../data_user/kk/$kk"; // Ganti ekstensi file sesuai kebutuhan

        // Periksa apakah gambar kk pengguna ada
        if (file_exists($pathToKK)) {
            // Jika ada, kirimkan gambar sebagai respons dari server
            header("Content-Type: image/jpeg"); // Ganti jenis konten sesuai ekstensi file
            readfile($pathToKK);
            exit;
        }
    }
}

// Panggil fungsi untuk mengambil gambar kk
getKKImage();
?>
