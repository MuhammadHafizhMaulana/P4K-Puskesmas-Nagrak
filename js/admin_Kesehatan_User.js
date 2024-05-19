var btnEditGoldar = document.getElementById('btn-edit-goldar');
var selectGoldar = document.getElementById('goldarGet');
var goldarSend = document.getElementById('goldar');

function editGoldar() {
    selectGoldar.disabled = false;

    btnEditGoldar.innerText = "Simpan";
    btnEditGoldar.setAttribute('onclick', 'showModal()')
}

function showModal() {
    goldarSend.value = selectGoldar.value;

    var myModal = new bootstrap.Modal(document.getElementById('confirmUpdateModal'), {
      keyboard: false
    });
    myModal.show();
}

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('buttonAlert').click();
 });