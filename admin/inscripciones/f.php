<!-- Carga dinámica de secciones por nivel -->

<!-- Carga dinámica de secciones por nivel y validación de cupos UNIFICADO -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const nivelSelect = document.getElementById('id_nivel');
    const seccionSelect = document.getElementById('id_nivel_seccion');
    const periodoSelect = document.getElementById('id_periodo');
    const infoCupos = document.getElementById('info-cupos');
    const submitBtn = document.querySelector('button[type="submit"]');

    let mensajeCupos = null;

    // ========== CARGAR SECCIONES POR NIVEL ==========
    function cargarSeccionesPorNivel(idNivel) {
      if (!idNivel) {
        seccionSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
        seccionSelect.disabled = true;
        infoCupos.textContent = 'Las secciones se cargarán según el nivel seleccionado';
        eliminarMensajeCupos();
        return;
      }

      // Mostrar loading
      seccionSelect.innerHTML = '<option value="">Cargando secciones...</option>';
      seccionSelect.disabled = true;
      infoCupos.textContent = 'Cargando secciones disponibles...';
      eliminarMensajeCupos();

      const formData = new FormData();
      formData.append('id_nivel', idNivel);

      fetch('/final/app/controllers/niveles/obtener_secciones.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            seccionSelect.innerHTML = '<option value="">Seleccionar Sección</option>';

            if (data.secciones && data.secciones.length > 0) {
              data.secciones.forEach(seccion => {
                const option = document.createElement('option');
                option.value = seccion.id_nivel_seccion;
                option.textContent = `${seccion.nom_seccion} (${seccion.cupos_disponibles} cupos disponibles de ${seccion.capacidad})`;
                option.dataset.cupos = seccion.cupos_disponibles;
                option.dataset.capacidad = seccion.capacidad;
                option.dataset.inscritos = seccion.inscritos;
                seccionSelect.appendChild(option);
              });

              seccionSelect.disabled = false;
              infoCupos.innerHTML = `<span class="text-success">✅ ${data.secciones.length} sección(es) disponible(s)</span>`;

              // Verificar cupos si ya hay periodo seleccionado
              if (periodoSelect.value) {
                setTimeout(verificarCupos, 100);
              }
            } else {
              seccionSelect.innerHTML = '<option value="">No hay secciones disponibles para este nivel</option>';
              seccionSelect.disabled = true;
              infoCupos.innerHTML = '<span class="text-danger">❌ No hay secciones disponibles con cupos para este nivel</span>';
              eliminarMensajeCupos();
            }
          } else {
            seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
            seccionSelect.disabled = true;
            infoCupos.textContent = 'Error: ' + data.message;
            eliminarMensajeCupos();
          }
        })
        .catch(error => {
          console.error('Error al cargar secciones:', error);
          seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
          seccionSelect.disabled = true;
          infoCupos.textContent = 'Error de conexión al cargar secciones';
          eliminarMensajeCupos();
        });
    }

    // ========== VALIDACIÓN DE CUPOS ==========
    function verificarCupos() {
      const id_nivel_seccion = seccionSelect.value;
      const id_periodo = periodoSelect.value;

      if (!id_nivel_seccion || !id_periodo) {
        eliminarMensajeCupos();
        return;
      }

      const formData = new FormData();
      formData.append('id_nivel_seccion', id_nivel_seccion);
      formData.append('id_periodo', id_periodo);

      fetch('/final/app/controllers/cupos/verificar_cupos.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          mostrarMensajeCupos(data);
        })
        .catch(error => {
          console.error('Error al verificar cupos:', error);
        });
    }

    function mostrarMensajeCupos(data) {
      eliminarMensajeCupos();

      const informacionAcademica = document.querySelector('.informacion_academica .card-body');
      if (!informacionAcademica) return;

      mensajeCupos = document.createElement('div');
      mensajeCupos.className = `alert ${data.disponible ? 'alert-success' : 'alert-danger'} mt-3`;
      mensajeCupos.innerHTML = `
            <strong>${data.disponible ? '✅ CUPOS DISPONIBLES' : '❌ SIN CUPOS'}</strong><br>
            ${data.mensaje}
            ${data.disponible ? 
                `<br><small class="text-muted">Puede continuar con la inscripción</small>` : 
                `<br><small class="text-muted">No se puede realizar la inscripción en esta sección</small>`
            }
        `;

      informacionAcademica.appendChild(mensajeCupos);

      // Deshabilitar/enable el botón de enviar
      if (submitBtn) {
        submitBtn.disabled = !data.disponible;
      }
    }

    function eliminarMensajeCupos() {
      if (mensajeCupos) {
        mensajeCupos.remove();
        mensajeCupos = null;
      }
      if (submitBtn) {
        submitBtn.disabled = false;
      }
    }

    // ========== EVENT LISTENERS ==========
    nivelSelect.addEventListener('change', function() {
      cargarSeccionesPorNivel(this.value);
    });

    seccionSelect.addEventListener('change', verificarCupos);
    periodoSelect.addEventListener('change', verificarCupos);

    // Cargar secciones automáticamente si ya hay un nivel seleccionado
    if (nivelSelect.value) {
      cargarSeccionesPorNivel(nivelSelect.value);
    }
  });
</script>

<!--- Validación sobre si el estudiante vive en la misma casa ---->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const selectMismaCasa = document.getElementById('misma_casa');
    const seccionDireccion = document.getElementById('direccion_representante');

    selectMismaCasa.addEventListener('change', function() {
      if (this.value === 'no') {
        document.getElementById('juntos').value = '0';
        seccionDireccion.style.display = 'block';
        document.getElementById('estado_e').required = true;
        document.getElementById('direccion_e').required = true;
      } else {
        document.getElementById('juntos').value = '1';
        seccionDireccion.style.display = 'none';
        document.getElementById('estado_e').required = false;
        document.getElementById('direccion_e').required = false;

        // Limpiar los campos cuando se ocultan
        document.getElementById('estado_e').value = '';
        document.getElementById('municipio_e').value = '';
        document.getElementById('parroquia_e').value = '';
        document.getElementById('direccion_e').value = '';
        document.getElementById('calle_e').value = '';
        document.getElementById('casa_e').value = '';
      }
    });
  });
</script>

<!-- Resto del código JavaScript existente (sin duplicados) -->
<!-- Mantén todo el resto del código JavaScript que ya tienes, pero ELIMINA las secciones duplicadas -->
<!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    const nivelSelect = document.getElementById('id_nivel');
    const seccionSelect = document.getElementById('id_nivel_seccion');
    const infoCupos = document.getElementById('info-cupos');

    // Función para cargar secciones según el nivel seleccionado
    function cargarSeccionesPorNivel(idNivel) {
      if (!idNivel) {
        seccionSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
        seccionSelect.disabled = true;
        infoCupos.textContent = 'Las secciones se cargarán según el nivel seleccionado';
        return;
      }

      // Mostrar loading
      seccionSelect.innerHTML = '<option value="">Cargando secciones...</option>';
      seccionSelect.disabled = true;
      infoCupos.textContent = 'Cargando secciones disponibles...';

      const formData = new FormData();
      formData.append('id_nivel', idNivel);

      fetch('/final/app/controllers/niveles/obtener_secciones.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            seccionSelect.innerHTML = '<option value="">Seleccionar Sección</option>';

            if (data.secciones && data.secciones.length > 0) {
              data.secciones.forEach(seccion => {
                const option = document.createElement('option');
                option.value = seccion.id_nivel_seccion;
                option.textContent = `${seccion.nom_seccion} (${seccion.cupos_disponibles} cupos disponibles de ${seccion.capacidad})`;
                option.dataset.cupos = seccion.cupos_disponibles;
                option.dataset.capacidad = seccion.capacidad;
                option.dataset.inscritos = seccion.inscritos;
                seccionSelect.appendChild(option);
              });

              seccionSelect.disabled = false;
              infoCupos.innerHTML = `<span class="text-success">✅ ${data.secciones.length} sección(es) disponible(s)</span>`;
            } else {
              seccionSelect.innerHTML = '<option value="">No hay secciones disponibles para este nivel</option>';
              seccionSelect.disabled = true;
              infoCupos.innerHTML = '<span class="text-danger">❌ No hay secciones disponibles con cupos para este nivel</span>';
            }
          } else {
            seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
            seccionSelect.disabled = true;
            infoCupos.textContent = 'Error: ' + data.message;
          }
        })
        .catch(error => {
          console.error('Error al cargar secciones:', error);
          seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
          seccionSelect.disabled = true;
          infoCupos.textContent = 'Error de conexión al cargar secciones';
        });
    }

    // Event listener para cambios en el select de nivel
    nivelSelect.addEventListener('change', function() {
      const idNivel = this.value;
      cargarSeccionesPorNivel(idNivel);

      // También verificar cupos si ya hay un periodo seleccionado
      const periodoSelect = document.querySelector('select[name="id_periodo"]');
      if (periodoSelect && periodoSelect.value) {
        setTimeout(verificarCupos, 100);
      }
    });

    // Cargar secciones automáticamente si ya hay un nivel seleccionado
    if (nivelSelect.value) {
      cargarSeccionesPorNivel(nivelSelect.value);
    }

    // Modificar la función de verificación de cupos para usar id_nivel_seccion
    function verificarCupos() {
      const id_nivel_seccion = seccionSelect.value;
      const id_periodo = document.querySelector('select[name="id_periodo"]').value;

      if (!id_nivel_seccion || !id_periodo) {
        eliminarMensajeCupos();
        return;
      }

      const formData = new FormData();
      formData.append('id_nivel_seccion', id_nivel_seccion);
      formData.append('id_periodo', id_periodo);

      fetch('/final/app/controllers/cupos/verificar_cupos.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          mostrarMensajeCupos(data);
        })
        .catch(error => {
          console.error('Error al verificar cupos:', error);
        });
    }

    // Actualizar event listeners para usar el nuevo select
    seccionSelect.addEventListener('change', verificarCupos);
    document.querySelector('select[name="id_periodo"]').addEventListener('change', verificarCupos);
  });
</script> -->

<!--- Aca hacemos la validacion sobre si el estudiante vive en la misma casa ---->
<!-- - Aca hacemos la validacion sobre si el estudiante vive en la misma casa -- -->
<!--- Aca hacemos la validacion sobre si el estudiante vive en la misma casa ---->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const selectMismaCasa = document.getElementById('misma_casa');
    const seccionDireccion = document.getElementById('direccion_representante');

    selectMismaCasa.addEventListener('change', function() {
      if (this.value === 'no') {
        document.getElementById('juntos').value = '0';

        // Mostrar la sección de dirección
        seccionDireccion.style.display = 'block';

        // Hacer los campos requeridos
        document.getElementById('estado_e').required = true;
        document.getElementById('direccion_e').required = true;
      } else {
        document.getElementById('juntos').value = '1';
        // Ocultar la sección de dirección
        seccionDireccion.style.display = 'none';

        // Quitar el atributo required y limpiar los campos
        document.getElementById('estado_e').required = false;
        document.getElementById('direccion_e').required = false;

        // Opcional: Limpiar los campos cuando se ocultan
        document.getElementById('estado_e').value = '';
        document.getElementById('municipio_e').value = '';
        document.getElementById('parroquia_e').value = '';
        document.getElementById('direccion_e').value = '';
        document.getElementById('calle_e').value = '';
        document.getElementById('casa_e').value = '';
      }
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Agregar asterisco a todos los labels de campos required
    document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
      const label = document.querySelector(`label[for="${field.id}"]`);
      if (label && !label.querySelector('.required-asterisk')) {
        label.innerHTML += ' <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span>';
      }
    });
  });
</script>

<!-- Validadndo edad para creacion de cedula escolar -->
<!-- Validadndo edad para creacion de cedula escolar -->
<!-- Validadndo edad para creacion de cedula escolar -->
<!-- Validadndo edad para creacion de cedula escolar -->

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha_nac_e');
    const cedulaEInput = document.getElementById('cedula_e');
    const cedulaRInput = document.getElementById('cedula_r');
    const id_representante_esc = document.getElementById('id_representante_existente');
    const tipo = document.getElementById('tipo_persona');
    const selectCi = document.getElementById('ci_si');

    const hoy = new Date();
    const añoActual = hoy.getFullYear();
    let añoMinimo = añoActual - 19;
    let añoMaximo = añoActual - 5;
    async function obtenerEdadesGlobales() {
      try {
        console.log('📊 Solicitando edades globales desde la base de datos...');

        const response = await fetch('/final/app/controllers/globales/obtenerEdades.php', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          }
        });

        const responseText = await response.text();
        console.log('📨 Respuesta del servidor (edades):', responseText);

        let data;
        try {
          data = JSON.parse(responseText);
        } catch (parseError) {
          console.error('❌ Error al parsear JSON:', parseError.message);
          // Usar valores por defecto en caso de error
          return {
            success: false
          };
        }

        if (data.success) {
          console.log('✅ Edades obtenidas:', {
            edad_min: data.edad_min,
            edad_max: data.edad_max
          });
          return data;
        } else {
          console.error('❌ Error al obtener edades:', data.error);
          return {
            success: false
          };
        }

      } catch (error) {
        console.error('❌ Error en obtenerEdadesGlobales:', error);
        return {
          success: false
        };
      }
    }

    // Función para inicializar los límites de fecha
    // Función para inicializar los límites de fecha
    // Función para inicializar los límites de fecha
    async function inicializarFechas() {
      const edades = await obtenerEdadesGlobales();

      if (edades.success) {
        // ✅ CORRECCIÓN: Invertir el cálculo
        añoMinimo = añoActual - edades.edad_max; // Para edad MÁXIMA
        añoMaximo = añoActual - edades.edad_min; // Para edad MÍNIMA

        console.log('🎯 Límites calculados:', {
          añoMinimo: añoMinimo,
          añoMaximo: añoMaximo,
          edad_min: edades.edad_min,
          edad_max: edades.edad_max,
          explicación: `Estudiantes entre ${edades.edad_min} y ${edades.edad_max} años`
        });
      } else {
        console.warn('⚠️ Usando valores por defecto para las edades');
        // También corregir los valores por defecto
        añoMinimo = añoActual - 19; // edad máxima por defecto
        añoMaximo = añoActual - 5; // edad mínima por defecto
      }

      // Establecer los límites en el input de fecha
      fechaInput.min = `${añoMinimo}-01-01`;
      fechaInput.max = `${añoMaximo}-12-31`;

      console.log('📅 Límites de fecha establecidos:', {
        min: fechaInput.min,
        max: fechaInput.max,
        rango_edades: `Nacidos entre ${añoMinimo} y ${añoMaximo}`
      });
    }

    // Inicializar los límites de fecha al cargar la página
    inicializarFechas();


    // Función para validar y generar cédula
    async function validarRegistro() {
      console.log('📅 Evento de cambio de fecha detectado');

      const fecha = fechaInput.value;
      const idR = id_representante_esc.value;
      const tp = tipo.value;

      // Obtener el valor ACTUAL de la cédula
      const cedulaRActual = cedulaRInput.value;
      console.log('Datos obtenidos:', {
        fecha: fecha,
        cedulaRActual: cedulaRActual,
        idR: idR,
        tp: tp
      });

      // Verificar que tenemos todos los datos necesarios
      if (!fecha) {
        console.log('❌ No hay fecha seleccionada');
        return;
      }

      if (!cedulaRActual) {
        console.log('❌ No hay cédula de representante');
        return;
      }

      const anioNacimiento = fecha.substring(2, 4);
      console.log('🔢 Año de nacimiento extraído:', anioNacimiento);

      if (tp === 'representante') {
        console.log('👨‍👦 Tipo: representante - generando cédula escolar');
        try {
          cedulaRInput.disabled = true;
          const numeroDEstudiantes = await validarYGenerarCedula(idR, anioNacimiento, cedulaRActual);
          if (numeroDEstudiantes) {
            cedulaEInput.value = numeroDEstudiantes;
            // ✅ Hacer el campo de solo lectura
            cedulaEInput.readOnly = true;
            cedulaEInput.style.backgroundColor = '#f8f9fa';
            cedulaEInput.style.cursor = 'not-allowed';
            console.log('✅ Cédula escolar generada:', numeroDEstudiantes);
          }
        } catch (error) {
          console.error('❌ Error:', error);
        }
      } else {
        console.log('👤 Tipo: otro - generando cédula simple');
        const c_esc = anioNacimiento + '1' + cedulaRActual;
        cedulaEInput.value = c_esc;
        // ✅ Hacer el campo de solo lectura
        cedulaEInput.readOnly = true;
        cedulaEInput.style.backgroundColor = '#f8f9fa';
        cedulaEInput.style.cursor = 'not-allowed';
        console.log('✅ Cédula escolar generada:', c_esc);
      }
    }

    // Manejar cambio en el select de CI
    selectCi.addEventListener('change', function() {
      console.log('🔄 Select CI cambiado a:', this.value);

      if (this.value === 'no') {
        console.log('🎯 Modo: Sin cédula - activando generación automática');

        // ✅ Asegurar que el campo esté listo para ser de solo lectura
        cedulaEInput.placeholder = "Se generará automáticamente";

        // Agregar event listener para cambios de fecha
        fechaInput.addEventListener('change', validarRegistro);
        console.log('👂 Escuchando cambios en fecha...');

        // Ejecutar inmediatamente si ya hay una fecha seleccionada
        if (fechaInput.value) {
          console.log('📋 Fecha ya seleccionada, ejecutando validación...');
          validarRegistro();
        } else {
          console.log('⏳ Esperando selección de fecha...');
        }

      } else if (this.value === 'si') {
        console.log('🆗 Modo: Con cédula - desactivando generación automática');
        // Remover el event listener cuando no es necesario
        fechaInput.removeEventListener('change', validarRegistro);
        // ✅ Limpiar y habilitar el campo para ingreso manual
        cedulaEInput.value = '';
        cedulaEInput.readOnly = false;
        cedulaEInput.style.backgroundColor = '';
        cedulaEInput.style.cursor = '';
        cedulaEInput.placeholder = "Ingrese la cédula de identidad";
        // cedulaEInput.focus();
      }
    });

    // También escuchar cambios en la cédula del representante por si cambia
    cedulaRInput.addEventListener('input', function() {
      console.log('✏️ Cédula representante cambiada:', this.value);
      // Si ya hay fecha seleccionada y estamos en modo "no CI", regenerar
      if (selectCi.value === 'no' && fechaInput.value) {
        console.log('🔄 Regenerando cédula escolar por cambio en cédula representante');
        validarRegistro();
      }
    });

    // Función para contar estudiantes (mantener igual)
    async function validarYGenerarCedula(idRepre, a, c) {
      try {
        console.log('📊 Solicitando cuenta de alumnos para ID:', idRepre);

        const response = await fetch('/final/app/controllers/representantes/cuentaDeAlumnos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id=${encodeURIComponent(idRepre)}`
        });

        const responseText = await response.text();
        console.log('📨 Respuesta del servidor:', responseText);

        let data;
        try {
          data = JSON.parse(responseText);
        } catch (parseError) {
          console.error('❌ Error al parsear JSON:', parseError.message);
          throw new Error(`Error de formato JSON: ${parseError.message}`);
        }

        if (!data.success) {
          throw new Error(data.error || 'Error del servidor');
        }

        console.log('✅ Total estudiantes:', data.total_estudiantes);
        const cedulaEsc = a + (data.total_estudiantes + 1) + c;
        console.log('🔢 Cédula escolar compuesta:', cedulaEsc);
        return cedulaEsc;

      } catch (error) {
        console.error('❌ Error en validarYGenerarCedula:', error);
        return 0;
      }
    }

    // Debug inicial
    console.log('🔍 Estado inicial:', {
      fechaInput: fechaInput ? 'Encontrado' : 'No encontrado',
      cedulaRInput: cedulaRInput ? 'Encontrado' : 'No encontrado',
      cedulaEInput: cedulaEInput ? 'Encontrado' : 'No encontrado',
      selectCi: selectCi ? 'Encontrado' : 'No encontrado',
      selectCiValue: selectCi ? selectCi.value : 'N/A'
    });
  });
</script>

<!-- Aca validamos la cedula de identidad y llenamos campo  -->
<!-- Aca validamos la cedula de identidad y llenamos campo  -->
<!-- Aca validamos la cedula de identidad y llenamos campo  -->
<!-- Aca validamos la cedula de identidad y llenamos campo  -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

    // ========== NAVEGACIÓN ENTRE PASOS ==========
    function showStep(step) {
      // Ocultar todos los pasos
      document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));

      // Mostrar el paso actual
      document.getElementById(`step${step}`).classList.add('active');

      // Actualizar indicador
      document.querySelectorAll('#stepIndicator .nav-link').forEach((link, index) => {
        if (index + 1 === step) {
          link.classList.add('active');
        } else if (index + 1 < step) {
          link.classList.remove('active', 'disabled');
          link.classList.add('completed');
        } else {
          link.classList.remove('active', 'completed');
          link.classList.add('disabled');
        }
      });

      currentStep = step;
    }

    // Event listeners para botones de navegación
    document.getElementById('btn-next-to-step2').addEventListener('click', function() {
      showStep(2);
    });

    document.getElementById('btn-next-to-step3').addEventListener('click', function() {
      // Validar campos requeridos del paso 2 antes de continuar
      const requiredFields = document.querySelectorAll('#step2 [required]');
      let valid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          valid = false;
          field.classList.add('is-invalid');
        } else {
          field.classList.remove('is-invalid');
        }
      });

      if (valid) {
        showStep(3);
      } else {
        alert('Por favor complete todos los campos requeridos del representante.');
      }
    });

    document.getElementById('btn-back-to-step1').addEventListener('click', function() {
      showStep(1);
    });

    document.getElementById('btn-back-to-step2').addEventListener('click', function() {
      showStep(2);
    });

    // ========== VALIDACIÓN DE REPRESENTANTE ==========
    document.getElementById('btn-validar-representante').addEventListener('click', function() {
      const cedula = document.getElementById('cedula_representante').value;
      if (!cedula) {
        alert('Por favor ingrese la cédula del representante');
        return;
      }
      validarRepresentante(cedula);
    });

    function validarRepresentante(cedula) {
      // Crear FormData para enviar por POST
      const formData = new FormData();
      formData.append('cedula', cedula);

      fetch('/final/app/controllers/representantes/validar.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
          }
          return response.json();
        })
        .then(data => {
          const resultado = document.getElementById('resultado-validacion');
          const nextButton = document.getElementById('btn-next-to-step2');

          console.log('📊 Datos recibidos:', data);

          // ✅ FUNCIÓN AUXILIAR PARA ASIGNAR VALORES SEGUROS
          function setValueSafe(elementId, value) {
            const element = document.getElementById(elementId);
            if (element) {
              element.value = value || '';
              console.log(`✅ Asignado ${elementId}:`, value);
            } else {
              console.warn(`⚠️ Elemento no encontrado: ${elementId}`);
            }
          }

          if (data.existe === true) {
            // Determinar el tipo de persona encontrada
            const tipoPersona = data.tipo;
            const esDocente = tipoPersona === 'docente';
            const esRepresentante = tipoPersona === 'representante';

            resultado.innerHTML = `
                    <div class="alert alert-success">
                        <strong>${esDocente ? 'Docente' : 'Representante'} encontrado:</strong> ${data.nombre_completo}
                        <br>Los datos se cargarán automáticamente.
                        ${esDocente ? '<br><em>Nota: Como es docente, algunos campos estarán disponibles para completar</em>' : ''}
                    </div>
                `;

            // PRIMERO: Habilitar TODOS los campos
            document.querySelectorAll('#form-inscripcion input, #form-inscripcion select').forEach(element => {
              element.disabled = false;
            });

            // Llenar los campos comunes (USANDO LA FUNCIÓN SEGURA)
            setValueSafe('representante_existente', '1');
            setValueSafe('id_direccion_repre', data.id_direccion);
            setValueSafe('tipo_persona', tipoPersona);

            if (esRepresentante) {
              setValueSafe('id_representante_existente', data.id_representante);
              setValueSafe('id_representante_existente_esc', data.id_representante);
            } else if (esDocente) {
              setValueSafe('id_representante_existente', data.id_docente);
              setValueSafe('id_representante_existente_esc', data.id_persona);
            }

            // Datos personales (comunes para ambos)
            setValueSafe('cedula_r', data.cedula);
            setValueSafe('primer_nombre_r', data.primer_nombre);
            setValueSafe('segundo_nombre_r', data.segundo_nombre);
            setValueSafe('primer_apellido_r', data.primer_apellido);
            setValueSafe('segundo_apellido_r', data.segundo_apellido);
            setValueSafe('correo_r', data.correo);
            setValueSafe('telefono_r', data.telefono);
            setValueSafe('telefono_hab_r', data.telefono_hab);
            setValueSafe('fecha_nac_r', data.fecha_nac);
            setValueSafe('lugar_nac_r', data.lugar_nac);
            setValueSafe('sexo_r', data.sexo);
            setValueSafe('nacionalidad_r', data.nacionalidad);

            // Cargar SELECT de profesión
            if (data.profesion) {
              setValueSafe('profesion_r', data.profesion);
            }

            // Datos de dirección (comunes para ambos)
            if (data.id_estado) {
              setValueSafe('estado_r', data.id_estado);

              // Cargar municipios para este estado
              cargarMunicipios(data.id_estado).then(() => {
                if (data.id_municipio) {
                  setValueSafe('municipio_r', data.id_municipio);

                  // Cargar parroquias para este municipio
                  cargarParroquias(data.id_municipio).then(() => {
                    if (data.id_parroquia) {
                      setValueSafe('parroquia_r', data.id_parroquia);
                    }
                  });
                }
              });
            }

            // DIFERENCIAS ENTRE DOCENTE Y REPRESENTANTE
            if (esRepresentante) {
              console.log('👨‍👦 Es representante - inhabilitando campos');

              // REPRESENTANTE: Cargar todos los datos
              setValueSafe('ocupacion_r', data.ocupacion);
              setValueSafe('lugar_trabajo_r', data.lugar_trabajo);
              setValueSafe('direccion_r', data.direccion);
              setValueSafe('calle_r', data.calle);
              setValueSafe('casa_r', data.casa);

              // Deshabilitar campos específicos
              const camposDeshabilitar = [
                'primer_nombre_r', 'segundo_nombre_r', 'primer_apellido_r', 'segundo_apellido_r',
                'cedula_r', 'correo_r', 'telefono_r', 'telefono_hab_r', 'fecha_nac_r',
                'lugar_nac_r', 'sexo_r', 'nacionalidad_r', 'profesion_r', 'ocupacion_r',
                'lugar_trabajo_r', 'estado_r', 'municipio_r', 'parroquia_r', 'direccion_r',
                'calle_r', 'casa_r'
              ];

              camposDeshabilitar.forEach(campoId => {
                const elemento = document.getElementById(campoId);
                if (elemento) {
                  elemento.disabled = true;
                  console.log(`🔒 Deshabilitado: ${campoId}`);
                } else {
                  console.warn(`⚠️ No se pudo deshabilitar (no existe): ${campoId}`);
                }
              });

            } else if (esDocente) {
              console.log('👨‍🏫 Es docente - campos específicos habilitados');

              // DOCENTE: Solo cargar datos básicos
              setValueSafe('ocupacion_r', data.ocupacion);
              setValueSafe('lugar_trabajo_r', data.lugar_trabajo);
              setValueSafe('direccion_r', data.direccion);
              setValueSafe('calle_r', data.calle);
              setValueSafe('casa_r', data.casa);

              // Deshabilitar solo campos básicos
              const camposDeshabilitados = [
                'primer_nombre_r', 'segundo_nombre_r', 'primer_apellido_r', 'segundo_apellido_r',
                'cedula_r', 'correo_r', 'telefono_r', 'telefono_hab_r', 'fecha_nac_r',
                'lugar_nac_r', 'sexo_r', 'nacionalidad_r', 'profesion_r', 'estado_r',
                'municipio_r', 'parroquia_r'
              ];

              camposDeshabilitados.forEach(campoId => {
                const elemento = document.getElementById(campoId);
                if (elemento) {
                  elemento.disabled = true;
                  console.log(`🔒 Deshabilitado: ${campoId}`);
                } else {
                  console.warn(`⚠️ No se pudo deshabilitar (no existe): ${campoId}`);
                }
              });

              // Mantener HABILITADOS los campos específicos
              const camposHabilitados = [
                'ocupacion_r', 'lugar_trabajo_r', 'direccion_r', 'calle_r', 'casa_r'
              ];

              camposHabilitados.forEach(campoId => {
                const elemento = document.getElementById(campoId);
                if (elemento) {
                  elemento.disabled = false;
                  console.log(`🔓 Habilitado: ${campoId}`);
                } else {
                  console.warn(`⚠️ No se pudo habilitar (no existe): ${campoId}`);
                }
              });
            }

            // Mostrar botón siguiente
            if (nextButton) {
              nextButton.style.display = 'inline-block';
            }

          } else {
            console.log('❌ Persona no encontrada');
            resultado.innerHTML = `
                    <div class="alert alert-info">
                        <strong>Persona no encontrada.</strong> Por favor complete todos los datos del representante.
                    </div>
                `;

            setValueSafe('cedula_r', cedula);
            setValueSafe('representante_existente', '0');
            setValueSafe('tipo_persona', '');

            // Habilitar todos los campos
            document.querySelectorAll('#form-inscripcion input, #form-inscripcion select').forEach(element => {
              element.disabled = false;
            });

            // Mostrar botón siguiente después de 2 segundos
            setTimeout(() => {
              if (nextButton) {
                nextButton.style.display = 'inline-block';
              }
            }, 2000);
          }
        })
        .catch(error => {
          console.error('❌ Error:', error);
          const resultado = document.getElementById('resultado-validacion');
          if (resultado) {
            resultado.innerHTML = `
                    <div class="alert alert-danger">
                        Error al validar la persona. Intente nuevamente.
                    </div>
                `;
          }
        });
    }

    // ========== CARGAR MUNICIPIOS Y PARROQUIAS ==========
    document.getElementById('estado_r').addEventListener('change', function() {
      const estadoId = this.value;
      const municipioSelect = document.getElementById('municipio_r');
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (estadoId) {
        municipioSelect.disabled = false;
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        cargarMunicipios(estadoId);
      } else {
        municipioSelect.disabled = true;
        parroquiaSelect.disabled = true;
        municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    document.getElementById('municipio_r').addEventListener('change', function() {
      const municipioId = this.value;
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (municipioId) {
        parroquiaSelect.disabled = false;
        cargarParroquias(municipioId);
      } else {
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    function cargarMunicipios(estadoId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('estado_id', estadoId);

        fetch('/final/app/controllers/ubicaciones/municipios.php', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('municipio_r');
            select.innerHTML = '<option value="">Seleccionar Municipio</option>';

            data.forEach(municipio => {
              select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar municipios:', error);
            reject(error);
          });
      });
    }

    function cargarParroquias(municipioId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('municipio_id', municipioId);

        fetch('/final/app/controllers/ubicaciones/parroquias.php', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('parroquia_r');
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';

            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar parroquias:', error);
            reject(error);
          });
      });
    }
  });
</script>

<!-- Aca Enviamos informacion del formulario -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Manejar el envío del formulario
    document.getElementById('form-inscripcion').addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('Formulario enviado - iniciando procesamiento...');
      document.querySelectorAll('#form-inscripcion input:disabled, #form-inscripcion select:disabled').forEach(element => {
        element.disabled = false;
      });
      // Mostrar loading
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      submitBtn.disabled = true;

      // creacion del formulario para enviar datos. 
      const formData = new FormData(this);

      console.log('Datos a enviar:');
      for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
      }
      //Aca tenemos el ajax para enviar toda la inscripcion y 
      fetch(this.action, {
          method: 'POST',
          body: formData
        })
        .then(response => {
          console.log('Respuesta recibida, status:', response.status);

          // Verificar si la respuesta es JSON
          const contentType = response.headers.get('content-type');
          if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta no es JSON');
          }
          return response.json();
        })
        .then(data => {
          console.log('Datos procesados:', data);

          if (data.success) {
            // Mostrar mensaje de éxito
            alert('✅ ' + data.message);
            // Redirigir después de 2 segundos
            setTimeout(() => {
              window.location.href = '/final/admin/index.php';
            }, 2000);
          } else {
            alert('❌ ' + data.message);
            // Rehabilitar botón
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          }
        })
        .catch(error => {
          console.error('Error completo:', error);

          // Mostrar error específico
          if (error.message.includes('JSON')) {
            alert('❌ Error: El servidor no respondió con JSON válido. Verifica que el archivo PHP no tenga errores.');
          } else {
            alert('❌ Error de conexión: ' + error.message);
          }

          // Rehabilitar botón
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        });
    });
  });
</script>
</script>

<!-- Carga de estados, municipios, parroquias del representante -->
<!-- Carga de estados, municipios, parroquias del representante -->
<!-- Carga de estados, municipios, parroquias del representante -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Cargar municipios cuando cambie el estado
    document.getElementById('estado_r').addEventListener('change', function() {
      const estadoId = this.value;
      const municipioSelect = document.getElementById('municipio_r');
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (estadoId) {
        municipioSelect.disabled = false;
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        cargarMunicipios(estadoId);
      } else {
        municipioSelect.disabled = true;
        parroquiaSelect.disabled = true;
        municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    // Cargar parroquias cuando cambie el municipio
    document.getElementById('municipio_r').addEventListener('change', function() {
      const municipioId = this.value;
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (municipioId) {
        parroquiaSelect.disabled = false;
        cargarParroquias(municipioId);
      } else {
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    function cargarMunicipios(estadoId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('estado_id', estadoId);

        fetch('/final/app/controllers/ubicaciones/municipios.php', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('municipio_r');
            select.innerHTML = '<option value="">Seleccionar Municipio</option>';

            data.forEach(municipio => {
              select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar municipios:', error);
            reject(error);
          });
      });
    }

    function cargarParroquias(municipioId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('municipio_id', municipioId);

        fetch('/final/app/controllers/ubicaciones/parroquias.php', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('parroquia_r');
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';

            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar parroquias:', error);
            reject(error);
          });
      });
    }

  });
</script>

<!-- Carga de estados, municipios, parroquias del alumno --->
<!-- Carga de estados, municipios, parroquias del alumno --->
<!-- Carga de estados, municipios, parroquias del alumno --->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Cargar municipios cuando cambie el estado
    document.getElementById('estado_e').addEventListener('change', function() {
      const estadoId = this.value;
      const municipioSelect = document.getElementById('municipio_e');
      const parroquiaSelect = document.getElementById('parroquia_e');

      if (estadoId) {
        municipioSelect.disabled = false;
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        cargarMunicipios(estadoId);
      } else {
        municipioSelect.disabled = true;
        parroquiaSelect.disabled = true;
        municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    // Cargar parroquias cuando cambie el municipio
    document.getElementById('municipio_e').addEventListener('change', function() {
      const municipioId = this.value;
      const parroquiaSelect = document.getElementById('parroquia_e');

      if (municipioId) {
        parroquiaSelect.disabled = false;
        cargarParroquias(municipioId);
      } else {
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    function cargarMunicipios(estadoId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('estado_id', estadoId);

        fetch('/final/app/controllers/ubicaciones/municipios.php', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('municipio_e');
            select.innerHTML = '<option value="">Seleccionar Municipio</option>';

            data.forEach(municipio => {
              select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar municipios:', error);
            reject(error);
          });
      });
    }

    function cargarParroquias(municipioId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('municipio_id', municipioId);

        fetch('/final/app/controllers/ubicaciones/parroquias.php', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('parroquia_e');
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';

            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar parroquias:', error);
            reject(error);
          });
      });
    }

  });
</script>

<!-- Manejo de patologias con select adicionales  -->
<!-- Manejo de patologias con select adicionales  -->
<!-- Manejo de patologias con select adicionales  -->
<!-- Manejo de patologias con select adicionales  -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const contenedorPatologias = document.getElementById('contenedor-patologias');
    const btnAgregarPatologia = document.getElementById('btn-agregar-patologia');

    // Obtener las patologías desde el primer select (que ya viene de la base de datos)
    function obtenerOpcionesPatologias() {
      const primerSelect = document.querySelector('.select-patologia');
      if (!primerSelect) return '';

      // Clonar todas las opciones excepto la primera (placeholder)
      const opciones = Array.from(primerSelect.options)
        .filter(option => option.value !== '')
        .map(option => `<option value="${option.value}">${option.text}</option>`)
        .join('');

      return opciones;
    }

    // Función para crear un nuevo select de patología
    function crearSelectPatologia() {
      const opciones = obtenerOpcionesPatologias();

      const div = document.createElement('div');
      div.className = 'mb-2 patologia-item d-flex align-items-center';

      div.innerHTML = `
            <select name="patologias[]" class="form-control select-patologia me-2">
                <option value="">Seleccione una patología...</option>
                ${opciones}
            </select>
            <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-patologia">
                <i class="fas fa-times"></i>
            </button>
        `;

      return div;
    }

    // Agregar nuevo select
    btnAgregarPatologia.addEventListener('click', function() {
      const nuevoSelect = crearSelectPatologia();
      contenedorPatologias.appendChild(nuevoSelect);

      // Agregar evento al botón eliminar
      const btnEliminar = nuevoSelect.querySelector('.btn-eliminar-patologia');
      btnEliminar.addEventListener('click', function() {
        nuevoSelect.remove();
      });
    });

    // Eliminar select (evento delegado)
    contenedorPatologias.addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-eliminar-patologia') ||
        e.target.closest('.btn-eliminar-patologia')) {
        const btn = e.target.classList.contains('btn-eliminar-patologia') ?
          e.target : e.target.closest('.btn-eliminar-patologia');
        btn.closest('.patologia-item').remove();
      }
    });
  });
</script>

<!-- <script>
  // ========== VALIDACIÓN DE CUPOS EN TIEMPO REAL ==========
  document.addEventListener('DOMContentLoaded', function() {
    const nivelSelect = document.querySelector('select[name="id_nivel"]');
    const seccionSelect = document.querySelector('select[name="id_seccion"]');
    const periodoSelect = document.querySelector('select[name="id_periodo"]');
    const submitBtn = document.querySelector('button[type="submit"]');

    let mensajeCupos = null;

    // Función para verificar cupos
    // function verificarCupos() {
    //   const id_nivel = nivelSelect.value;
    //   const id_seccion = seccionSelect.value;
    //   const id_periodo = periodoSelect.value;

    //   if (!id_nivel || !id_seccion || !id_periodo) {
    //     eliminarMensajeCupos();
    //     return;
    //   }

    //   const formData = new FormData();
    //   formData.append('id_nivel', id_nivel);
    //   formData.append('id_seccion', id_seccion);
    //   formData.append('id_periodo', id_periodo);

    //   fetch('/final/app/controllers/cupos/verificar_cupos.php', {
    //       method: 'POST',
    //       body: formData
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //       mostrarMensajeCupos(data);
    //     })
    //     .catch(error => {
    //       console.error('Error al verificar cupos:', error);
    //     });
    // }

    function verificarCupos() {
      const id_nivel_seccion = seccionSelect.value;
      const id_periodo = periodoSelect.value;

      if (!id_nivel_seccion || !id_periodo) {
        eliminarMensajeCupos();
        return;
      }

      const formData = new FormData();
      formData.append('id_nivel_seccion', id_nivel_seccion);
      formData.append('id_periodo', id_periodo);

      fetch('/final/app/controllers/cupos/verificar_cupos.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          mostrarMensajeCupos(data);
        })
        .catch(error => {
          console.error('Error al verificar cupos:', error);
        });
    }

    // Función para mostrar mensaje de cupos
    // function mostrarMensajeCupos(data) {
    //   eliminarMensajeCupos();

    //   const informacionAcademica = document.querySelector('.informacion_academica .card-body');
    //   if (!informacionAcademica) return;

    //   mensajeCupos = document.createElement('div');
    //   mensajeCupos.className = `alert ${data.disponible ? 'alert-success' : 'alert-danger'} mt-3`;
    //   mensajeCupos.innerHTML = `
    //         <strong>${data.disponible ? '✅ CUPOS DISPONIBLES' : '❌ SIN CUPOS'}</strong><br>
    //         ${data.mensaje}
    //         ${data.disponible ? 
    //             `<br><small class="text-muted">Puede continuar con la inscripción</small>` : 
    //             `<br><small class="text-muted">No se puede realizar la inscripción en esta sección</small>`
    //         }
    //     `;

    //   informacionAcademica.appendChild(mensajeCupos);

    //   // Deshabilitar/enable el botón de enviar
    //   if (submitBtn) {
    //     submitBtn.disabled = !data.disponible;
    //   }
    // }
    function mostrarMensajeCupos(data) {
      eliminarMensajeCupos();

      const informacionAcademica = document.querySelector('.informacion_academica .card-body');
      if (!informacionAcademica) return;

      mensajeCupos = document.createElement('div');
      mensajeCupos.className = `alert ${data.disponible ? 'alert-success' : 'alert-danger'} mt-3`;
      mensajeCupos.innerHTML = `
            <strong>${data.disponible ? '✅ CUPOS DISPONIBLES' : '❌ SIN CUPOS'}</strong><br>
            ${data.mensaje}
            ${data.disponible ? 
                `<br><small class="text-muted">Puede continuar con la inscripción</small>` : 
                `<br><small class="text-muted">No se puede realizar la inscripción en esta sección</small>`
            }
        `;

      informacionAcademica.appendChild(mensajeCupos);

      // Deshabilitar/enable el botón de enviar
      if (submitBtn) {
        submitBtn.disabled = !data.disponible;
      }
    }

    // Función para eliminar mensaje de cupos
    // function eliminarMensajeCupos() {
    //   if (mensajeCupos) {
    //     mensajeCupos.remove();
    //     mensajeCupos = null;
    //   }
    //   if (submitBtn) {
    //     submitBtn.disabled = false;
    //   }
    // }
    function eliminarMensajeCupos() {
      if (mensajeCupos) {
        mensajeCupos.remove();
        mensajeCupos = null;
      }
      if (submitBtn) {
        submitBtn.disabled = false;
      }
    }

    // Event listeners para cambios en los selects
    if (nivelSelect) nivelSelect.addEventListener('change', verificarCupos);
    if (seccionSelect) seccionSelect.addEventListener('change', verificarCupos);
    if (periodoSelect) periodoSelect.addEventListener('change', verificarCupos);

    // Verificar cupos al cargar si ya hay valores seleccionados
    setTimeout(verificarCupos, 500);
  });
</script> -->