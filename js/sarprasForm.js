function updateForm() {
    var usg = document.getElementById('usg').value;
    var additionalFields = document.getElementById('additionalFields');
    additionalFields.innerHTML = '';

    if (usg === 'pernah') {
        additionalFields.innerHTML = `
            <div id="tanggal-usg-group" class="form-group">
                <label for="tanggal-usg" class="form-label">Tanggal USG Terakhir</label>
                <input type="date" id="tanggal-usg" name="tanggal_usg" class="form-control" required>
            </div>
            <div id="umur-usg-group" class="form-group">
                <label for="umur_usg" class="form-label">Berapakah Usia Kandungan Saat USG Terakhir (Minggu)</label>
                <input type="number" id="umur_usg" name="umur_usg" class="form-control" required>
            </div>
            <div id="status-usg-group" class="form-group">
                <label for="status_usg" class="form-label">Bagaimana Hasil USG Terakhir Anda</label>
                <select id="status_usg" name="status_usg" class="form-select" aria-label="Default select example" required>
                    <option value="">Pilih Jawaban Anda</option>
                    <option value="usg baik">Baik</option>
                    <option value="usg tidak baik">Tidak Baik</option>
                </select>
            </div>
        `;
    }
}