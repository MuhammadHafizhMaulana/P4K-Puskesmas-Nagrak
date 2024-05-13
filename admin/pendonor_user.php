<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit(); // tambahkan exit setelah redirect
}

// Sambungan ke database
include '../proses/koneksi.php';

// Periksa apakah parameter id ada di URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    // Query untuk mengambil data pengguna berdasarkan id 
    $query = "SELECT * FROM pendonor WHERE id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah data ditemukan
    if(mysqli_num_rows($result) > 0) {
        // Ambil data pengguna
        while($data = mysqli_fetch_assoc($result)) {
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit User</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <link rel="stylesheet" href="../css/dashboard.css">
        </head>
        <body>
        <table class="table">
            <tbody>
                <th><h1>Data Pendonor</h1></th>
                <?php
                
                ?>
                <tr>
                    <!-- Nama Pendonor -->
                    <th>Nama Pendonor</th>
                    <td><?= isset($data['nama']) ? $data['nama'] : "Data belum diinput" ?></td>
                </tr>
                <tr>
                    <!-- Nomor HP Pendonor -->
                    <th>Nomor HP Pendonor</th>
                    <td><?= isset($data['nomorHP']) ? $data['nomorHP'] : "Data belum diinput" ?></td>
                </tr>
                <tr>
                    <!-- Status -->
                    <th>Status</th>
                    <td><?= isset($data['status']) ? $data['status'] : "Data belum diinput" ?></td>
                </tr>
                <tr>
                    <!-- Golongan Darah Pendonor -->
                    <th>Golongan Darah Pendonor</th>
                    <td><?= isset($data['goldar']) ? $data['goldar'] : "Data belum diinput" ?>
                        <a href="edit_goldar_pendonor.php?id=<?= $data['id']?>&id_user=<?=$_GET['id']?>">Edit</a>
                    </td>
                </tr>
                <tr>
                    <th> <a href="kesehatan_user.php?id=<?=$_GET['id']?>" class="button button-dark me-2">kembali</a></th>
                </tr>
            </tbody>
        </table>
        </body>
        </html>
<?php
        }
    } else {
        // Tampilkan pesan jika data tidak ditemukan
        echo "<p>Data pengguna tidak ditemukan.</p>";
    }
    // Tutup koneksi
    mysqli_close($connect);
}
?>
