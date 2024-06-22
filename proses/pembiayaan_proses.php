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
    $jenis_pembayaran = ($_POST['jenis_pembayaran']);
    $tabungan_hamil = isset($_POST['tabungan_hamil']) ? $_POST['tabungan_hamil'] : null;
    $kepemilikan_jaminan = isset($_POST['kepemilikan_jaminan']) ? $_POST['kepemilikan_jaminan'] : null;
    $status_jaminan = isset($_POST['status_jaminan']) ? $_POST['status_jaminan'] : null;
    $tipe_jkn = isset($_POST['tipe_jkn']) ? $_POST['tipe_jkn'] : null;
    $jkn_aktif = isset($_POST['jkn_aktif']) ? $_POST['jkn_aktif'] : null;
    $jkn_tidakAktif = isset($_POST['jkn_tidakAktif']) ? $_POST['jkn_tidakAktif'] : null;
    $jenisPembayaranInput = null;
    $statusInput = null;
    $jenisTabungan = null;


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
 

    if ($jenis_pembayaran === "tabungan") {
        if ($tabungan_hamil === "dada_linmas") {
            $jenisPembayaranInput = "tabungan";
            $statusInput = "aktif";
            $jenisTabungan = "dadalinmas";
        } else if ($tabungan_hamil === "saldo_pribadi") {
            $jenisPembayaranInput = "tabungan";
            $statusInput = "aktif";
            $jenisTabungan = "saldo_pribadi";
        }
    } else if ($jenis_pembayaran === "jkn") {
        if ($kepemilikan_jaminan === "punya") {
            if ($status_jaminan === "aktif") {
                if ($jkn_aktif === "mandiri") {
                    $jenisPembayaranInput = "jkn";
                    $statusInput = "aktif";
                    $jenisTabungan = "mandiri";
                } else if ($jkn_aktif === "jkn_pbi") {
                    $jenisPembayaranInput = "jkn";
                    $statusInput = "aktif";
                    $jenisTabungan = "pbi";
                }
            } else if ($status_jaminan === "tidak_aktif") {
                $jenisPembayaranInput = "jkn";
                $statusInput = "non aktif";
                $jenisTabungan = "-";
            }
        } else if ($kepemilikan_jaminan === "tidak_punya") {
            if ($tipe_jkn === "pbi") {
                $jenisPembayaranInput = "jkn";
                $statusInput = "tidak punya";
                $jenisTabungan = "pbi";
            } else if ($tipe_jkn === "mandiri") {
                $jenisPembayaranInput = "jkn";
                $statusInput = "tidak punya";
                $jenisTabungan = "mandiri";
            }
        }
    } else {
        // Jika jenis pembayaran tidak ditemukan
        $jenisPembayaranInput = null; // Atau nilai default yang sesuai
    }

    $jenis_pembayaran = isset($_POST['jenis_pembayaran']) ? $_POST['jenis_pembayaran'] : null;

    // Persiapkan query dengan prepared statement
    if ($user > 0) {
        $ktp_name = $ktp_name ?? $pembiayaanData['ktp'];
        $kk_name = $kk_name ?? $pembiayaanData['kk'];
        $pas_foto_name = $pas_foto_name ?? $pembiayaanData['pas_foto'];
        $rujukan_name = $rujukan_name ?? $pembiayaanData['rujukan'];
        $rekomendasi_name = $rekomendasi_name ?? $pembiayaanData['rekomendasi'];

        $query = "UPDATE `pembiayaan` SET `jenis_pembayaran` = ?, `status` = ?, `jenis_tabungan` = ?, `ktp` = ?, `kk` = ?, `rujukan` = ?, `rekomendasi` = ?, `pas_foto` = ? WHERE `id_user` = ?";
    } else {
         // Mengubah nilai null menjadi string kosong atau simbol placeholder
        $rujukan_name = $rujukan_name ?? '-';
        $rekomendasi_name = $rekomendasi_name ?? '-';
    
        $query = "INSERT INTO `pembiayaan`(`jenis_pembayaran`, `status`, `jenis_tabungan`, `ktp`, `kk`, `rujukan`, `rekomendasi`, `pas_foto`, `id_user`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
    $stmt = mysqli_prepare($connect, $query);
    

    if ($stmt) {
        // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "ssssssssi", $jenisPembayaranInput, $statusInput, $jenisTabungan, $ktp_name, $kk_name, $rujukan_name, $rekomendasi_name, $pas_foto_name, $id_user);

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
