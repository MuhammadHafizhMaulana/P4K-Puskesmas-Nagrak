var goldarField = document.getElementById('goldar');
var usiaKandunganField = document.getElementById('usia_kandungan');
var buttonSelanjutnya = document.getElementById('buttonFormSelanjutnya');


function openSpinner() {
    // Ambil elemen body dari dokumen
    var body = document.getElementsByTagName("body")[0];

    // Buat elemen div untuk spinner
    var spinnerDiv = document.createElement("div");
    spinnerDiv.className = "spinner-opened";

    // Buat elemen div untuk spinner-border
    var spinnerBorderDiv = document.createElement("div");
    spinnerBorderDiv.className = "spinner-border text-light";
    spinnerBorderDiv.setAttribute("role", "status");

    // Buat elemen span untuk teks "Loading..."
    var spinnerText = document.createElement("span");
    spinnerText.className = "visually-hidden";
    spinnerText.innerText = "Loading...";

    // Masukkan elemen span ke dalam elemen spinner-border
    spinnerBorderDiv.appendChild(spinnerText);

    // Masukkan elemen spinner-border ke dalam elemen spinner
    spinnerDiv.appendChild(spinnerBorderDiv);

    // Masukkan elemen spinner ke dalam elemen body
    body.appendChild(spinnerDiv);
}

buttonSelanjutnya.addEventListener('click', () => {
    const formJenisPembayaran = document.getElementById('formJenisPembayaran');

    formJenisPembayaran.innerHTML = `
    <div>
        <label for="jenis_pembayaran" onload="updateForm()">Jenis Pembayaran</label>
        <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-select" aria-label="Default select example" required onchange="updateForm()">
        <option value="">Pilih Jenis Pembayaran</option>
        <option value="tabungan">Tabungan Ibu Hamil</option>
        <option value="jkn">Jaminan Kesehatan Nasional</option>
        </select>
    </div>
    `
})

function updateForm() {
    var jenisPembayaran = document.getElementById('jenis_pembayaran').value;
    var additionalFields = document.getElementById('additionalFields');
    additionalFields.innerHTML = '';

    if (jenisPembayaran === 'tabungan') {
        additionalFields.innerHTML = `
            <label for="tabungan_hamil">Tabungan Ibu Hamil</label>
            <select id="tabungan_hamil" name="tabungan_hamil" class="form-select" required onchange="showRequiredDocuments()">
                <option value="">Pilih Tabungan</option>
                <option value="dada_linmas">DADA LINMAS</option>
                <option value="saldo_pribadi">Saldo Pribadi</option>
            </select>
            <div id="dataFields"></div>
        `;
    } else if (jenisPembayaran === 'jkn') {
        additionalFields.innerHTML = `
            <label for="kepemilikan_jaminan">Kepemilikan Jaminan Kesehatan Nasional</label>
            <select id="kepemilikan_jaminan" name="kepemilikan_jaminan" class="form-select" required onchange="updateJknFields()">
                <option value="">Pilih Kepemilikan</option>
                <option value="punya">Punya</option>
                <option value="tidak_punya">Tidak Punya</option>
            </select>
            <div id="jknFields"></div>
        `;
    }
}

function updateJknFields() {
    var kepemilikanJaminan = document.getElementById('kepemilikan_jaminan').value;
    var jknFields = document.getElementById('jknFields');
    jknFields.innerHTML = '';

    if (kepemilikanJaminan === 'punya') {
        jknFields.innerHTML = `
            <label for="status_jaminan">Status Jaminan</label>
            <select id="status_jaminan" name="status_jaminan" class="form-select" required onchange="updateStatusFields()">
                <option value="">Pilih Status</option>
                <option value="aktif">Aktif</option>
                <option value="tidak_aktif">Tidak Aktif</option>
            </select>
            <div id="statusFields"></div>
        `;
    } else if (kepemilikanJaminan === 'tidak_punya') {
        jknFields.innerHTML = `
            <label for="tipe_jkn">Tipe JKN</label>
            <select id="tipe_jkn" name="tipe_jkn" class="form-select" required onchange="showRequiredDocuments()">
                <option value="">Pilih Tipe</option>
                <option value="pbi">PBI</option>
                <option value="mandiri">Mandiri</option>
            </select>
            <div id="dataFields"></div>
        `;
    }
}

function updateStatusFields() {
    var statusJaminan = document.getElementById('status_jaminan').value;
    var statusFields = document.getElementById('statusFields');
    statusFields.innerHTML = '';

    if (statusJaminan === 'aktif') {
        statusFields.innerHTML = `
            <label for="jkn_aktif">JKN Aktif</label>
            <select id="jkn_aktif" name="jkn_aktif" class="form-select" required onchange="showRequiredDocuments()">
                <option value="">Pilih JKN</option>
                <option value="jkn_pbi">JKN PBI</option>
                <option value="mandiri">Mandiri</option>
            </select>
            <div id="dataFields"></div>
        `;
    } else if (statusJaminan === 'tidak_aktif') {
        statusFields.innerHTML = `
            <label for="jkn_tidakAktif">Isi data berikut untuk pengurusan JKN</label>
            <div id="dataFields"></div>
        `;
        showRequiredDocuments();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    showRequiredDocuments
});

function showRequiredDocuments() {
    var dataFields = document.getElementById('dataFields');
    dataFields.innerHTML = `
    <div class="d-flex justify-content-center w-100">
        <button onclick="openSpinner()" type="submit" class="btn btn-primary">INPUT</button>
    </div>
    `;
}

function openPhotoDialog(photoName) {
    const titleDialog = document.getElementById('titlePhotoDialog');
    const contentDialog = document.getElementById('contentPhotoDialog');
    if (photoName == "ktp") {
        titleDialog.innerText = "Detail Foto KTP"
        contentDialog.setAttribute('src', './proses/check_ktp.php')
    } else if (photoName == "kk") {
        titleDialog.innerText = "Detail Foto KK"
        contentDialog.setAttribute('src', './proses/check_kk.php')
    } else if (photoName == "pas_foto") {
        titleDialog.innerText = "Detail Pas Foto"
        contentDialog.setAttribute('src', './proses/check_pas_foto.php')
    } else if (photoName == "rujukan") {
        titleDialog.innerText = "Detail Foto Rujukan"
        contentDialog.setAttribute('src', './proses/check_rujukan.php')
    } else if (photoName == "rekomendasi") {
        titleDialog.innerText = "Detail Foto Rekomendasi"
        contentDialog.setAttribute('src', './proses/check_rekomendasi.php')
    }
}