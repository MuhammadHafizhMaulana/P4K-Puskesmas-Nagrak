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

    $queryCheck = "SELECT COUNT(*) AS user FROM kb WHERE id_user = ?";
    $stmtCheck = mysqli_prepare($connect, $queryCheck);
    mysqli_stmt_bind_param($stmtCheck, "i", $id);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_bind_result($stmtCheck, $user);
    mysqli_stmt_fetch($stmtCheck);
    mysqli_stmt_close($stmtCheck);

    // Inisialisasi data dari POST
    $id_user = $_SESSION['id'];
    $tujuan = $_POST['tujuan'];
    $mow = isset($_POST['mow']) ? $_POST['mow'] : '';
    $penyakit1 = isset($_POST['penyakit1']) ? $_POST['penyakit1'] : '';
    $penyakit2 = isset($_POST['penyakit2']) ? $_POST['penyakit2'] : '';
    $iud = isset($_POST['iud']) ? $_POST['iud'] : '';

    if ($tujuan === "menyudahi") {
        if ($mow === "iya") {
            $jenis = "mow";
        } else if ($mow === "tidak") {
            $jenis = "tidak ingin mow";
        }
    } else if ($tujuan === "menjarakan") {
        if ($penyakit1 === "tidak") {
            if ($iud === "iya") {
                $jenis = "iud";
            } else if ($iud === "tidak") {
                $jenis = "tidak mau iud";
            }
        } else if ($penyakit1 === "iya") {
            if ($penyakit2 === "tidak") {
                $jenis = "kondom + kb hormonal";
            } else if ($penyakit2 === "iya") {
                $jenis = "dikonsultasikan terlebih dahulu";
            }
        }
    }

    
    $query = "INSERT INTO `kb`(`tujuan`, `jenis`, `id_user`, `tanggal_input`) VALUES (?, ?, ?, NOW() )";
    
    $stmt = mysqli_prepare($connect, $query);

    if ($stmt) {
        // Bind parameter ke placeholder
        mysqli_stmt_bind_param($stmt, "ssi", $tujuan, $jenis, $id_user);

        // Jalankan prepared statement
        $result = mysqli_stmt_execute($stmt);

        // Tutup statement
        mysqli_stmt_close($stmt);

        // Pesan berhasil
        if ($result) {
            header('Location: ../dashboard_kb.php?success=input');
        } else {
            header('Location: ../dashboard_kb.php?gagal=error_menyimpandata');
        }
    } else {
        // Tampilkan pesan jika persiapan statement gagal
        header('Location: ../dashboard_kb.php?gagal=error_periapanquery');
    }

    // Tutup koneksi
    mysqli_close($connect);
} else {
    // Jika permintaan bukan dari metode POST
    header('Location: ../dashboard_kb.php?gagal=error_methodnotallowed');
}
?>
