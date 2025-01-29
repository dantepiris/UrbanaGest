document.addEventListener('DOMContentLoaded', function () {
    // Primer popup
    const openPopup = document.getElementById('openPopup');
    const popup = document.getElementById('popup');
    const closePopup = document.getElementById('closePopup');

    openPopup.addEventListener('click', function () {
        popup.style.display = 'flex';
    });

    closePopup.addEventListener('click', function () {
        popup.style.display = 'none';
    });

    // Cerrar al hacer clic fuera del contenido
    popup.addEventListener('click', function (e) {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });

    // Segundo popup
    const openPopup2 = document.getElementById('openPopup2');
    const popup2 = document.getElementById('popup2');
    const closePopup2 = document.getElementById('closePopup2');

    openPopup2.addEventListener('click', function () {
        popup2.style.display = 'flex';
    });

    closePopup2.addEventListener('click', function () {
        popup2.style.display = 'none';
    });

    // Cerrar al hacer clic fuera del contenido
    popup2.addEventListener('click', function (e) {
        if (e.target === popup2) {
            popup2.style.display = 'none';
        }
    });

    // Tercer popup
    const openPopup3 = document.getElementById('openPopup3');
    const popup3 = document.getElementById('popup3');
    const closePopup3 = document.getElementById('closePopup3');

    openPopup3.addEventListener('click', function () {
        popup3.style.display = 'flex';
    });

    closePopup3.addEventListener('click', function () {
        popup3.style.display = 'none';
    });

    // Cerrar al hacer clic fuera del contenido
    popup3.addEventListener('click', function (e) {
        if (e.target === popup2) {
            popup3.style.display = 'none';
        }
    });
});
