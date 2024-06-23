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

function getKTPImage() {
    // Ambil ID pengguna dari session
    $userID = $_SESSION['id']; // Anda harus menyesuaikan ini dengan cara Anda mengambil ID pengguna dari session

    // Cek apakah ID pengguna sesuai dengan ID dalam nama file ktp
    if ($userID) {
        // Path gambar ktp
        $pathToKTP = "./ktp_$userID.jpeg"; // Ganti ekstensi file sesuai kebutuhan

        // Periksa apakah gambar ktp pengguna ada
        if (file_exists($pathToKTP)) {
            // Jika ada, kirimkan gambar sebagai respons dari server
            header("Content-Type: image/jpeg"); // Ganti jenis konten sesuai ekstensi file
            readfile($pathToKTP);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembiayaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/general_Form.css">
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
            Biaya Persalinan
        </h1>
        <br/>
        <p>
            Lengkapi data berikut untuk melengkapi data pembayaran anda
        </p>
        <form id="formPembiayaan" method="post" action="proses/pembiayaan_proses.php" enctype="multipart/form-data">
            <div class="text-start" id="formFields">
                <label for="ktp"><?php echo $data ? 'Foto KTP Terakhir' : 'Masukan Foto KTP';?></label>
                <?php
                if ($data) {
                ?>
                    <button style="display:contents" onclick="openPhotoDialog('ktp')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="border: none; padding: 0">
                        <div id="photoKTP" class="boxPhoto rounded-3 border border-2 border-primary" style='background-image: url("./proses/check_ktp.php");'>
                        <div class="photoDescription w-100 h-100 rounded-2">
                            <h4 class="text-white">Lihat Detail Foto</h4>
                        </div>
                        </div>
                    </button>
                <?php } ?>
                <input accept=".jpeg, .jpg, .png" type="file" id="ktp" name="ktp" class="required-field form-control" <?php echo $data ? "" : "required" ?>>
                <br>
                <label for="kk"><?php echo $data ? 'Foto KK Terakhir' : 'Masukan Foto KK';?></label>
                <?php
                if ($data) {
                ?>
                    <button style="display:contents" onclick="openPhotoDialog('kk')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="border: none; padding: 0">
                        <div id="photoKK" class="boxPhoto rounded-3 border border-2 border-primary" style='background-image: url("./proses/check_kk.php");'>
                        <div class="photoDescription w-100 h-100 rounded-2">
                            <h4 class="text-white">Lihat Detail Foto</h4>
                        </div>
                        </div>
                    </button>
                <?php } ?>
                <input accept=".jpeg, .jpg, .png" type="file" id="kk" name="kk" class="required-field form-control"  <?php echo $data ? "" : "required" ?>>
                <br>
                <label for="rujukan"><?php echo $data ? 'Foto Rujukan Terakhir' : 'Masukan Foto Rujukan (jika ada)';?></label>
                <?php if ($data) { 
                    if ($data && $data['rujukan'] != "-") {
                ?>        
                    <button style="display:contents" onclick="openPhotoDialog('rujukan')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="border: none; padding: 0">
                        <div id="photoRujukan" class="boxPhoto rounded-3 border border-2 border-primary" style='background-image: url("./proses/check_rujukan.php");'>
                        <div class="photoDescription w-100 h-100 rounded-2">
                            <h4 class="text-white">Lihat Detail Foto</h4>
                        </div>
                        </div>
                    </button>
                <?php } else { ?>
                    <div style="width: 320px; text-align: center;" class="m-0 alert alert-primary" role="alert">
                        <h6>Anda belum pernah menginputkan file rujukan sebelumnya</h6>
                    </div>
                <?php }} ?>
                <input accept=".jpeg, .jpg, .png" type="file" id="rujukan" name="rujukan" class="form-control">
                <br>
                <label for="pas_foto"><?php echo $data ? 'Pas Foto 3x4 Terakhir' : 'Masukan Pas Foto 3x4';?></label>
                <?php
                if ($data) {
                ?>
                    <button style="display:contents" onclick="openPhotoDialog('pas_foto')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="border: none; padding: 0">
                        <div id="photoPasFoto" class="boxPhoto rounded-3 border border-2 border-primary" style='background-image: url("./proses/check_pas_foto.php");'>
                        <div class="photoDescription w-100 h-100 rounded-2">
                            <h4 class="text-white">Lihat Detail Foto</h4>
                        </div>
                        </div>
                    </button>
                <?php } ?>
                <input accept=".jpeg, .jpg, .png" type="file" id="pas_foto" name="pas_foto" class="required-field form-control"  <?php echo $data ? "" : "required" ?>>
                <br>
                <label for="rekomendasi"><?php echo $data ? 'Foto Surat Rekomendasi dari Kelurahan Terakhir' : 'Masukan Foto Surat Rekomendasi dari Kelurahan (jika ada)';?></label>
                <?php if ($data) { 
                    if ($data && $data['rekomendasi'] != "-") {
                ?>        
                    <button style="display:contents" onclick="openPhotoDialog('rekomendasi')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="border: none; padding: 0">
                        <div id="photoRekomendasi" class="boxPhoto rounded-3 border border-2 border-primary" style='background-image: url("./proses/check_rekomendasi.php");'>
                        <div class="photoDescription w-100 h-100 rounded-2">
                            <h4 class="text-white">Lihat Detail Foto</h4>
                        </div>
                        </div>
                    </button>
                <?php } else { ?>
                    <div style="width: 320px; text-align: center;" class="m-0 alert alert-primary" role="alert">
                        <h6>Anda belum pernah menginputkan file rekomendasi sebelumnya</h6>
                    </div>
                <?php }} ?>
                <input accept=".jpeg, .jpg, .png" type="file" id="rekomendasi" name="rekomendasi" class="form-control" >
            </div>
            <?php if ($data == null) { ?> 
                <div class="mt-0 text-start">
                <div id="formJenisPembayaran" class="d-flex justify-content-center">    
                    <div>
                        <br>
                        <button type="button" class="btn btn-primary" id="buttonFormSelanjutnya" disabled>Selanjutnya</button>
                    </div>    
                </div>
                </div>
                <div id="additionalFields" class="text-start"></div>
            <?php } else { ?>
                <div class="mt-0 form-group text-start">
                    <br>
                    <label for="jenis_pembayaran" onload="updateForm()">Jenis Pembayaran</label>
                    <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-select" aria-label="Default select example" required onchange="updateForm()">
                    <option value="">Pilih Jenis Pembayaran</option>
                    <option value="tabungan" <?php if ($data['jenis_pembayaran'] === 'tabungan') echo 'selected'; ?>>Tabungan Ibu Hamil</option>
                    <option value="jkn"<?php if ($data['jenis_pembayaran'] === 'jkn') echo 'selected'; ?>>Jaminan Kesehatan Nasional</option>
                    </select>
                </div>
                <div id="additionalFields" class="text-start">
                    <?php if ($data['jenis_pembayaran'] == "tabungan") { ?>
                        <br>
                        <label for="tabungan_hamil">Tabungan Ibu Hamil</label>
                        <select id="tabungan_hamil" name="tabungan_hamil" class="form-select" required onchange="showRequiredDocuments()">
                            <option value="">Pilih Tabungan</option>
                            <option value="dada_linmas" <?php if ($data['jenis_tabungan'] === 'dadalinmas') echo 'selected'; ?>>Dadalinmas</option>
                            <option value="saldo_pribadi" <?php if ($data['jenis_tabungan'] === 'saldo_pribadi') echo 'selected'; ?>>Saldo Pribadi</option>
                        </select>
                        <div id="dataFields" class="d-flex flex-column"></div>
                        <?php } else if ($data['jenis_pembayaran'] == "jkn") {?>
                        <br>
                        <label for="kepemilikan_jaminan">Kepemilikan Jaminan Kesehatan Nasional</label>
                        <select id="kepemilikan_jaminan" name="kepemilikan_jaminan" class="form-select" required onchange="updateJknFields()">
                            <option value="">Pilih Kepemilikan</option>
                            <option value="punya" <?php if ($data['status'] === 'aktif' || $data['status'] === 'non aktif') echo 'selected'; ?>>Punya</option>
                            <option value="tidak_punya" <?php if ($data['status'] === 'tidak punya') echo 'selected'; ?>>Tidak Punya</option>
                        </select>
                        <div id="jknFields">
                            <?php if ($data['status'] === 'aktif' || $data['status'] === 'non aktif') { ?>
                                <br>
                                <label for="status_jaminan">Status Jaminan</label>
                                <select id="status_jaminan" name="status_jaminan" class="form-select" required onchange="updateStatusFields()">
                                    <option value="">Pilih Status</option>
                                    <option value="aktif" <?php if ($data['status'] === 'aktif') echo 'selected'; ?>>Aktif</option>
                                    <option value="tidak_aktif" <?php if ($data['status'] === 'non aktif') echo 'selected'; ?>>Tidak Aktif</option>
                                </select>
                                <div id="statusFields">
                                    <?php if ($data['status'] === 'aktif') { ?>
                                        <br>
                                        <label for="jkn_aktif">JKN Aktif</label>
                                        <select id="jkn_aktif" name="jkn_aktif" class="form-select" required onchange="showRequiredDocuments()">
                                            <option value="">Pilih JKN</option>
                                            <option value="jkn_pbi" <?php if ($data['jenis_tabungan'] === 'pbi') echo 'selected'; ?>>JKN PBI</option>
                                            <option value="mandiri" <?php if ($data['jenis_tabungan'] === 'mandiri') echo 'selected'; ?>>Mandiri</option>
                                        </select>
                                        <div id="dataFields" class="d-flex flex-column"></div>
                                    <?php } else if ($data['status'] === 'non aktif') { ?>
                                        <br>
                                        <div id="dataFields" class="d-flex flex-column"></div>
                                    <?php } ?>
                                </div>
                            <?php } else if ($data['status'] === 'tidak punya') {?>
                                <br>
                                <label for="tipe_jkn">Tipe JKN</label>
                                <select id="tipe_jkn" name="tipe_jkn" class="form-select" required onchange="showRequiredDocuments()">
                                    <option value="">Pilih Tipe</option>
                                    <option value="pbi" <?php if ($data['jenis_tabungan'] === 'pbi') echo 'selected'; ?>>PBI</option>
                                    <option value="mandiri" <?php if ($data['jenis_tabungan'] === 'mandiri') echo 'selected'; ?>>Mandiri</option>
                                </select>
                                <div id="dataFields" class="d-flex flex-column"></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </form>
                   
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="titlePhotoDialog"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="contentPhotoDialog" alt="" srcset="">
            </div>
            </div>
        </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="js/pembiayaan_Form.js"></script>
</body>
</html>