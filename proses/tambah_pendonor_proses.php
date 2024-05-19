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

    // Validasi input dan inisialisasi data dari POST
    $nama = ucwords($_POST['nama']);
    $nomorHP = $_POST['nomorHP'];
    $goldar = $_POST['goldar'];
    $id = $_SESSION['id'];
    $status = "menunggu";
    $inputProcess = true;



    // Ambil ID terbaru dari tabel user
    $query = "SELECT `id` FROM `user` WHERE `id` = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($goldar !== '-') {
        $status = "diketahui";
    }

    // Validasi hasil query
    if ($result && mysqli_num_rows($result) > 0) {
        $data_input = mysqli_fetch_assoc($result);
        $id_input = $data_input['id'];

        // Persiapkan query dengan prepared statement
        $query = "INSERT INTO `pendonor`(`nama`, `nomorHP`, `goldar`, `status`, `id_user`, `tanggal_input`) VALUES (?, ?, ?, ?, ?, NOW() )";
        $stmt = mysqli_prepare($connect, $query);

        if ($stmt) {
            // Bind parameter ke placeholder
            mysqli_stmt_bind_param($stmt, "ssssi", $nama, $nomorHP, $goldar, $status, $id_input);

            // Jalankan prepared statement
            $result = mysqli_stmt_execute($stmt);

            // Redirect berdasarkan hasil eksekusi
            if ($result) {
                header('Location: ../dashboard_donor_darah.php?success=addPendonor');
                exit();
            } else {
                header('Location: ../dashborad_donor_darah.php?gagal=1');
                exit();
            }
        } else {
            echo "Error: " . mysqli_error($connect);
        }
    } else {
        // Tampilkan pesan jika ID pengguna tidak valid
    }

    // Tutup koneksi
    mysqli_close($connect);
} else {
    // Jika permintaan bukan dari metode POST
    echo 'method_not_allowed';
}
