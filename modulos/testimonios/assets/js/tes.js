(() => {
    const formulario = document.getElementById('form-testimonio');
    const aviso = document.getElementById('mensaje-formulario');

    if (formulario && aviso) {
        const tipoPersona = formulario.elements.namedItem('tipo_persona');
        const campoIngreso = formulario.elements.namedItem('anio_ingreso');
        const campoEgreso = formulario.elements.namedItem('anio_egreso');
        const grupoAnios = document.getElementById('tes-anios');
        const grupoEgreso = document.getElementById('tes-grupo-egreso');
        const etiquetaIngreso = document.getElementById('tes-etiqueta-ingreso');
        const etiquetaEgreso = document.getElementById('tes-etiqueta-egreso');
        const ayudaAnios = document.getElementById('tes-ayuda-anios');

        const actualizarPeriodo = () => {
            const tipo = tipoPersona.value;
            const estudiante = tipo === 'estudiante';
            const egresado = tipo === 'egresado';
            const familiar = tipo === 'familiar';
            const mostrarPeriodo = estudiante || egresado || familiar;

            grupoAnios.hidden = !mostrarPeriodo;
            ayudaAnios.hidden = !mostrarPeriodo;
            grupoEgreso.hidden = !mostrarPeriodo || estudiante;
            campoIngreso.disabled = !mostrarPeriodo;
            campoEgreso.disabled = !mostrarPeriodo || estudiante;
            campoIngreso.required = estudiante || egresado;
            campoEgreso.required = egresado;

            etiquetaIngreso.textContent = familiar
                ? 'Año de ingreso del estudiante (opcional)'
                : 'Año de ingreso *';
            etiquetaEgreso.textContent = familiar
                ? 'Año de egreso del estudiante (opcional)'
                : 'Año de egreso *';
        };
        tipoPersona.addEventListener('change', actualizarPeriodo);
        actualizarPeriodo();

        const validarAnio = (valor) => {
            if (!/^\\d{4}$/.test(valor)) return false;
            const anio = Number(valor);
            return anio >= 1990 && anio <= new Date().getFullYear();
        };

        const reglas = {
            nombre: (valor) => {
                if (!valor) return 'Escribe tu nombre.';
                if ([...valor].length > 100) return 'El nombre admite hasta 100 caracteres.';
                return '';
            },
            tipo_persona: (valor) =>
                ['estudiante', 'egresado', 'familiar'].includes(valor)
                    ? '' : 'Selecciona estudiante, egresado o familiar.',
            carrera_id: (valor) => {
                if (tipoPersona.value !== 'familiar' && !valor) return 'Selecciona tu carrera.';
                return '';
            },
            anio_ingreso: (valor) => {
                if (!tipoPersona.value) return '';
                if (!valor && tipoPersona.value !== 'familiar') return 'Selecciona tu año de ingreso.';
                if (valor && !validarAnio(valor)) return 'Selecciona un año de ingreso válido.';
                return '';
            },
            anio_egreso: (valor) => {
                if (tipoPersona.value === 'estudiante') return '';
                if (!valor && tipoPersona.value === 'egresado') return 'Selecciona tu año de egreso.';
                if (valor && !validarAnio(valor)) return 'Selecciona un año de egreso válido.';
                if (valor && campoIngreso.value && Number(valor) < Number(campoIngreso.value)) {
                    return 'El año de egreso no puede ser anterior al de ingreso.';
                }
                return '';
            },
            testimonio: (valor) => {
                const longitud = [...valor].length;
                return longitud >= 20 && longitud <= 3000
                    ? '' : 'Escribe un testimonio de 20 a 3000 caracteres.';
            },
        };

        formulario.noValidate = true;

        formulario.addEventListener('submit', (evento) => {
            aviso.textContent = '';

            for (const [nombre, validar] of Object.entries(reglas)) {
                const campo = formulario.elements.namedItem(nombre);
                const error = validar(campo.value.trim());

                if (error) {
                    evento.preventDefault();
                    aviso.textContent = error;
                    campo.focus();
                    return;
                }
            }

            if (!formulario.reportValidity()) {
                evento.preventDefault();
            }
        });
    }

    // Actualiza el contador sin cerrar el desplegable ni perder selecciones.
    const panelEtiquetas = document.querySelector('.tes-etiquetas-desplegable');
    if (panelEtiquetas) {
        const casillas = panelEtiquetas.querySelectorAll('input[name="etiquetas[]"]');
        const contador = panelEtiquetas.querySelector('.tes-etiquetas-desplegable__contador');
        const actualizarContador = () => {
            const cantidad = [...casillas].filter((casilla) => casilla.checked).length;
            contador.textContent = cantidad === 0
                ? 'Ninguna seleccionada'
                : cantidad + (cantidad === 1 ? ' seleccionada' : ' seleccionadas');
        };
        casillas.forEach((casilla) => casilla.addEventListener('change', actualizarContador));
        actualizarContador();
    }

    document.querySelectorAll('form.form-eliminar').forEach((formularioEliminar) => {
        formularioEliminar.addEventListener('submit', (evento) => {
            const id = formularioEliminar.elements.namedItem('id').value;

            if (!window.confirm(`¿Eliminar definitivamente el testimonio #${id}?`)) {
                evento.preventDefault();
            }
        });
    });
})();