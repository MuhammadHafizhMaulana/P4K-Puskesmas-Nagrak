<?php
session_start();
if(isset($_SESSION['status']) || $_SESSION['status'] == 'login'){
header('Location: home.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Memeriksa apakah nomor HP telah dikirim
    if(isset($_POST['nomorHP'])) {
        // Mendapatkan nomor HP dari formulir
        $nomorHP = $_POST['nomorHP'];
        echo $nomorHP;
        // Melakukan koneksi ke database
        include 'koneksi.php'; // Sesuaikan dengan lokasi file koneksi.php Anda

        // Membuat kueri SQL untuk memeriksa apakah nomor HP sudah ada di database
        $query = "SELECT * FROM pendonor WHERE nomorHP = ?"; // Ganti nama_tabel dengan nama tabel Anda
        $stmt = mysqli_prepare($connect, $query);
        
        // Memasukkan nilai parameter nomor HP ke dalam kueri
        mysqli_stmt_bind_param($stmt, "s", $nomorHP);
        
        // Mengeksekusi kueri
        mysqli_stmt_execute($stmt);
        
        // Mengambil hasil kueri
        mysqli_stmt_store_result($stmt);
        
        // Menghitung jumlah baris yang ditemukan
        $rows = mysqli_stmt_num_rows($stmt);
        
        // Menutup statement
        mysqli_stmt_close($stmt);
        
        // Menutup koneksi ke database
        mysqli_close($connect);
        
        // Memberikan respons berdasarkan hasil pengecekan
        if ($rows > 0) {
            // Nomor HP ditemukan di database
            echo 'found';
        } else {
            // Nomor HP tidak ditemukan di database
            echo 'not_found';
        }
    } else {
        // Jika nomor HP tidak dikirimkan
        echo 'error';
    }
} else {
    // Jika permintaan bukan dari metode POST
    echo 'method_not_allowed';
}
?>