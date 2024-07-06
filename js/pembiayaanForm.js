const ktpField = document.getElementById('ktp')
const kkField = document.getElementById('kk')
const pasFotoField = document.getElementById('pas_foto')
const nomorBPJSPattern = /^[0-9]+$/;
let prevNomorBPJS = '';


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


if (document.getElementById('buttonFormSelanjutnya')) {
    var buttonSelanjutnya = document.getElementById('buttonFormSelanjutnya');

    buttonSelanjutnya.addEventListener('click', () => {
        const formDetailPembiayaan = document.getElementById('formDetailPembiayaan');
    
        formDetailPembiayaan.innerHTML = `
        <div class="form-group">
            <label for="saldoTabungan">Masukkan saldo tabungan persalinan yang sudah dimiliki</label>
            <div class="d-flex align-items-center">
                Rp.
                <input oninput="cekKelengkapanField()" type="number" min="0" class="form-control registrasi-form fieldSelainFoto" id="saldoTabungan" name="saldoTabungan" placeholder="saldo tabungan" min="0" required>
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_pembayaran">Jenis pembiayaan yang digunakan</label>
            <select onchange="cekJenisPembiayaan()" id="jenis_pembayaran" name="jenis_pembayaran" class="form-select fieldSelainFoto" aria-label="Default select example" required>
                <option value="-">Pilih jenis pembiayaan</option>
                <option value="BPJS Aktif">BPJS Aktif</option>
                <option value="BPJS Tidak Aktif (Tidak Punya)">BPJS Tidak Aktif (Tidak Punya)</option>
                <option value="Saldo Tabungan">Saldo Tabungan</option>
            </select>
        </div>

        <div id="formNomorBPJS">
        </div>
        
        <br>
        <div class="alert alert-primary text-center m-0" role="alert">
            <h6 class="m-0">Tekan tombol input untuk melanjutkan konsultasi dengan nakes</h6>
        </div>

        <div id="dataFields">
            <br>
            <div class="d-flex justify-content-center w-100">
                <button data-bs-toggle="modal" data-bs-target="#modalKonsultasi" id="buttonSubmit" type="button" disabled class="btn btn-danger">Input</button>
            </div>
        </div>
        `
    })
}

document.addEventListener('DOMContentLoaded', () => {
    updateBoxPhotoHeight();
} );

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

ktpField.addEventListener('input',() => {
   this.checkRequiredFile();
})
kkField.addEventListener('input',() => {
   this.checkRequiredFile();
})
pasFotoField.addEventListener('input',() => {
   this.checkRequiredFile();
})


function checkRequiredFile(event) {
    const fileInputs = [...document.getElementsByClassName('required-field')];
    const nextButton = document.getElementById('buttonFormSelanjutnya');

    fileInputs.forEach(input => {
        input.addEventListener('change', checkFiles);
    });

    function checkFiles() {
        let allFilled = true;
        fileInputs.forEach(input => {
            if (input.hasAttribute('required') && !input.files.length) {
                allFilled = false;
            }
        });
        nextButton.disabled = !allFilled;
    }
}

function updateBoxPhotoHeight() {
    if (document.querySelectorAll('.boxPhoto')) {
        var items = document.querySelectorAll('.boxPhoto');
        items.forEach(function(item) {
            item.style.height = item.offsetWidth * 0.75 + 'px';
        });
    }
}

// Update height on window resize
window.addEventListener('resize', function() {
    updateBoxPhotoHeight();
});

function cekJenisPembiayaan() {
    if (document.getElementById('formNomorBPJS')) {
        const nomorBPJS = document.getElementById('formNomorBPJS');
        const jenisPembiayaan = document.getElementById('jenis_pembayaran');
    
        if (jenisPembiayaan.value == 'BPJS Aktif') {
            nomorBPJS.innerHTML = `
                <div class="form-group">
                    <label for="nomorBPJS">Masukan nomor BPJS</label>
                    <input oninput="cekFormatNomorBPJS()" type="text" min="0" class="form-control registrasi-form fieldSelainFoto" id="nomorBPJS" name="nomorBPJS" placeholder="nomor BPJS" min="0" required>
                </div>
            `;
        } else {
            nomorBPJS.innerHTML = ``;
            prevNomorBPJS = '';
        }
    }

    cekKelengkapanField();
}

function cekKelengkapanField(event) {
    if (document.getElementsByClassName('fieldSelainFoto')) {
        const fieldSelainFoto = [...document.getElementsByClassName('fieldSelainFoto')];
        const submitForm = document.getElementById('buttonSubmit');

        submitForm.disabled = false;

        fieldSelainFoto.forEach((fieldValue, index) => {
            if (index == 1) {
                if (fieldValue.value == '-') {
                    submitForm.disabled = true;    
                }    
            } else if (!fieldValue.value) {
                submitForm.disabled = true;
            }
        });
    }
}


function cekFormatNomorBPJS() {
    if (document.getElementById('nomorBPJS')) {
        const nomorBPJS = document.getElementById('nomorBPJS');

        if (nomorBPJS.value === "") {
            prevNomorBPJS = "";
        } else if (nomorBPJSPattern.test(nomorBPJS.value)) {
            prevNomorBPJS = nomorBPJS.value;
        } else {
            nomorBPJS.value = prevNomorBPJS;
        }
    
    }

    cekKelengkapanField();
}


// Dialog konsultasi


if (document.getElementById('waktu_konsultasi')) {
    var waktuKonsultasi = document.getElementById('waktu_konsultasi');
    var buttonSubmitDialog = document.getElementById('submitJadwal');
    
    
    // Aktifkan atau nonaktifkan tombol submit berdasarkan input di field waktu
    waktuKonsultasi.addEventListener('input', function () {
        buttonSubmitDialog.disabled = !waktuKonsultasi.value;
    });
    
    // Event listener untuk dialog submit
    buttonSubmitDialog.addEventListener("click", function (event) {
        // Ambil nilai-nilai dari field form
        var nama = document.getElementById('nama').value;
        var nomorHP = document.getElementById('nomorHP').value;
        var alamat = document.getElementById('alamat').value;
        const saldoTabungan = document.getElementById('saldoTabungan').value;
        const jenisPembiayaan = document.getElementById('jenis_pembayaran').value;
    
        // Buat URL WhatsApp
        var urlToWhatsapp = `https://wa.me/6285720848420?text=Halo, perkenalkan saya *${nama}* dengan nomor handphone *${nomorHP}* yang beralamatkan di *${alamat}* ingin melakukan konsultasi KB pada *${waktuKonsultasi.value}*. Saat ini, saldo tabungan persalinan yang sudah saya miliki adalah Rp ${saldoTabungan} dan juga jenis pembiayaan yang akan saya gunakan yaitu  ${jenisPembiayaan}`;
    
        // Buka URL di tab baru
        window.open(urlToWhatsapp, "_blank");
    });
}
