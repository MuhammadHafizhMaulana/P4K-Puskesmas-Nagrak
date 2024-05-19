<?php
session_start();
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    header('Location: home.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sambungan ke koneksi
    include 'koneksi.php';

    // Inisialisasi data dari POST
    $nama = ucwords($_POST['nama']);
    $usia = $_POST['usia'];
    $nomorHP = $_POST['nomorHP'];
    $alamat = ucwords($_POST['alamat']);
    $passwordDefault = $_POST['password'];
    $minggu = $_POST['minggu'];
    $hari = $_POST['hari'];
    $usia_kandungan = ($minggu * 7) + $hari;
    $hpht = $_POST['hpht'];
     // Buat objek DateTime dari tanggal HPHT
     $hpht_date = new DateTime($hpht);
    
     // Buat objek DateInterval untuk 9 bulan dan 10 hari
     $interval = new DateInterval('P9M7D');
     
     // Tambahkan interval ke objek DateTime HPHT
     $hpht_date->add($interval);
     
     // Format tanggal hasil penambahan sebagai taksiran persalinan
     $taksiran_persalinan = $hpht_date->format('Y-m-d');

    // Validasi password dengan pola tertentu
    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>])(?=.*[0-9]).{8,}$/';
    if (!preg_match($passwordPattern, $passwordDefault)) {
        header('Location: ../daftar.php?error=password');
        exit(); // Batalkan proses jika password tidak sesuai pola
    }

    // Validasi nomor HP dengan pola tertentu
    $nomorHPPattern = '/^[0-9]+$/';
    if (!preg_match($nomorHPPattern, $nomorHP)) {
        header('Location: ../daftar.php?error=nomorHPFormat');
        exit(); // Batalkan proses jika nomor HP tidak sesuai pola
    }

    // Cek apakah nomor HP sudah ada di database
    $queryCheck = "SELECT COUNT(*) AS total FROM user WHERE nomorHP = ?";
    $stmtCheck = mysqli_prepare($connect, $queryCheck);
    mysqli_stmt_bind_param($stmtCheck, "s", $nomorHP);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_bind_result($stmtCheck, $total);
    mysqli_stmt_fetch($stmtCheck);
    mysqli_stmt_close($stmtCheck);

    if ($total > 0) {
        // Jika nomor HP sudah ada, batalkan proses
        header('Location: ../daftar.php?gagal=nomorHP');
        exit();
    }

    $password = password_hash($passwordDefault, PASSWORD_DEFAULT);

    // Persiapkan query dengan prepared statement
    $query = "INSERT INTO `user`(`nama`, `usia`, `nomorHP`, `alamat`, `password`) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);

    if ($stmt) {
        // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "sisss", $nama, $usia, $nomorHP, $alamat, $password);

        // Jalankan prepared statement
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Ambil ID pengguna yang baru saja ditambahkan
            $userID = mysqli_insert_id($connect);

            // Persiapkan query untuk menambahkan data ke tabel kesehatan_user
            $queryKandungan = "INSERT INTO `kesehatan_user`(`id_user`, `usia_kandungan`, `hpht`, `taksiran_persalinan`) VALUES (?, ?, ?, ?)";
            $stmtKandungan = mysqli_prepare($connect, $queryKandungan);

            if ($stmtKandungan) {
                // Bind parameter ke placeholder
                mysqli_stmt_bind_param($stmtKandungan, "iiss", $userID, $usia_kandungan, $hpht, $taksiran_persalinan);

                // Jalankan prepared statement
                $resultKandungan = mysqli_stmt_execute($stmtKandungan);

                if ($resultKandungan) {
                    // Redirect jika berhasil
                    header('Location: ../index.php?success=1');
                    exit();
                } else {
                    // Tampilkan pesan jika gagal
                    echo "Tambah data usia kandungan gagal: " . mysqli_stmt_error($stmtKandungan);
                }

                // Tutup statement
                mysqli_stmt_close($stmtKandungan);
            } else {
                // Tampilkan pesan jika persiapan statement gagal
                echo "Error persiapan query kandungan: " . mysqli_error($connect);
            }
        } else {
            // Tampilkan pesan jika gagal
            echo "Tambah data user gagal: " . mysqli_stmt_error($stmt);
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        // Tampilkan pesan jika persiapan statement gagal
        echo "Error persiapan query user: " . mysqli_error($connect);
    }

    // Tutup koneksi
    mysqli_close($connect);
} else {
    // Jika permintaan bukan dari metode POST
    echo 'method_not_allowed';
}
?>
