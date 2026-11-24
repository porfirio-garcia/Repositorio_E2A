(() => {
    const tarjetas = document.querySelectorAll('.tes-tarjeta');

    if (!tarjetas.length) return;

    function actualizarAcciones() {
        tarjetas.forEach((tarjeta) => {
            const texto = tarjeta.querySelector('.tes-tarjeta__extracto');
            const accion = tarjeta.querySelector('.tes-tarjeta__accion-texto');

            if (!texto || !accion) return;

            // scrollHeight incluye las líneas que el CSS ha recortado.
            // clientHeight refleja el espacio que realmente está visible.
            const recortado = texto.dataset.recortadoPalabras === '1'
                || texto.scrollHeight > texto.clientHeight + 1;

            accion.textContent = recortado ? 'Leer más' : 'Ver testimonio';
        });
    }

    actualizarAcciones();

    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(actualizarAcciones);
    }

    if (typeof ResizeObserver !== 'undefined') {
        const observador = new ResizeObserver(actualizarAcciones);
        tarjetas.forEach((tarjeta) => observador.observe(tarjeta));
    } else {
        window.addEventListener('resize', actualizarAcciones, { passive: true });
    }
})();
