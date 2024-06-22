function updateForm() {
    var jenisPembayaran = document.getElementById('tujuan').value;
    var additionalFields = document.getElementById('additionalFields');
    additionalFields.innerHTML = '';

    if (jenisPembayaran === 'menyudahi') {
        additionalFields.innerHTML = `
            <br>
            <p>Untuk menyudahi kehamilan metode yang disarankan adalah metode operasi wanita (MOW). Tindakan ini dapat mencegah kehamilan secara permanen dan hanya bisa dilakukan oleh dokter spesialis.</p>
            <label for="mow">Apakah anda ingin menggunakan metode MOW</label>
            <select id="mow" name="mow" class="form-select" required onchange="showRequiredDocuments()">
                <option value="">Pilih Status</option>
                <option value="iya">Ingin</option>
                <option value="tidak">Tidak</option>
            </select>
            <div id="dataFields"></div>
        `;
    } else if (jenisPembayaran === 'menjarakan') {
        additionalFields.innerHTML = `
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
            <label for="penyakit2">Apakah anda pernah menderita atau sedang mengidap penyakit berikut:<br>
            1. Darah Tinggi<br>
            2. Gangguan Pembekuan Darah (Trombosis Vena Dalam)<br>
            3. Keganasan (Kanker)</label>
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
            <p>Kami merekomendasikan anda untuk menggunakan IUD</p>
            <label for="iud">Apakah anda ingin menggunakan IUD?</label>
            <select id="iud" name="iud" class="form-select" required onchange="showRequiredDocuments()">
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
            <p>Konsultasikan dengan Nakes dengan membuat janji terlebih dahulu setelah menekan tombol input</p>
        `;
    } else if (riwayatPenyakit2 === 'tidak') {
        statusFields.innerHTML = `
            <p>Kami merekomendasikan untuk menggunakan Kondom + KB Hormonal, diutamakan implan, namun jika tidak bersedia disuntik masih ada pil<br> Tekan tombol input untul melanjutkan konsultasi dengan nakes</p>
        `;
    }
}
