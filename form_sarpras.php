<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}

include './proses/koneksi.php';

$id = $_SESSION['id'];
$query = "SELECT * FROM `sarpras` WHERE `id_user` = ?";
if ($stmt = mysqli_prepare($connect, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($connect);
        exit();
    }
} else {
    echo "Error: " . mysqli_error($connect);
    exit();
}

// Memisahkan jenis penolong dan nama penolong
$penolong_data = isset($data['penolong']) ? explode(' + ', $data['penolong']) : ['', ''];
$jenis_penolong = trim($penolong_data[0]);
$nama_penolong = trim($penolong_data[1]);
mysqli_close($connect);

$usg_data = isset($data['usg']) ? explode(' + ', $data['usg']) : ['', '', '', ''];
$status_usg = isset($usg_data[0]) ? $usg_data[0] : '';
$tanggal_usg = isset($usg_data[1]) ? $usg_data[1] : '';
$umur_usg = isset($usg_data[2]) ? $usg_data[2] : '';
$hasil_usg = isset($usg_data[3]) ? $usg_data[3] : '';

if ($status_usg === 'belum') {
    // Lakukan sesuatu jika status USG adalah "belum"
    // Misalnya:
    $tanggal_usg = ''; // Atur tanggal menjadi kosong
    $hasil_usg = ''; // Atur hasil USG menjadi kosong
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Sarpras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/generalForm.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
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
                <div class="navbar-nav ms-auto align-items-center">
                    <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
                    <a class="nav-link active" href="dashboard_sarpras.php">Sarpras</a>
                    <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
                    <a class="nav-link" href="dashboard_kb.php">Konsul KB</a>
                    <a class="nav-link" href="profile.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">
                        <button type="button" class="btn btn-outline-danger">Logout</button>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div id="formDonorDarah">
        <h1 style="font-weight: bold; font-size: xxx-large">
            Sarana Prasarana
        </h1>
        <br />
        <p>
            Lengkapi data sarana prasarana anda berikut
        </p>

        <form method="post" action="proses/sarpras_proses.php">
            <div class="form-group">
                <label for="transportasi" class="form-label">Pilih Transportasi Anda</label>
                <select oninput="cekValidButton()" id="transportasi" name="transportasi" class="form-select" id="
                    transportasi" name="transportasi" class="form-select" aria-label="Default select example" required>
                    <option value="-">Pilih Jenis Transportasi Anda</option>
                    <option value="ambulance desa"
                        <?php if (isset($data['transportasi']) && $data['transportasi'] === 'ambulance desa') echo 'selected'; ?>>
                        Ambulance Desa</option>
                    <option value="ambulance pkm"
                        <?php if (isset($data['transportasi']) && $data['transportasi'] === 'ambulance pkm') echo 'selected'; ?>>
                        Ambulance PKM</option>
                    <option value="kendaraan pribadi"
                        <?php if (isset($data['transportasi']) && $data['transportasi'] === 'kendaraan pribadi') echo 'selected'; ?>>
                        Kendaraan Pribadi</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nama_supir" class="form-label">Nama Supir</label>
                <input oninput="cekValidButton()" type="text" id="nama_supir" name="nama_supir" class="form-control" placeholder="nama supir"
                    value="<?php echo htmlspecialchars(isset($data['nama_supir']) ? $data['nama_supir'] : '', ENT_QUOTES); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="no_supir" class="form-label">Nomor Handphone Supir</label>
                <input oninput="cekValidButton()" type="text" id="no_supir" name="no_supir" class="form-control" placeholder="nomor handphone"
                    value="<?php echo htmlspecialchars(isset($data['no_supir']) ? $data['no_supir'] : '', ENT_QUOTES); ?>"
                    required>
                <div id="nomorHPSupirAlert" class="form-text text-danger"></div>
            </div>
            <div class="form-group">
                <label for="nama_pendamping" class="form-label">Nama Pendamping Persalinan</label>
                <input oninput="cekValidButton()" type="text" id="nama_pendamping" name="nama_pendamping" class="form-control" placeholder="nama pendamping" value="<?php echo htmlspecialchars(isset($data['nama_pendamping']) ? $data['nama_pendamping'] : '', ENT_QUOTES); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="no_pendamping" class="form-label">Nomor Handphone Pendamping</label>
                <input oninput="cekValidButton()" type="text" id="no_pendamping" name="no_pendamping" class="form-control" placeholder="nomor handphone" value="<?php echo htmlspecialchars(isset($data['no_pendamping']) ? $data['no_pendamping'] : '', ENT_QUOTES); ?>" required>
                <div id="nomorHPPendampingAlert" class="form-text text-danger"></div>
            </div>
            <div class="form-group">
                <label for="tujuan" class="form-label">Tempat Persalinan</label>
                <input oninput="cekValidButton()" type="text" id="tujuan" name="tujuan" class="form-control" placeholder="tempat persalinan"
                    value="<?php echo htmlspecialchars(isset($data['tujuan']) ? $data['tujuan'] : '', ENT_QUOTES); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="penolong" class="form-label">Pilih Penolong Persalinan Anda</label>
                <select oninput="cekValidButton()" id="penolong" name="penolong" class="form-select" aria-label="Default select example" required>
                    <option value="-">Pilih Penolong Persalinan Anda</option>
                    <option value="bidan / dokter umum"
                        <?php if ($jenis_penolong === 'bidan / dokter umum') echo 'selected'; ?>>Bidan / Dokter Umum
                    </option>
                    <option value="dokter kandungan"
                        <?php if ($jenis_penolong === 'dokter kandungan') echo 'selected'; ?>>Dokter Kandungan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nama_penolong" class="form-label">Nama Penolong Persalinan Anda</label>
                <input oninput="cekValidButton()" type="text" id="nama_penolong" name="nama_penolong" class="form-control"
                    placeholder="contoh : Bidan Herlina"
                    value="<?php echo htmlspecialchars($nama_penolong, ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="usg" class="form-label">Apakah Pernah USG</label>
                <select id="usg" name="usg" class="form-select" aria-label="Default select example" required
                    onchange="updateForm()">
                    <option value="-">Pilih Jawaban Anda</option>
                    <option value="pernah" <?php if ($status_usg === 'pernah') echo 'selected'; ?>>Pernah</option>
                    <option value="belum" <?php if ($status_usg === 'belum') echo 'selected'; ?>>Belum</option>
                </select>
            </div>
            <?php if ($status_usg === 'pernah') { ?>
            <div id="additionalFields" class="d-flex flex-column">
                <div id="tanggal-usg-group" class="form-group">
                    <label for="tanggal-usg" class="form-label">Tanggal USG Terakhir</label>
                    <input oninput="cekValidButton()" type="date" id="tanggal-usg" name="tanggal_usg" class="form-control"
                        value="<?php echo htmlspecialchars($tanggal_usg, ENT_QUOTES); ?>" required>
                </div>
                <div id="umur-usg-group" class="form-group">
                    <label for="umur_usg" class="form-label">Berapakah Usia Kandungan Saat USG Terakhir (Minggu)</label>
                    <input oninput="cekValidButton()" type="number" id="umur_usg" min="0" name="umur_usg" class="form-control"
                        value="<?php echo htmlspecialchars($umur_usg, ENT_QUOTES); ?>" required>
                </div>
                <div id="status-usg-group" class="form-group">
                    <label for="status_usg" class="form-label">Bagaimana Hasil USG Terakhir Anda</label>
                    <select oninput="cekValidButton()" id="status_usg" name="status_usg" class="form-select" aria-label="Default select example"
                        required>
                        <option value="-">Pilih Jawaban Anda</option>
                        <option value="baik" <?php if ($hasil_usg === 'baik') echo 'selected'; ?>>Baik</option>
                        <option value="tidak baik" <?php if ($hasil_usg === 'tidak baik') echo 'selected'; ?>>Tidak
                            Baik</option>
                    </select>
                </div>
            </div>
            <?php } else { ?>
            <div id="additionalFields" class="d-flex flex-column"></div>
            <?php } ?>
            <br>
            <button id="buttonSubmit" type="submit" value="Input" class="btn btn-danger">Input</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="js/sarprasForm.js"></script>
</body>

</html>