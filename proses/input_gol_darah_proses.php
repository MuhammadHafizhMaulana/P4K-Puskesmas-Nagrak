<?php
    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] !== 'login'){
    header('Location: index.php');
    exit();
}

    // Sambungan ke koneksi
    include 'koneksi.php';

    // Inisialisasi data dari POST
    $goldar = $_POST['goldar'];
    $usia_kandungan = $_POST['usia_kandungan'];
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
    $query = "INSERT INTO `kesehatan_user`(`goldar`, `id_user`, `usia_kandungan`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);

if ($stmt) {
    // Bind parameter ke placeholder
    mysqli_stmt_bind_param($stmt, "sii", $goldar, $id_input, $usia_kandungan );

    // Jalankan prepared statement
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Redirect jika berhasil
        header('Location: ../donor_darah.php?success=1');
        exit();
    } else {
        // Tampilkan pesan jika gagal
        header('Location: ../donor_darah.php?gagal=1');
        exit();
    }
} else {
    // Tampilkan pesan jika persiapan statement gagal
    echo "Error: " . mysqli_error($connect);
}

// Tutup koneksi
mysqli_close($connect);
?>
