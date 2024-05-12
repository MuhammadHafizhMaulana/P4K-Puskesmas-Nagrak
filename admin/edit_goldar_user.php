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
    // Query untuk mengambil data pengguna berdasarkan id yang sudah didekripsi
    $query = "SELECT * FROM kesehatan_user WHERE id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah data ditemukan
    if(mysqli_num_rows($result) > 0) {
        // Ambil data pengguna
        $data = mysqli_fetch_assoc($result);

        // Tampilkan data pengguna dalam formulir untuk diedit
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <table class="table">
        <tbody>
            <th>
                <h1>Data Kesehatan</h1>
            </th>
            <tr>
                <!-- Nama -->
                <?php
                        $query = "SELECT `nama`FROM user WHERE id = ?";
                        $stmt = mysqli_prepare($connect, $query);
                        mysqli_stmt_bind_param($stmt, "i", $id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                    
                        // Periksa apakah data ditemukan
                        if(mysqli_num_rows($result) > 0) {
                            // Ambil data pengguna
                            $ambil_nama = mysqli_fetch_assoc($result);
                        ?>
                <th>Nama</th>
                <td><?= isset($ambil_nama['nama']) ? $ambil_nama['nama'] : "Data belum diinput" ?></td>

                <?php } ?>
            </tr>
            <>
                <!-- Goldar -->

                <th>Golongan Darah</th>
                <td>
                    <form action="proses/edit_goldar_user_proses.php" method="post">
                        <div class="form-group">
                            <?php
                            $goldar = $data['goldar'];
                            ?>
                            <select id="goldar" name="goldar" class="form-select" aria-label="Default select example"
                                required>
                                <option value="-">Belum Mengetahui</option>
                                <option value="a+" <?php if ($goldar === 'a+') echo 'selected'; ?>>A+</option>
                                <option value="o+" <?php if ($goldar === 'o+') echo 'selected'; ?>>O+</option>
                                <option value="b+" <?php if ($goldar === 'b+') echo 'selected'; ?>>B+</option>
                                <option value="ab+" <?php if ($goldar === 'ab+') echo 'selected'; ?>>AB+</option>
                                <option value="a-" <?php if ($goldar === 'a-') echo 'selected'; ?>>A-</option>
                                <option value="o-" <?php if ($goldar === 'o-') echo 'selected'; ?>>O-</option>
                                <option value="b-" <?php if ($goldar === 'b-') echo 'selected'; ?>>B-</option>
                                <option value="ab-" <?php if ($goldar === 'ab-') echo 'selected'; ?>>AB-</option>
                            </select>

                            <!-- Menambahkan input tersembunyi untuk id -->
                            <input type="hidden" name="id" value="<?=$data['id_user']?>">

                            <button type="submit" class="btn btn-primary">Input</button>
                        </div>
                    </form>
                </td>
                </tr>
                <tr>
                    <!-- Usia Kandungan -->
                    <th>Usia Kandungan (Minggu)</th>
                    <td><?= isset($data['usia_kandungan']) ? $data['usia_kandungan'] : "Data belum diinput" ?></td>
                </tr>
                <tr>
                    <!-- Status -->
                    <th>Status</th>
                    <td><?= isset($data['status']) ? $data['status'] : "Data belum diinput" ?></td>
                </tr>
                </tr>
                <tr>
                    <!-- Tanggal update -->
                    <th>Terakhir Update</th>
                    <td><?= isset($data['tanggal_input']) ? $data['tanggal_input'] : "Data belum diinput" ?></td>

                </tr>
                <tr>
                    <th> <a href="data_user.php" class="button button-dark me-2">kembali</a></th>
                </tr>
        </tbody>
    </table>
</body>

</html>
<?php
    } else {
        echo "Data pengguna tidak ditemukan.";
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    echo "ID tidak ditemukan dalam URL.";
}

// Tutup koneksi
mysqli_close($connect);
?>