function updateHeightVideoPenjelasan() {
    if (document.getElementById('videoPenjelasan')) {
        var item = document.getElementById('videoPenjelasan');
        
        item.style.height = item.offsetWidth * 0.5625 + 'px';
    }
}

window.addEventListener('resize', function() {
    updateHeightVideoPenjelasan();
});

document.addEventListener('DOMContentLoaded', () => {
    updateHeightVideoPenjelasan();
} );

function triggerUpdateRasioVideo() {
    setTimeout(() => {
        updateHeightVideoPenjelasan();
    }, 250);
}

function closeDialogVideoPenjelasan() {
    if (document.getElementById('videoPenjelasan')) {
        const item = document.getElementById('videoPenjelasan');
        
        item.src = item.src
    }
}