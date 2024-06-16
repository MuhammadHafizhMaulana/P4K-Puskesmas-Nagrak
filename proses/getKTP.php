<?php
session_start();

// Validasi status login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    echo json_encode(['error' => 'Anda belum login.']);
    exit();
}

// Sambungan ke koneksi
include '../koneksi.php';

$id_user = $_SESSION['id'];

// Query untuk mengambil nama file KTP berdasarkan ID pengguna
$query = $conn->prepare("SELECT ktp FROM pembiayaan WHERE id_user = ?");
$query->bind_param('i', $id_user);
$query->execute();
$query->bind_result($ktpFile);
$query->fetch();
$query->close();

// Cek apakah file KTP ditemukan
if ($ktpFile) {
    $filePath = '../data_user/ktp/' . $ktpFile;

    // Cek apakah file benar-benar ada di server
    if (file_exists($filePath)) {
        // Kembalikan URL file KTP
        $fileUrl = 'http://yourdomain.com/data_user/ktp/' . $ktpFile; // Ganti dengan URL sebenarnya
        echo json_encode(['ktpURL' => $fileUrl]);
    } else {
        echo json_encode(['error' => 'File KTP tidak ditemukan.']);
    }
} else {
    echo json_encode(['error' => 'Data KTP tidak ditemukan.']);
}
?>
