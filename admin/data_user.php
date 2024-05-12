<?php
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
      header('Location: login_admin.php');
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <h1>Data Pengguna</h1>
    <table class="table">
        <tr>
            <th>Nama</th>
            <th>Usia</th>
            <th>Nomor HP</th>
            <th>Alamat</th>
            <th>Kesehatan</th>
            <th>Action</th>
        </tr>
        <?php
            // Sambungan ke database
            include '../proses/koneksi.php';

            // Query untuk mengambil semua data dari tabel user
            $query = "SELECT * FROM user";
            $result = mysqli_query($connect, $query);

            // Periksa apakah ada baris data yang ditemukan
            if(mysqli_num_rows($result) > 0) {
                // Mulai iterasi melalui setiap baris data
                while($data = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?= $data['nama'] ?></td>
            <td><?= $data['usia'] ?></td>
            <td><?= $data['nomorHP'] ?></td>
            <td><?= $data['alamat'] ?></td>
            <td><h5><a href="kesehatan_user.php?id=<?= $data['id'] ?>">Kesehatan User</a></h5></td>
            <td> <h5><a href="edit_user.php?id=<?= $data['id'] ?>">Edit</a></h5> 
            <h5><a href="proses/hapus_user.php?id=<?= $data['id'] ?>">Hapus</a></h5> </td>
        </tr>
        <?php 
                } // Tutup while loop
            } else {
                // Tampilkan pesan jika data tidak ditemukan
                echo "<tr><td colspan='4'>Data pengguna tidak ditemukan.</td></tr>";
            }
            // Tutup koneksi
            mysqli_close($connect);
        ?>
    </table>
</body>

</html>
