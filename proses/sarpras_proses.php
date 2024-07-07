<?php
session_start();

// Validasi status login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sambungan ke koneksi
    include 'koneksi.php';

    // Inisialisasi data dari POST
    $transportasi = $_POST['transportasi'];
    $nama_supir = ucwords(strtolower($_POST['nama_supir']));
    $no_supir = $_POST['no_supir'];
    $nama_pendamping = ucwords(strtolower($_POST['nama_pendamping']));
    $no_pendamping = $_POST['no_pendamping'];
    $tujuan = $_POST['tujuan'];
    $penolong = $_POST['penolong'];
    $nama_penolong = ucwords(strtolower($_POST['nama_penolong']));
    $usg = $_POST['usg'];
    $tanggal_usg = isset($_POST['tanggal_usg']) ? $_POST['tanggal_usg'] : '';
    $umur_usg = isset($_POST['umur_usg']) ? $_POST['umur_usg'] : '';
    $status_usg = isset($_POST['status_usg']) ? $_POST['status_usg'] : '';
    $id_user = $_SESSION['id'];

    // Memproses kondisi berdasarkan nilai $usg
    if ($usg === 'pernah') {
        $usg_input = "$usg + $tanggal_usg + $umur_usg + $status_usg";
    } else {
        $usg_input = 'belum';
    }

    // Memproses kondisi berdasarkan nilai $penolong
    $penolong_input = $penolong . " + " . $nama_penolong;

    // Validasi nomor HP dengan pola tertentu
    $nomorHPPattern = '/^[0-9]+$/';
    if (!preg_match($nomorHPPattern, $no_supir) || !preg_match($nomorHPPattern, $no_pendamping)) {
        header('Location: ../dashboard_sarpras.php?error=nomorHPFormat');
        exit(); // Batalkan proses jika nomor HP tidak sesuai pola
    }

    // Cek apakah data sudah ada sebelumnya untuk pengguna tersebut
    $query_check = "SELECT COUNT(*) as count FROM `sarpras` WHERE `id_user` = ?";
    $stmt_check = mysqli_prepare($connect, $query_check);
    mysqli_stmt_bind_param($stmt_check, "i", $id_user);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $count);
    mysqli_stmt_fetch($stmt_check);
    mysqli_stmt_close($stmt_check);

    if ($count > 0) {
        // Persiapkan query UPDATE dengan prepared statement
        $query = "UPDATE `sarpras` SET `transportasi` = ?, `nama_supir` = ?, `no_supir` = ?, `nama_pendamping` = ?, `no_pendamping` = ?, `tujuan` = ?, `penolong` = ?, `usg` = ? WHERE `id_user` = ?";
    } else {
        // Persiapkan query INSERT dengan prepared statement
        $query = "INSERT INTO `sarpras`(`transportasi`, `nama_supir`, `no_supir`, `nama_pendamping`, `no_pendamping`, `tujuan`, `penolong`, `usg`, `id_user`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
    $stmt = mysqli_prepare($connect, $query);

    if ($stmt) {
        // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "ssssssssi", $transportasi, $nama_supir, $no_supir, $nama_pendamping, $no_pendamping, $tujuan, $penolong_input, $usg_input, $id_user);

        // Jalankan prepared statement
        $result = mysqli_stmt_execute($stmt);

        // Pesan berhasil
        if ($result) {
            if ($count > 0) {
                header('Location: ../dashboard_sarpras.php?success=edit');
            } else {
                header('Location: ../dashboard_sarpras.php?success=input');
            }
        } else {
            header('Location: ../dashboard_sarpras.php?pesan=eror_menyimpandata');
        }
    } else {
        echo "Error: " . mysqli_error($connect);
    }

    // Tutup koneksi
    mysqli_close($connect);
} else {
    // Jika permintaan bukan dari metode POST
    echo 'method_not_allowed';
}
?>
