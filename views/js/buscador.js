document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('Buscador');
    const tarjetas = document.querySelectorAll('.tarjeta');
  
    if (input && tarjetas.length > 0) {
        function mostrarTodasTarjetas() {
            tarjetas.forEach(tarjeta => {
                tarjeta.style.display = 'block';
            });
        }

        input.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            if (!query) {
                mostrarTodasTarjetas();
            } else {
                tarjetas.forEach(tarjeta => {
                    const titulo = tarjeta.getAttribute('data-title').toLowerCase();
                    if (titulo.includes(query)) {
                        tarjeta.style.display = 'block';
                    } else {
                        tarjeta.style.display = 'none';
                    }
                });
            }
        });

        mostrarTodasTarjetas();
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('Buscador');
    const tarjetas = document.querySelectorAll('.tarjeta');
  
    if (input) {
        console.log("El buscador está presente.");
    } else {
        console.warn("No se encontró el buscador en esta página.");
    }
    
    if (tarjetas.length > 0) {
        console.log("Se encontraron " + tarjetas.length + " tarjetas.");
    } else {
        console.warn("No se encontraron tarjetas en esta página.");
    }

    if (input && tarjetas.length > 0) {
        // Lógica de filtrado
        // ...
    }
});
