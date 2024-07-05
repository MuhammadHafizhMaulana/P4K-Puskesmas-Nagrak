<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit(); // tambahkan exit setelah redirect
}

// Fungsi untuk mengambil konten gambar sebagai respons
function getKTPImage() {

    // Cek apakah ID pengguna sesuai dengan ID dalam nama file ktp
    if (isset($_GET['id'])) {
        include '../../proses/koneksi.php';
        $userID = $_GET['id'];
        $queryCheck = "SELECT ktp FROM pembiayaan WHERE id_user = $userID";
        $stmtCheck = mysqli_prepare($connect, $queryCheck);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $ktp);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);
        // Path gambar ktp
        $pathToKTP = "../../data_user/ktp/$ktp"; // Ganti ekstensi file sesuai kebutuhan

        // Periksa apakah gambar ktp pengguna ada
        if (file_exists($pathToKTP)) {
            // Jika ada, kirimkan gambar sebagai respons dari server
            header("Content-Type: image/jpeg"); // Ganti jenis konten sesuai ekstensi file
            readfile($pathToKTP);
            exit;
        }
    }
}

// Panggil fungsi untuk mengambil gambar ktp
getKTPImage();
?>
