document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const chipId = urlParams.get('txt_nro_residencia');  

    console.log(chipId);
    if (chipId) {
        ver_residencia(chipId).then(data => {
            console.log(data);  

            // Filtrar para encontrar la boleta con la fecha de primer_vencimiento más reciente
            let ultimaBoleta = data.reduce((ultimo, actual) => {
                let fechaUltimo = new Date(ultimo.primer_vencimiento);
                let fechaActual = new Date(actual.primer_vencimiento);
                return fechaActual > fechaUltimo ? actual : ultimo;
            });

            // Asignar valores a los campos correspondientes
            document.querySelector(".cardText:nth-child(1)").innerHTML = `Partida Municipal Nro: ${ultimaBoleta.Nro_partida}`;
            document.querySelector(".cardText:nth-child(2)").innerHTML = `<b>Titular:</b> ${ultimaBoleta.titular || "No disponible"}`;
            document.querySelector(".cardText:nth-child(3)").innerHTML = `<b>Domicilio:</b> ${ultimaBoleta.domicilio_del_bien}`;
            document.querySelector(".cardText:nth-child(4)").innerHTML = `<b>Tipo de vivienda:</b> ${ultimaBoleta.tipo_vivienda || "No disponible"}`;
            document.querySelector(".cardText:nth-child(5)").innerHTML = `<b>Superficie:</b> ${ultimaBoleta.superficie || "No disponible"}`;
            document.querySelector(".cardText:nth-child(6)").innerHTML = `<b>Metros de frente:</b> ${ultimaBoleta.metros_frente || "No disponible"}`;

            // Asignar fechas de vencimiento y precios
            document.querySelector(".divDer .vencimiento").innerHTML = `1er.Vto: ${ultimaBoleta.primer_vencimiento}`;
            document.querySelector(".divDer .precio").innerHTML = `$${ultimaBoleta.primer_pago}`;
            
            document.querySelector(".divDer2 .vencimiento").innerHTML = `2do.Vto: ${ultimaBoleta.segundo_vencimiento}`;
            document.querySelector(".divDer2 .precio").innerHTML = `$${ultimaBoleta.segundo_pago}`;

            // Asignar monto de la tarjeta
            document.querySelector(".contMonto .montoBox").innerHTML = `Mes de Octubre: $${ultimaBoleta.primer_pago}`;

        }).catch(err => {
            document.querySelector(".mainIzq").innerHTML = `<p>Error al cargar los datos.</p>`;
            console.error("Error al cargar la residencia:", err);
        });
    } else {
        document.querySelector(".mainIzq").innerHTML = `<p>No se encontró un chipId en la URL.</p>`;
    }
});
