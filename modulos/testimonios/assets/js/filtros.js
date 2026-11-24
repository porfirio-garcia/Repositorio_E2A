(() => {
    const formulario = document.getElementById('tes-filtros');
    const resultados = document.getElementById('tes-resultados');
    const contador = formulario?.querySelector('.tes-filtro-etiquetas__contador');
    const limpiar = document.getElementById('tes-limpiar-etiquetas');
    const desplegable = document.getElementById('tes-filtro-desplegable');
    if (!formulario || !resultados) return;

    // El selector es un menú flotante: se cierra al hacer clic fuera
    // o al pulsar Escape, sin borrar las etiquetas seleccionadas.
    document.addEventListener('pointerdown', (evento) => {
        if (desplegable?.open && !desplegable.contains(evento.target)) {
            desplegable.open = false;
        }
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && desplegable?.open) {
            desplegable.open = false;
            desplegable.querySelector('summary')?.focus();
        }
    });

    let solicitud = null;
    let version = 0;

    const etiquetas = () =>
        [...formulario.querySelectorAll('input[name="etiquetas[]"]:checked')];

    function actualizarIndicadores() {
        const cantidad = etiquetas().length;
        if (contador) {
            contador.textContent = cantidad
                ? cantidad + (cantidad === 1 ? ' seleccionada' : ' seleccionadas')
                : 'Ninguna seleccionada';
        }
        if (limpiar) limpiar.hidden = cantidad === 0;
    }

    async function cargar(url, guardarHistorial = true) {
        const direccion = new URL(url, window.location.href);
        direccion.searchParams.delete('tes_fragmento');
        if (solicitud) solicitud.abort();
        solicitud = new AbortController();
        const actual = ++version;
        resultados.setAttribute('aria-busy', 'true');
        try {
            const solicitudUrl = new URL(direccion);
            solicitudUrl.searchParams.set('tes_fragmento', '1');
            const respuesta = await fetch(solicitudUrl, {
                signal: solicitud.signal,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!respuesta.ok) throw new Error('No se pudo actualizar el listado.');
            const html = await respuesta.text();
            if (actual !== version) return;

            resultados.innerHTML = html;
            if (guardarHistorial) history.pushState(null, '', direccion);
            document.dispatchEvent(new Event('tes:listado-actualizado'));
        } catch (error) {
            if (error.name === 'AbortError') return;
            // Si falla la actualización parcial, la navegación clásica sigue funcionando.
            window.location.assign(direccion.href);
        } finally {
            if (actual === version) resultados.setAttribute('aria-busy', 'false');
        }
    }

    function urlFiltros(pagina = 1) {
        const url = new URL(formulario.action, window.location.href);
        const datos = new FormData(formulario);
        for (const [clave, valor] of datos) {
            if (valor !== '') url.searchParams.append(clave, valor);
        }
        if (pagina > 1) url.searchParams.set('pagina', String(pagina));
        return url;
    }

    formulario.addEventListener('change', (evento) => {
        if (!evento.target.matches('select, input[name="etiquetas[]"]')) return;
        actualizarIndicadores();
        cargar(urlFiltros());
    });

    formulario.addEventListener('submit', (evento) => {
        evento.preventDefault();
        cargar(urlFiltros());
    });

    limpiar?.addEventListener('click', () => {
        formulario.querySelectorAll('input[name="etiquetas[]"]').forEach(c => { c.checked = false; });
        actualizarIndicadores();
        cargar(urlFiltros());
    });

    resultados.addEventListener('click', (evento) => {
        const enlace = evento.target.closest('.tes-paginacion a[href]');
        if (!enlace) return;
        evento.preventDefault();
        cargar(enlace.href);
    });

    function restaurarFiltros() {
        const params = new URLSearchParams(location.search);
        for (const nombre of ['carrera', 'tipo']) {
            const campo = formulario.elements.namedItem(nombre);
            if (campo) campo.value = params.get(nombre) ?? '';
        }
        const elegidas = params.getAll('etiquetas[]');
        const antigua = params.get('etiqueta');
        if (antigua) elegidas.push(antigua);
        formulario.querySelectorAll('input[name="etiquetas[]"]').forEach(c => {
            c.checked = elegidas.includes(c.value);
        });
        actualizarIndicadores();
    }

    window.addEventListener('popstate', () => {
        restaurarFiltros();
        cargar(new URL(location.href), false);
    });

    actualizarIndicadores();
})();
