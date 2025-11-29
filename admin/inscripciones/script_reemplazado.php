<!-- Aca Enviamos informacion del formulario -->
<!-- <script>
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

   // ========== GENERAR CONSTANCIA DESPUÉS DE INSCRIPCIÓN EXITOSA ==========
  function generarConstanciaInscripcion(idInscripcion) {
      console.log('📄 Generando constancia para inscripción ID:', idInscripcion);
      
      // Abrir en nueva pestaña para generar el PDF
      const url = `/final/app/controllers/inscripciones/generar_constancia.php?id_inscripcion=${idInscripcion}`;
      window.open(url, '_blank');
  }

  // Modificar el manejo del éxito en el envío del formulario
  document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('form-inscripcion');
      
      // Guardar el event listener original
      const originalSubmitHandler = form.onsubmit;
      
      form.onsubmit = function(e) {
          e.preventDefault();
          
          // Mostrar loading
          const submitBtn = this.querySelector('button[type="submit"]');
          const originalText = submitBtn.innerHTML;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
          submitBtn.disabled = true;

          // Habilitar campos deshabilitados temporalmente para el envío
          document.querySelectorAll('#form-inscripcion input:disabled, #form-inscripcion select:disabled').forEach(element => {
              element.disabled = false;
          });

          const formData = new FormData(this);

          fetch(this.action, {
              method: 'POST',
              body: formData
          })
          .then(response => {
              const contentType = response.headers.get('content-type');
              if (!contentType || !contentType.includes('application/json')) {
                  throw new Error('La respuesta no es JSON');
              }
              return response.json();
          })
          .then(data => {
              console.log('Respuesta del servidor:', data);

              if (data.success) {
                  // Mostrar mensaje de éxito
                  alert('✅ ' + data.message);
                  
                  // Generar constancia si tenemos el ID de inscripción
                  if (data.id_inscripcion) {
                      setTimeout(() => {
                          generarConstanciaInscripcion(data.id_inscripcion);
                      }, 1000);
                  }
                  
                  // Redirigir después de 3 segundos
                  setTimeout(() => {
                      window.location.href = '/final/admin/index.php';
                  }, 3000);
                  
              } else {
                  alert('❌ ' + data.message);
                  // Rehabilitar botón
                  submitBtn.innerHTML = originalText;
                  submitBtn.disabled = false;
              }
          })
          .catch(error => {
              console.error('Error completo:', error);
              
              if (error.message.includes('JSON')) {
                  alert('❌ Error: El servidor no respondió con JSON válido. Verifica que el archivo PHP no tenga errores.');
              } else {
                  alert('❌ Error de conexión: ' + error.message);
              }

              // Rehabilitar botón
              submitBtn.innerHTML = originalText;
              submitBtn.disabled = false;
          });
      };
  });
</script>
</script> --> -->