var goldarField = document.getElementById('goldar');
var usiaKandunganField = document.getElementById('usia_kandungan');


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

document.addEventListener('DOMContentLoaded', function() {
    showRequiredDocuments();
});

function showRequiredDocuments() {
    var dataFields = document.getElementById('dataFields');
    dataFields.innerHTML = `
        <label for="ktp">KTP</label>
        <?php
            if ($data['ktp']) {
        ?>
            <h1>TES</h1>
        <?php } ?>
        <input type="file" id="ktp" name="ktp" class="form-control" required>
        <label for="kk">KK</label>
        <input type="file" id="kk" name="kk" class="form-control" required>
        <label for="rujukan">Rujukan (jika ada)</label>
        <input type="file" id="rujukan" name="rujukan" class="form-control">
        <label for="pas_foto">Pas Foto 3x4</label>
        <input type="file" id="pas_foto" name="pas_foto" class="form-control" required>
        <label for="rekomendasi">Surat rekomendasi dari kelurahan (jika ada)</label>
        <input type="file" id="rekomendasi" name="rekomendasi" class="form-control" >
        <br>
        <div class="d-flex justify-content-center w-100">
            <button onclick="openSpinner()" type="submit" class="btn btn-primary">INPUT</button>
        </div>
    `;
}
