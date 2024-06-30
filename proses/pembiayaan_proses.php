<?php
session_start();

// Validasi status login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sambungan ke koneksi
    include 'koneksi.php';
    $id = $_SESSION['id'];

    $queryCheck = "SELECT COUNT(*) AS user FROM pembiayaan WHERE id_user = ?";
    $stmtCheck = mysqli_prepare($connect, $queryCheck);
    mysqli_stmt_bind_param($stmtCheck, "i", $id);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_bind_result($stmtCheck, $user);
    mysqli_stmt_fetch($stmtCheck);
    mysqli_stmt_close($stmtCheck);

    if ($user > 0) {
        $pembiayaanData = "SELECT * FROM `pembiayaan` WHERE `id_user` = ?";
        $stmt = mysqli_prepare($connect, $pembiayaanData);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Fetch the data as an associative array
        $pembiayaanData = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }

    // Inisialisasi data dari POST
    $id_user = $_SESSION['id'];
    $jenis_pembayaran = isset($_POST['jenis_pembayaran']) ? $_POST['jenis_pembayaran'] : '-';
    $saldoTabungan = isset($_POST['saldoTabungan']) ? $_POST['saldoTabungan'] : null;
    $nomorBPJS = isset($_POST['nomorBPJS']) ? $_POST['nomorBPJS'] : '-';

     // Inisialisasi data dari FILES
     $ktp = isset($_FILES['ktp']) ? $_FILES['ktp'] : null;
     $kk = isset($_FILES['kk']) ? $_FILES['kk'] : null;
     $rujukan = isset($_FILES['rujukan']) ? $_FILES['rujukan'] : null;
     $pas_foto = isset($_FILES['pas_foto']) ? $_FILES['pas_foto'] : null;
     $rekomendasi = isset($_FILES['rekomendasi']) ? $_FILES['rekomendasi'] : null;
 
     // Folder tujuan
     $target_dir = "../data_user/";
 
     // Fungsi untuk mengelola unggahan file dengan mengganti nama dan menempatkan di subfolder
     function unggahFile($file, $awalan, $id_user, $target_dir, $subfolder) {
         if ($file['error'] != UPLOAD_ERR_OK) {
             return null; // mengembalikan null jika terjadi kesalahan saat unggah
         }
         $ekstensi = pathinfo($file["name"], PATHINFO_EXTENSION);
         $nama_baru = $awalan . $id_user . '.' . $ekstensi;
         $target_file = $target_dir . $subfolder . '/' . $nama_baru;
 
         // Pastikan subfolder ada, jika tidak buat subfolder
         if (!is_dir($target_dir . $subfolder)) {
            header('Location: ../dashboard_pembiayaan.php?pesan=foldertidakada');
         }
 
         if (move_uploaded_file($file["tmp_name"], $target_file)) {
             return $nama_baru;
         } else {
             return null;
         }
     }
 
     $ktp_name = unggahFile($ktp, "ktp_", $id_user, $target_dir, 'ktp');
     $kk_name = unggahFile($kk, "kk_", $id_user, $target_dir, 'kk');
     $rujukan_name = unggahFile($rujukan, "rujukan_", $id_user, $target_dir, 'rujukan');
     $pas_foto_name = unggahFile($pas_foto, "pas_foto_", $id_user, $target_dir, 'pas_foto');
     $rekomendasi_name = unggahFile($rekomendasi, "rekomendasi_", $id_user, $target_dir, 'rekomendasi');
 

    // Persiapkan query dengan prepared statement
    if ($user > 0) {
        $ktp_name = $ktp_name ?? $pembiayaanData['ktp'];
        $kk_name = $kk_name ?? $pembiayaanData['kk'];
        $pas_foto_name = $pas_foto_name ?? $pembiayaanData['pas_foto'];
        $rujukan_name = $rujukan_name ?? $pembiayaanData['rujukan'];
        $rekomendasi_name = $rekomendasi_name ?? $pembiayaanData['rekomendasi'];

        $query = "UPDATE `pembiayaan` SET `jenis_pembayaran` = ?, `ktp` = ?, `kk` = ?, `rujukan` = ?, `rekomendasi` = ?, `pas_foto` = ?, `saldo_tabungan` = ?, `nomor_bpjs` = ? WHERE `id_user` = ?";
    } else {
         // Mengubah nilai null menjadi string kosong atau simbol placeholder
        $rujukan_name = $rujukan_name ?? '-';
        $rekomendasi_name = $rekomendasi_name ?? '-';
    
        $query = "INSERT INTO `pembiayaan`(`jenis_pembayaran`, `ktp`, `kk`, `rujukan`, `rekomendasi`, `pas_foto`, `saldo_tabungan`, `nomor_bpjs`, `id_user`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
    $stmt = mysqli_prepare($connect, $query);
    

    if ($stmt) {
        // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "sssssssii", $jenis_pembayaran, $ktp_name, $kk_name, $rujukan_name, $rekomendasi_name, $pas_foto_name, $saldoTabungan, $nomorBPJS, $id_user);

        // Jalankan prepared statement
        $result = mysqli_stmt_execute($stmt);

        // Tutup statement
        mysqli_stmt_close($stmt);

        // Pesan berhasil
        if ($result) {
            if ($user > 0) {
                header('Location: ../dashboard_pembiayaan.php?success=edit');
            } else {
                header('Location: ../dashboard_pembiayaan.php?success=input');
            }
        } else {
            header('Location: ../dashboard_pembiayaan.php?pesan=eror_menyimpandata');
        }
    } else {
        // Tampilkan pesan jika persiapan statement gagal
        header('Location: ../dashboard_pembiayaan.php?pesan=eror_periapanquery');
    }

    // Tutup koneksi
    mysqli_close($connect);
} else {
    // Jika permintaan bukan dari metode POST
    header('Location: ../dashboard_pembiayaan.php?pesan=eror_methodnotallowed');
}
?>
