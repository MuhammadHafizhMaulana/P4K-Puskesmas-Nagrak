<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}
// Fungsi untuk mengambil konten gambar sebagai respons
function getPasFotoImage() {
    // Ambil ID pengguna dari session
    $userID = $_SESSION['id']; // Anda harus menyesuaikan ini dengan cara Anda mengambil ID pengguna dari session

    // Cek apakah ID pengguna sesuai dengan ID dalam nama file pas_foto
    if ($userID) {
        include './koneksi.php';
        $queryCheck = "SELECT pas_foto FROM pembiayaan WHERE id_user = $userID";
        $stmtCheck = mysqli_prepare($connect, $queryCheck);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $pas_foto);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);
        // Path gambar pas_foto
        $pathToPasFoto = "../data_user/pas_foto/$pas_foto"; // Ganti ekstensi file sesuai kebutuhan

        // Periksa apakah gambar pas_foto pengguna ada
        if (file_exists($pathToPasFoto)) {
            // Jika ada, kirimkan gambar sebagai respons dari server
            header("Content-Type: image/jpeg"); // Ganti jenis konten sesuai ekstensi file
            readfile($pathToPasFoto);
            exit;
        }
    }
}

// Panggil fungsi untuk mengambil gambar pas_foto
getPasFotoImage();
?>
