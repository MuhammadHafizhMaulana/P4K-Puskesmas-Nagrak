<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit(); // tambahkan exit setelah redirect
}

// Sambungan ke database
include '../proses/koneksi.php';

// Periksa apakah parameter id ada di URL
if (isset($_GET['id'])) {

    $id = $_GET['id'];
    // Query untuk mengambil data pengguna berdasarkan id yang sudah didekripsi
    $query = " SELECT ku.*, kb.* FROM kesehatan_user ku INNER JOIN kb ON ku.id_user = kb.id_user WHERE ku.id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    $query = "SELECT `nama`FROM user WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $nameResult = mysqli_stmt_get_result($stmt);
    
    // Periksa apakah data ditemukan

        // Ambil data pengguna
        $data = mysqli_fetch_assoc($result);
        $ambil_nama = mysqli_fetch_assoc($nameResult);

        // Function untuk merubah format tanggal
        function formatTanggal($tanggal_input)
        {
            $timestamp = strtotime($tanggal_input);
            $tanggal_format = date("d M Y", $timestamp);

            return $tanggal_format;
        }

        if ($data) {
            if (!empty($data['tanggal_input'])) {
                $data['tanggal_input'] = formatTanggal($data['tanggal_input']);
            } else {
                $data['tanggal_input'] = '-';
            }
        }
        if ($data) {
            if (!empty($data['hpht'])) {
                $data['hpht'] = formatTanggal($data['hpht']);
            } else {
                $data['hpht'] = '-';
            }
        }
        if ($data) {
            if (!empty($data['taksiran_persalinan'])) {
                $data['taksiran_persalinan'] = formatTanggal($data['taksiran_persalinan']);
            } else {
                $data['taksiran_persalinan'] = '-';
            }
        }

        if (isset($_GET['success'])) {
            $proccessIsSuccess = true;
            if ($_GET['success'] == "update_successful") {
                $message = "Anda berhasil mengedit golongan darah.";
            }
        } else if (isset($_GET['gagal'])) {
            $proccessIsSuccess = false;
            if ($_GET['error'] == "update_failed") {
                $message = "Edit golongan darah gagal dilakukan.";
            }
        }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/adminKesehatanUser&DetailPendonor.css">
</head>

<body>
    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="../assets/logo-kemenkes.png" alt="Logo Kemenkes">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                        fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="landing.php">Dashboard</a>
                    <a class="nav-link" href="data_user.php">User</a>
                    <a class="nav-link" href="listKesehatanUser.php">Kesehatan User</a>
                    <a class="nav-link" href="pendonor.php">Pendonor</a>
                    <a class="nav-link" href="profile_admin.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="boxKesehatanUser">
        <h1 style="
                font-weight: bold;
                ">
            Data Kesehatan User
        </h1>
        <?php if ($data) { ?>
        <br>
        <div class="w-100">
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Nama</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                <?php echo isset($ambil_nama['nama']) ? ucwords($ambil_nama['nama']) : "-"; ?>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-sm-5 text-start fw-bolder">Goldar</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <form class="text-start d-flex align-items-center p-0">
                        <div class="form-group m-0">
                            <select id="goldarGet" name="goldarGet" class="form-select" disabled required>
                                <option value="-" <?php if ($data['goldar'] === '-') echo 'selected'; ?>>-</option>
                                <option value="a+" <?php if ($data['goldar'] === 'a+') echo 'selected'; ?>>A+</option>
                                <option value="o+" <?php if ($data['goldar'] === 'o+') echo 'selected'; ?>>O+</option>
                                <option value="b+" <?php if ($data['goldar'] === 'b+') echo 'selected'; ?>>B+</option>
                                <option value="ab+" <?php if ($data['goldar'] === 'ab+') echo 'selected'; ?>>AB+
                                </option>
                                <option value="a-" <?php if ($data['goldar'] === 'a-') echo 'selected'; ?>>A-</option>
                                <option value="o-" <?php if ($data['goldar'] === 'o-') echo 'selected'; ?>>O-</option>
                                <option value="b-" <?php if ($data['goldar'] === 'b-') echo 'selected'; ?>>B-</option>
                                <option value="ab-" <?php if ($data['goldar'] === 'ab-') echo 'selected'; ?>>AB-
                                </option>
                            </select>
                        </div>
                        <button id="btn-edit-goldar" type="button" onclick="editGoldar()"
                            class="btn btn-primary">Edit</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Usia Kandungan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['usia_kandungan'] ? $data['usia_kandungan'] .'Minggu' : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Status Goldar</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['status'] ? ucwords($data['status']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Terakhir User Update</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['tanggal_input'] ? ucwords($data['tanggal_input']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Taksiran Persalinan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['taksiran_persalinan'] ? ucwords($data['taksiran_persalinan']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">HPHT</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['hpht'] ? ucwords($data['hpht']) : '-' ?>
                </div>
            </div>
            <br><br>
            <h1 style="
                font-weight: bold;
                ">
        </div>
        <?php } else { ?>
        <div class="alert alert-primary text-center">
            <h2>User atas nama <?php echo $ambil_nama['nama'] ?> belum melakukan penginputan data</h2>
        </div>
        <?php } ?>
    </div>


    <!-- Modal Konfirmasi Edit -->
    <div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="post" action="proses/edit_goldar_user_proses.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUpdateModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $id ?>" name="id" id="id">
                    <input type="hidden" name="goldar" id="goldar">
                    Apakah Anda yakin ingin mengedit golongan darah?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Iya</button>
                </div>
            </form>
        </div>
    </div>
    <button style="display: none;" id="buttonAlert" type="button" class="btn btn-primary" data-bs-toggle="modal"
        data-bs-target="#exampleModal"></button>

    <?php
                if (isset($_GET['success']) || isset($_GET['error'])) { ?>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-primary" id="exampleModalLabel">
                        <?php echo $proccessIsSuccess ? "BERHASIL" : "GAGAL" ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary text-center" role="alert">
                        <?php echo $message ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
                }
                ?>

    <script src="../js/adminKesehatanUser&DetailPendonor.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
<?php
    }

    // Tutup statement
    mysqli_stmt_close($stmt);



// Tutup koneksi
mysqli_close($connect);
?>