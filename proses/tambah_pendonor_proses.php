<?php
    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] !== 'login'){
    header('Location: index.php');
    exit();
}

    // Sambungan ke koneksi
    include 'koneksi.php';

    // Inisialisasi data dari POST
    $nama = $_POST['nama'];
    $nomorHP = $_POST['nomorHP'];
    $goldar = $_POST['goldar'];
    $id = $_SESSION['id'];

    // Ambil ID terbaru dari tabel user
    $ambildata = mysqli_query($connect, "SELECT `id` FROM `user` WHERE `id` ='$id'");
    if ($ambildata) {
        $data_input = mysqli_fetch_assoc($ambildata);
        $id_input = $data_input['id'];
    } else {
        // Menampilkan pesan kesalahan jika query SELECT gagal
        echo "Error: " . mysqli_error($connect);
        exit(); // Hentikan eksekusi skrip
    }

    // Persiapkan query dengan prepared statement
    $query = "INSERT INTO `pendonor`(`nama`, `nomorHP`, `goldar`, `id_user`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);

if ($stmt) {
    // Bind parameter ke placeholder
    mysqli_stmt_bind_param($stmt, "sssi", $nama, $nomorHP, $goldar, $id_input );

    // Jalankan prepared statement
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Redirect jika berhasil
        header('Location: ../tambah_pendonor.php?success=1');
        exit();
    } else {
        // Tampilkan pesan jika gagal
        header('Location: ../tambah_pendonor.php?gagal=1');
        exit();
    }
} else {
    // Tampilkan pesan jika persiapan statement gagal
    echo "Error: " . mysqli_error($connect);
}

// Tutup koneksi
mysqli_close($connect);
?>
