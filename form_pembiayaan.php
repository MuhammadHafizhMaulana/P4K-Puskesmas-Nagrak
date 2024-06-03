<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
}

include './proses/koneksi.php';

$id = $_SESSION['id'];
$query = "SELECT * FROM `pembiayaan` WHERE `id_user` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Mengambil data dari hasil query
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembiayaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/donorDarah.css">
</head>

<body>
    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
                    <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
                    <a class="nav-link" href="donor_darah.php">DonorDarahTambah</a>
                    <a class="nav-link" href="profile.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="formDonorDarah">
        <h1 style="font-weight: bold; font-size: xxx-large">
            Pembiayaan Persalinan
        </h1>
        <br/>
        <p>
            Lengkapi data berikut untuk melengkapi data pembayaran anda
        </p>
        <form id="formPembiayaan" method="post" action="proses/pembiayaan_proses.php" enctype="multipart/form-data">
            <?php if ($data == null) { ?> 
                <div class="form-group text-start">
                    <label for="jenis_pembayaran" onload="updateForm()">Jenis Pembayaran</label>
                    <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-select" aria-label="Default select example" required onchange="updateForm()">
                    <option value="">Pilih Jenis Pembayaran</option>
                    <option value="tabungan">Tabungan Ibu Hamil</option>
                    <option value="jkn">Jaminan Kesehatan Nasional</option>
                    </select>
                </div>
                <div id="additionalFields" class="text-start"></div>
            <?php } else { ?>
                <img src="/proses/getKTP.php">
                <div class="form-group text-start">
                    <label for="jenis_pembayaran" onload="updateForm()">Jenis Pembayaran</label>
                    <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-select" aria-label="Default select example" required onchange="updateForm()">
                    <option value="">Pilih Jenis Pembayaran</option>
                    <option value="tabungan" <?php if ($data['jenis_pembayaran'] === 'tabungan') echo 'selected'; ?>>Tabungan Ibu Hamil</option>
                    <option value="jkn"<?php if ($data['jenis_pembayaran'] === 'jkn') echo 'selected'; ?>>Jaminan Kesehatan Nasional</option>
                    </select>
                </div>
                <div id="additionalFields" class="text-start">
                    <?php if ($data['jenis_pembayaran'] == "tabungan") { ?>
                        <label for="tabungan_hamil">Tabungan Ibu Hamil</label>
                        <select id="tabungan_hamil" name="tabungan_hamil" class="form-select" required onchange="showRequiredDocuments()">
                            <option value="">Pilih Tabungan</option>
                            <option value="dada_linmas" <?php if ($data['jenis_tabungan'] === 'dadalinmas') echo 'selected'; ?>>DADA LINMAS</option>
                            <option value="saldo_pribadi" <?php if ($data['jenis_tabungan'] === 'saldo_pribadi') echo 'selected'; ?>>Saldo Pribadi</option>
                        </select>
                        <div id="dataFields"></div>
                    <?php } else if ($data['jenis_pembayaran'] == "jkn") {?>
                        <label for="kepemilikan_jaminan">Kepemilikan Jaminan Kesehatan Nasional</label>
                        <select id="kepemilikan_jaminan" name="kepemilikan_jaminan" class="form-select" required onchange="updateJknFields()">
                            <option value="">Pilih Kepemilikan</option>
                            <option value="punya" <?php if ($data['status'] === 'aktif' || $data['status'] === 'non aktif') echo 'selected'; ?>>Punya</option>
                            <option value="tidak_punya" <?php if ($data['status'] === 'tidak punya') echo 'selected'; ?>>Tidak Punya</option>
                        </select>
                        <div id="jknFields">
                            <?php if ($data['status'] === 'aktif' || $data['status'] === 'non aktif') { ?>
                                <label for="status_jaminan">Status Jaminan</label>
                                <select id="status_jaminan" name="status_jaminan" class="form-select" required onchange="updateStatusFields()">
                                    <option value="">Pilih Status</option>
                                    <option value="aktif" <?php if ($data['status'] === 'aktif') echo 'selected'; ?>>Aktif</option>
                                    <option value="tidak_aktif" <?php if ($data['status'] === 'non aktif') echo 'selected'; ?>>Tidak Aktif</option>
                                </select>
                                <div id="statusFields">
                                    <?php if ($data['status'] === 'aktif') { ?>
                                        <label for="jkn_aktif">JKN Aktif</label>
                                        <select id="jkn_aktif" name="jkn_aktif" class="form-select" required onchange="showRequiredDocuments()">
                                            <option value="">Pilih JKN</option>
                                            <option value="jkn_pbi" <?php if ($data['jenis_tabungan'] === 'pbi') echo 'selected'; ?>>JKN PBI</option>
                                            <option value="mandiri" <?php if ($data['jenis_tabungan'] === 'mandiri') echo 'selected'; ?>>Mandiri</option>
                                        </select>
                                        <div id="dataFields"></div>
                                    <?php } else if ($data['status'] === 'non aktif') { ?>
                                        <label for="jkn_tidakAktif">Isi data berikut untuk pengurusan JKN</label>
                                        <div id="dataFields"></div>
                                    <?php } ?>
                                </div>
                            <?php } else if ($data['status'] === 'tidak punya') {?>
                                <label for="tipe_jkn">Tipe JKN</label>
                                <select id="tipe_jkn" name="tipe_jkn" class="form-select" required onchange="showRequiredDocuments()">
                                    <option value="">Pilih Tipe</option>
                                    <option value="pbi" <?php if ($data['jenis_tabungan'] === 'pbi') echo 'selected'; ?>>PBI</option>
                                    <option value="mandiri" <?php if ($data['jenis_tabungan'] === 'mandiri') echo 'selected'; ?>>Mandiri</option>
                                </select>
                                <div id="dataFields"></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="js/formPembiayaan.js"></script>
</body>

</html>