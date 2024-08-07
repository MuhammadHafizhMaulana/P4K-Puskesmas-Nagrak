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

$query = "SELECT `nama`, `nomorHP`, `alamat` FROM `user` WHERE `id` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nama, $nomorHP, $alamat);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembiayaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/generalForm.css">
</head>

<body>
    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
                <img src="./assets/logo-puskesmas-nagrak.png" alt="Logo Puskesmas Nagrak">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto align-items-center">
                    <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    <a class="nav-link active" href="dashboard_pembiayaan.php">Pembiayaan</a>
                    <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
                    <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
                    <a class="nav-link" href="dashboard_kb.php">Konsultasi KB</a>
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
            Biaya Persalinan
        </h1>
        <br/>
        <p>
            Lengkapi data berikut untuk melengkapi data pembiayaan anda
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
                            <h4 class="text-white">Klik untuk melihat detail</h4>
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
                            <h4 class="text-white">Klik untuk melihat detail</h4>
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
                            <h4 class="text-white">Klik untuk melihat detail</h4>
                        </div>
                        </div>
                    </button>
                <?php } else { ?>
                    <div style="text-align: center;" class="m-0 alert alert-primary" role="alert">
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
                            <h4 class="text-white">Klik untuk melihat detail</h4>
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
                            <h4 class="text-white">Klik untuk melihat detail</h4>
                        </div>
                        </div>
                    </button>
                <?php } else { ?>
                    <div style="text-align: center;" class="m-0 alert alert-primary" role="alert">
                        <h6>Anda belum pernah menginputkan file rekomendasi sebelumnya</h6>
                    </div>
                <?php }} ?>
                <input accept=".jpeg, .jpg, .png" type="file" id="rekomendasi" name="rekomendasi" class="form-control" >
            </div>

            <?php if ($data == null) { ?>
                <div id="formDetailPembiayaan">
                    <div class="d-flex justify-content-center">    
                        <div>
                            <br>
                            <button type="button" class="btn btn-danger" id="buttonFormSelanjutnya" disabled>Selanjutnya</button>
                        </div>    
                    </div>
                </div> 
            <?php } else { ?>
                <div id="formDetailPembiayaan">
                    <div class="form-group">
                        <label for="saldoTabungan">Masukkan saldo tabungan persalinan yang sudah dimiliki</label>
                        <div class="d-flex align-items-center">
                            Rp.
                            <input oninput="cekKelengkapanField()" type="number" value="<?= $data['saldo_tabungan'] ?>" min="0" class="form-control registrasi-form fieldSelainFoto" id="saldoTabungan" name="saldoTabungan" placeholder="saldo tabungan" min="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jenis_pembayaran">Jenis pembiayaan yang digunakan</label>
                        <select onchange="cekJenisPembiayaan()" id="jenis_pembayaran" name="jenis_pembayaran" class="form-select fieldSelainFoto" aria-label="Default select example" required>
                            <option value="-">Pilih jenis pembiayaan</option>
                            <option value="BPJS Aktif" <?php if ($data['jenis_pembayaran'] === 'BPJS Aktif') echo 'selected' ?>>BPJS Aktif</option>
                            <option value="BPJS Tidak Aktif (Tidak Punya)" <?php if ($data['jenis_pembayaran'] === 'BPJS Tidak Aktif (Tidak Punya)') echo 'selected' ?>>BPJS Tidak Aktif (Tidak Punya)</option>
                            <option value="Saldo Tabungan" <?php if ($data['jenis_pembayaran'] === 'Saldo Tabungan') echo 'selected' ?>>Saldo Tabungan</option>
                        </select>
                    </div>

                    <div id="formNomorBPJS">
                        <?php if ($data['jenis_pembayaran'] == 'BPJS Aktif') { ?>
                            <div class="form-group">
                                <label for="nomorBPJS">Masukan nomor BPJS</label>
                                <input oninput="cekFormatNomorBPJS()" type="text" min="0" class="form-control registrasi-form fieldSelainFoto" id="nomorBPJS" name="nomorBPJS" placeholder="nomor BPJS" min="0" value="<?= $data['nomor_bpjs'] ?>" required>
                            </div>
                        <?php } ?>
                    </div>

                    <div id="dataFields">
                        <br>
                        <div class="d-flex justify-content-center w-100">
                            <button id="buttonSubmit" type="submit" class="btn btn-danger">Input</button>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (!$data) { ?>
                <div class="modal fade" id="modalKonsultasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Form permintaan jadwal konsultasi pembiayaan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning" role="alert">
                                Setelah tombol "Input" ditekan, anda akan diarahkan ke whatsapp untuk mengirim pesan permintaan konsultasi Pembiayaan kepada nakes.
                                </div>
                                <input id="nama" value="<?php echo $nama; ?>" type="hidden" disabled>
                                <input id="nomorHP" value="<?php echo $nomorHP; ?>" type="hidden" disabled>
                                <input id="alamat" value="<?php echo $alamat; ?>" type="hidden" disabled>
                                <div style="width: 100%;">
                                    <label for="waktu_konsultasi">Tentukan tanggal untuk konsultasi Pembiayaan</label>
                                    <input style="width: 100%;" type="date" class="form-control"
                                        id="waktu_konsultasi" name="waktu_konsultasi">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button id="submitJadwal" disabled onclick="openSpinner()" type="submit"
                                    data-bs-dismiss="modal" class="btn btn-primary">Input</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </form>
                   
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-photo-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="titlePhotoDialog"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div style=" overflow-x: scroll; width: 100%; max-width: min-content;">
                  <img style="height: 70vh;" id="contentPhotoDialog" alt="" srcset="">
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="js/pembiayaanForm.js"></script>
</body>
</html>