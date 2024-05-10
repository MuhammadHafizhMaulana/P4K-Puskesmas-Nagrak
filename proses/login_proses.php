<?php 
session_start();
include 'koneksi.php';

// Ambil nomor HP dan password dari POST
$nomorHP = $_POST['nomorHP'];
$password = $_POST['password'];

// Persiapkan query dengan prepared statement
$query = "SELECT * FROM `user` WHERE `nomorHP` = ?";
$stmt = mysqli_prepare($connect, $query);

if ($stmt) {
    // Bind parameter ke placeholder
    mysqli_stmt_bind_param($stmt, "s", $nomorHP);

    // Eksekusi prepared statement
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah ada baris hasil
    if(mysqli_num_rows($result) > 0){
        // Ambil data pengguna dari hasil query
        $data = mysqli_fetch_assoc($result);
        
        // Periksa apakah nomor HP sesuai dengan data dari hasil query
        if ($_POST['nomorHP'] === $data['nomorHP'] && password_verify($_POST['password'], $data['password'])) {
            print('tes');
            $_SESSION['id'] = $data['id'];;
            $_SESSION['status'] = 'login';
            // Jika sesuai, redirect ke halaman home
            header('Location: ../home.php');
        } else {
            // Jika tidak sesuai, redirect ke halaman login dengan pesan gagal
            header('Location: ../index.php?pesan=gagal');
        }
    } else {
        // Jika tidak ada baris hasil, redirect ke halaman login dengan pesan gagal
        header('Location: ../index.php?pesan=gagal');
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    // Jika persiapan statement gagal, tangani kesalahan
    echo "Error: " . mysqli_error($connect);
}

// Tutup koneksi
mysqli_close($connect);
?>
