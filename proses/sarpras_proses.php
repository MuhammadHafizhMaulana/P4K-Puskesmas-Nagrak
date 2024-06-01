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
    $nama_supir = ucwords($_POST['nama_supir']);
    $no_supir = $_POST['no_supir'];
    $nama_pendamping = ucwords($_POST['nama_pendamping']);
    $no_pendamping = $_POST['no_pendamping'];
    $tujuan = $_POST['tujuan'];
    $id_user = $_SESSION['id'];

    // Validasi nomor HP dengan pola tertentu
    $nomorHPPattern = '/^[0-9]+$/';
    if (!preg_match($nomorHPPattern, $no_supir) || !preg_match($nomorHPPattern, $no_pendamping)) {
        header('Location: ../dashboard_sarpras.php?error=nomorHPFormat');
        exit(); // Batalkan proses jika nomor HP tidak sesuai pola
    }

    // Persiapkan query dengan prepared statement
    $query = "INSERT INTO `sarpras`(`transportasi`, `nama_supir`, `no_supir`, `nama_pendamping`, `no_pendamping`, `tujuan`, `id_user`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);

    if ($stmt) {
        // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "ssssssi", $transportasi, $nama_supir, $no_supir, $nama_pendamping, $no_pendamping, $tujuan, $id_user);

        // Jalankan prepared statement
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Redirect berdasarkan hasil eksekusi
            header('Location: ../dashboard_sarpras.php?success=addSarpras');
            exit();
        } else {
            header('Location: ../dashboard_donor_darah.php?gagal=1');
            exit();
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
