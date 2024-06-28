function updateForm() {
    var jenisPembayaran = document.getElementById('tujuan').value;
    var additionalFields = document.getElementById('additionalFields');
    additionalFields.innerHTML = '';

    if (jenisPembayaran === 'menyudahi') {
        additionalFields.innerHTML = `
            <br>
            <div class="alert alert-primary text-center m-0" role="alert">
                <h6 class="m-0">Untuk menyudahi kehamilan metode yang disarankan adalah metode operasi wanita (MOW). Tindakan ini dapat mencegah kehamilan secara permanen dan hanya bisa dilakukan oleh dokter spesialis.</h6>
            </div>
            <label for="mow">Apakah anda ingin menggunakan metode MOW</label>
            <select id="mow" name="mow" class="form-select" required onchange="showInputButton()">
                <option value="">Pilih Status</option>
                <option value="iya">Ingin</option>
                <option value="tidak">Tidak</option>
            </select>
            <div id="dataFields"></div>
        `;
    } else if (jenisPembayaran === 'menjarakan') {
        additionalFields.innerHTML = `
            <br>
            <label for="penyakit1">Apakah ibu pernah atau sedang mengidap penyakit radang pinggul, atau memiliki keluhan keputihan berbau tidak sedap dan berwarna kuning, hijau atau berdarah?</label>
            <select id="penyakit1" name="penyakit1" class="form-select" required onchange="updatePenyakit1()">
                <option value="">Pilih Status</option>
                <option value="iya">Iya</option>
                <option value="tidak">Tidak</option>
            </select>
            <div id="dataPenyakit1"></div>
        `;
    }
}

function updatePenyakit1() {
    var riwayatPenyakit1 = document.getElementById('penyakit1').value;
    var dataPenyakit1 = document.getElementById('dataPenyakit1');
    dataPenyakit1.innerHTML = '';

    if (riwayatPenyakit1 === 'iya') {
        dataPenyakit1.innerHTML = `
            <br>
            <label for="penyakit2">Apakah anda pernah menderita atau sedang mengidap penyakit berikut:
            <ul>
              <li>Darah Tinggi</li>
              <li>Gangguan Pembekuan Darah (Trombosis Vena Dalam)</li>
              <li>Keganasan (Kanker)</li>
            </ul></label>
            <select id="penyakit2" name="penyakit2" class="form-select" required onchange="updateStatusFields()">
                <option value="">Pilih Status</option>
                <option value="iya">Iya</option>
                <option value="tidak">Tidak</option>
            </select>
            <div id="statusFields"></div>
        `;
    } else if (riwayatPenyakit1 === 'tidak') {
        dataPenyakit1.innerHTML = `
            <br>
            <div class="alert alert-primary text-center m-0" role="alert">
                <h6 class="m-0">Kami merekomendasikan anda untuk menggunakan IUD</h6>
            </div>
            <label for="iud">Apakah anda ingin menggunakan IUD?</label>
            <select id="iud" name="iud" class="form-select" required onchange="showInputButton()">
                <option value="">Menggunakan IUD</option>
                <option value="iya">Iya</option>
                <option value="tidak">Tidak</option>
            </select>
            <div id="dataFields"></div>
        `;
    }
}

function updateStatusFields() {
    var riwayatPenyakit2 = document.getElementById('penyakit2').value;
    var statusFields = document.getElementById('statusFields');
    statusFields.innerHTML = '';

    if (riwayatPenyakit2 === 'iya') {
        statusFields.innerHTML = `
            <br>
            <div class="alert alert-primary text-center m-0" role="alert">
                <h6 class="m-0">Konsultasikan dengan Nakes dengan membuat janji terlebih dahulu setelah menekan tombol input</h6>
            </div>
        `;
    } else if (riwayatPenyakit2 === 'tidak') {
        statusFields.innerHTML = `
            <br>
            <div class="alert alert-primary text-center m-0" role="alert">
                <h6 class="m-0">Kami merekomendasikan untuk menggunakan Kondom + KB Hormonal, diutamakan implan, namun jika tidak bersedia disuntik masih ada pil.</h6>
                <h6 class="m-0">Tekan tombol input untul melanjutkan konsultasi dengan nakes</h6>
            </div>
        `;
    }

    statusFields.innerHTML += `
        <br>
        <div class="d-flex justify-content-center w-100">
            <button id="submitForm" type="button"  data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-danger">INPUT</button>
        </div>
    `
}

function showInputButton() {
    var inputField
    if (document.getElementById('mow')) {
        inputField = document.getElementById('mow')
    } else if (document.getElementById('iud')) {
        inputField = document.getElementById('iud')
    }

    if (inputField.value == 'iya' || inputField.value == "tidak") {
        const boxButton = document.getElementById('dataFields')
        boxButton.innerHTML = `
            <br>
            <div class="d-flex justify-content-center w-100">
                <button id="submitForm" type="button"  data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-danger">INPUT</button>
            </div>
        `
    } else {
        const boxButton = document.getElementById('dataFields')
        boxButton.innerHTML = ``
    }
}


// function addInputButton() {
//     const form = document.getElementById('buttonInputForm');
//     form.innerHTML += `
//         <br>
//         <button id="submitForm" type="button"  data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-danger">INPUT</button>    
//     `
    
// }
