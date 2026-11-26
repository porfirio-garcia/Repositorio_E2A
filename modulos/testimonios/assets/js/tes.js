(() => {
    const formulario = document.getElementById('form-testimonio');
    const aviso = document.getElementById('mensaje-formulario');

    if (formulario && aviso) {
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
                const tipo = formulario.elements.namedItem('tipo_persona').value;
                return tipo !== 'familiar' && !valor ? 'Selecciona tu carrera.' : '';
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

    document.querySelectorAll('[data-limite-etiquetas]').forEach((grupo) => {
        const limite = Number(grupo.dataset.limiteEtiquetas);
        const casillas = [...grupo.querySelectorAll('input[name="etiquetas[]"]')];
        const contador = grupo.querySelector('[data-contador-etiquetas]');
        const actualizarSeleccion = () => {
            const cantidad = casillas.filter((casilla) => casilla.checked).length;
            casillas.forEach((casilla) => {
                casilla.disabled = !casilla.checked && cantidad >= limite;
            });
            const avisoLimite = cantidad > limite ? '. Desmarca las que sobran antes de guardar.' : '';
            contador.textContent = `${cantidad} de ${limite} seleccionadas${avisoLimite}`;
        };
        casillas.forEach((casilla) => casilla.addEventListener('change', actualizarSeleccion));
        actualizarSeleccion();
    });

    document.querySelectorAll('form.form-eliminar').forEach((formularioEliminar) => {
        formularioEliminar.addEventListener('submit', (evento) => {
            const id = formularioEliminar.elements.namedItem('id').value;

            if (!window.confirm(`¿Eliminar definitivamente el testimonio #${id}?`)) {
                evento.preventDefault();
            }
        });
    });
})();
