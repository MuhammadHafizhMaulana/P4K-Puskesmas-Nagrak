<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}
// Fungsi untuk mengambil konten gambar sebagai respons
function getRekomendasiImage() {
    // Ambil ID pengguna dari session
    $userID = $_SESSION['id']; // Anda harus menyesuaikan ini dengan cara Anda mengambil ID pengguna dari session

    // Cek apakah ID pengguna sesuai dengan ID dalam nama file rekomendasi
    if ($userID) {
        include './koneksi.php';
        $queryCheck = "SELECT rekomendasi FROM pembiayaan WHERE id_user = $userID";
        $stmtCheck = mysqli_prepare($connect, $queryCheck);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $rekomendasi);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);
        // Path gambar rekomendasi
        $pathToRekomendasi = "../data_user/rekomendasi/$rekomendasi"; // Ganti ekstensi file sesuai kebutuhan

        // Periksa apakah gambar rekomendasi pengguna ada
        if (file_exists($pathToRekomendasi)) {
            // Jika ada, kirimkan gambar sebagai respons dari server
            header("Content-Type: image/jpeg"); // Ganti jenis konten sesuai ekstensi file
            readfile($pathToRekomendasi);
            exit;
        }
    }
}

// Panggil fungsi untuk mengambil gambar rekomendasi
getRekomendasiImage();
?>
