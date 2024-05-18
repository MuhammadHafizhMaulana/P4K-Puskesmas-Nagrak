var btnEditGoldar = document.getElementById('btn-edit-goldar');
var selectGoldar = document.getElementById('goldar');

function editGoldar() {
    selectGoldar.disabled = false;

    btnEditGoldar.innerText = "Simpan";
    btnEditGoldar.setAttribute('onclick', 'showModal()')
}

function showModal() {
    var myModal = new bootstrap.Modal(document.getElementById('confirmUpdateModal'), {
      keyboard: false
    });
    myModal.show();
}

function confirmUpdate(id) {
    var goldar = selectGoldar.value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "proses/edit_goldar_pendonor_proses.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Response berhasil, Anda bisa melakukan sesuatu di sini jika perlu
                console.log("Update berhasil");
            } else {
                // Terjadi kesalahan saat mengirim permintaan
                console.error("Terjadi kesalahan: " + xhr.status);
            }
        }
    };
    xhr.send("id=" + id + "&goldar=" + goldar);
}
