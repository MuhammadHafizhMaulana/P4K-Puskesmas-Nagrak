<?php
session_start();

// Validasi status login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  header('Location: ../index.php');
  exit();
}

// Sambungan ke koneksi
include '../koneksi.php';

$id = $_SESSION['id'];

// Query untuk mengambil nama file KTP berdasarkan ID pengguna
$query = $conn->prepare("SELECT ktp FROM pembiayaan WHERE id = ?");
$query->bind_param('i', $id);
$query->execute();
$query->bind_result($ktpFile);
$query->fetch();
$query->close();

// Debugging: periksa hasil query
if ($ktpFile) {
  error_log("KTP file found: $ktpFile for user ID: $id");
} else {
  error_log("No KTP file found for user ID: $id");
}

// Cek apakah file KTP ditemukan
if ($ktpFile) {
  $filePath = __DIR__ . '/../data_user/ktp/' . $ktpFile;

  // Cek apakah file benar-benar ada di server
  if (file_exists($filePath)) {
    // Gunakan finfo_open untuk deteksi tipe konten yang lebih akurat
    $fileInfo = finfo_open(FILEINFO_MIME);
    $mimeType = finfo_file($fileInfo, $filePath);
    finfo_close($fileInfo);

    // Set header berdasarkan tipe konten yang didapat
    header('Content-Type: ' . $mimeType);
    readfile($filePath);
    exit();
  } else {
    // File tidak ditemukan
    header("HTTP/1.0 404 Not Found");
    echo "File KTP tidak ditemukan: " . $filePath;
    exit();
  }
} else {
  // Nama file KTP tidak ditemukan dalam database
  header("HTTP/1.0 404 Not Found");
  echo "Data KTP tidak ditemukan untuk ID: " . $id;
  exit();
}
?>
