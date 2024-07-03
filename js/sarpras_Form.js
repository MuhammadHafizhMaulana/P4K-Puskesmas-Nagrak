
const nomorHpPattern = /^[0-9]+$/;
const nomorHPSupir = document.getElementById('no_supir');
const nomorHPPendamping = document.getElementById('no_pendamping')
let prevNomorHPSupir = "";
let nomorHPSupirValid = true;
let prevNomorHPPendamping = "";
let nomorHPPendampingValid = true;

function updateForm() {
    var usg = document.getElementById('usg').value;
    var additionalFields = document.getElementById('additionalFields');
    additionalFields.innerHTML = '';

    if (usg === 'pernah') {
        additionalFields.innerHTML = `
            <div id="tanggal-usg-group" class="form-group">
                <label for="tanggal-usg" class="form-label">Tanggal USG Terakhir</label>
                <input oninput="cekValidButton()" type="date" id="tanggal-usg" name="tanggal_usg" class="form-control" required>
            </div>
            <div id="umur-usg-group" class="form-group">
                <label for="umur_usg" class="form-label">Berapakah Usia Kandungan Saat USG Terakhir (Minggu)</label>
                <input oninput="cekValidButton()" type="number" min="0" id="umur_usg" name="umur_usg" class="form-control" required>
            </div>
            <div id="status-usg-group" class="form-group">
                <label for="status_usg" class="form-label">Bagaimana Hasil USG Terakhir Anda</label>
                <select oninput="cekValidButton()" id="status_usg" name="status_usg" class="form-select" aria-label="Default select example" required>
                    <option value="-">Pilih Jawaban Anda</option>
                    <option value="baik">Baik</option>
                    <option value="tidak baik">Tidak Baik</option>
                </select>
            </div>
        `;
    }

    cekValidButton();
}


nomorHPSupir.addEventListener('input', () => {
    if (nomorHPSupir.value === "") {
        prevNomorHPSupir = "";
    } else if (nomorHpPattern.test(nomorHPSupir.value)) {
        prevNomorHPSupir = nomorHPSupir.value;
    } else {
        nomorHPSupir.value = prevNomorHPSupir;
    }

    if (nomorHPSupir.value.length >= 10 && nomorHPSupir.value.startsWith("08")) {
        nomorHPSupirValid = true;
    } else {
        nomorHPSupirValid = false;
    }

    if (document.getElementById('nomorHPSupirAlert')) {
        const nomorHPSupirAlert = document.getElementById('nomorHPSupirAlert')

        if (!nomorHPSupirValid) {
            nomorHPSupirAlert.innerHTML = "Nomor HP harus diawali '08' dan memiliki lebih dari 9 karakter";
        } else {
            nomorHPSupirAlert.innerHTML = '';
        }
    }

    cekValidButton();
})

nomorHPPendamping.addEventListener('input', () => {
    if (nomorHPPendamping.value === "") {
        prevNomorHPPendamping = "";
    } else if (nomorHpPattern.test(nomorHPPendamping.value)) {
        prevNomorHPPendamping = nomorHPPendamping.value;
    } else {
        nomorHPPendamping.value = prevNomorHPPendamping;
    }

    if (nomorHPPendamping.value.length >= 10 && nomorHPPendamping.value.startsWith("08")) {
        nomorHPPendampingValid = true;
    } else {
        nomorHPPendampingValid = false;
    }

    if (document.getElementById('nomorHPPendampingAlert')) {
        const nomorHPPendampingAlert = document.getElementById('nomorHPPendampingAlert')

        if (!nomorHPPendampingValid) {
            nomorHPPendampingAlert.innerHTML = "Nomor HP harus diawali '08' dan memiliki lebih dari 9 karakter";
        } else {
            nomorHPPendampingAlert.innerHTML = '';
        }
    }

    cekValidButton();
})

function cekValidButton(event) {
    if (document.getElementsByTagName('input')) {
        const inputs = [...document.getElementsByTagName('input')];
        const selects = [...document.getElementsByTagName('select')];
        const submitForm = document.getElementById('buttonSubmit');

        submitForm.disabled = false;

        inputs.forEach((fieldValue) => {
            if (!fieldValue.value) {
                submitForm.disabled = true;
            }
        });

        selects.forEach((fieldValue) => {
            if (fieldValue.value == '-') {
                submitForm.disabled = true;
            }
        });

        if (!nomorHPSupirValid || !nomorHPPendampingValid) {
            submitForm.disabled = true;
        }
    }
}