(() => {
    let observador = null;

    function actualizar() {
        document.querySelectorAll('.tes-tarjeta').forEach(tarjeta => {
            const texto = tarjeta.querySelector('.tes-tarjeta__extracto');
            const enlaceVisual = tarjeta.querySelector('.tes-tarjeta__accion');
            if (!texto || !enlaceVisual) return;
            const cortado = enlaceVisual.dataset.recortadoPalabras === '1'
                || texto.scrollHeight > texto.clientHeight + 1;
            enlaceVisual.hidden = !cortado;
        });
    }

    function observar() {
        observador?.disconnect();
        actualizar();
        if (typeof ResizeObserver !== 'undefined') {
            observador = new ResizeObserver(actualizar);
            document.querySelectorAll('.tes-tarjeta').forEach(tarjeta => observador.observe(tarjeta));
        }
    }

    observar();
    document.fonts?.ready?.then(actualizar);
    document.addEventListener('tes:listado-actualizado', observar);
    window.addEventListener('resize', actualizar, { passive: true });
})();
