(() => {
    const tarjetas = [...document.querySelectorAll('.tes-tarjeta')];

    if (!tarjetas.length) return;

    function actualizar() {
        for (const tarjeta of tarjetas) {
            const texto = tarjeta.querySelector('.tes-tarjeta__extracto');
            const enlaceVisual = tarjeta.querySelector('.tes-tarjeta__accion');
            if (!texto || !enlaceVisual) continue;

            // El texto puede estar truncado por el límite de 40 palabras
            // o visualmente por el espacio de cinco líneas disponible.
            const cortado = enlaceVisual.dataset.recortadoPalabras === '1'
                || texto.scrollHeight > texto.clientHeight + 1;
            enlaceVisual.hidden = !cortado;
        }
    }

    actualizar();
    if (document.fonts?.ready) {
        document.fonts.ready.then(actualizar);
    }
    if (typeof ResizeObserver !== 'undefined') {
        const observador = new ResizeObserver(actualizar);
        tarjetas.forEach(tarjeta => observador.observe(tarjeta));
    } else {
        window.addEventListener('resize', actualizar, { passive: true });
    }
})();
