document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('buttonAlert').click();
    updateCarouselHeight();
});

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

function updateCarouselHeight() {
    var items = document.querySelectorAll('.carousel-item');
    items.forEach(function(item) {
        item.style.height = item.offsetWidth * 0.75 + 'px';
    });
}

// Update photo size when slide carousel
$('#carouselExampleCaptions').on('slid.bs.carousel', function () {
    updateCarouselHeight();
});

// Update height on window resize
window.addEventListener('resize', function() {
    updateCarouselHeight();
});