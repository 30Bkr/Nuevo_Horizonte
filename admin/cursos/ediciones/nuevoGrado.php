                          <div class="modal fade" id="modal_asignacion_grados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                              <div class="modal-content">
                                <div class="modal-header bg-gradient-primary">
                                  <h5 class="modal-title">
                                    <strong>Nuevo Grado</strong>
                                  </h5>
                                </div>
                                <form action="">
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <label for="grado">Grado</label>
                                      <input type="text" class="form-control" name="grado" id="grado">
                                    </div>
                                    <div class="form-group">
                                      <label for="nom_seccion">Sección</label>
                                      <input type="text" class="form-control" name="nom_seccion" id="nom_seccion">
                                    </div>
                                    <div class="form-group">
                                      <label for="capacidad">Capacidad</label>
                                      <input type="text" class="form-control" name="capacidad" id="capacidad" onkeydown="manejarTeclaG(event)">
                                      <span id="error_capacidad"></span>
                                    </div>
                                    <div class="form-group">
                                      <label for="turno">Turno</label>
                                      <select name="turno" id="turno" class="form-control">
                                        <?php
                                        foreach ($turnoss as $turnos) { ?>
                                          <option value="<?= $turnos; ?>"><?php echo strtoupper($turnos) ?></option>
                                        <?php } ?>

                                      </select>
                                      <div id="error-turno"></div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button id="btn_agregar" class="btn btn-lg bg-gradient-success" data-dismiss="modal" aria-label="Close">
                                      Agregar
                                    </button>

                                    <script>
                                      let esValido = true;
                                      document.getElementById('btn_agregar').addEventListener('click', function(e) {
                                        e.preventDefault();
                                        let capacidad = document.getElementById('capacidad').value;
                                        let nom_seccion = document.getElementById('nom_seccion').value;
                                        let grado = document.getElementById('grado').value;
                                        let turno = document.getElementById('turno').value;
                                        console.log('Agarramos el input');
                                        console.log();
                                        if (!esValido) {
                                          // capacidad.textContent = 'Por favor verificar los datos ingresados'
                                          console.log('No paso');
                                          window.alert('No se fue posible actualizar la tabla')
                                          return;
                                        } else {
                                          // capacidad.textContent = 'si mandamos el formulario'
                                          console.log('Si paso');


                                          enviarDatos({
                                            capadidad: capacidad,
                                            nom_seccion: nom_seccion,
                                            grado: grado,
                                            turno: turno
                                          })
                                        }

                                      });

                                      function manejarTeclaG(event) {
                                        let valor = event.target.value;
                                        let tecla = event.key;
                                        // console.log(valor);

                                        let capacidad = document.getElementById("error_capacidad");
                                        esValido = true;
                                        if (/^[0-9]+$/.test(tecla) || tecla == 'Backspace') {
                                          capacidad.textContent = ''
                                          esValido = true;
                                          if (valor.length > 1 && tecla != 'Backspace') {
                                            // console.log(valor);
                                            event.preventDefault();
                                            let total = parseInt(valor);
                                            // console.log(total);
                                            if (total > 37) {
                                              capacidad.textContent = 'Excede limite de cupos disponibles'
                                              esValido = false;
                                            }
                                          } else {
                                            let numero = valor + tecla;
                                            let total = parseInt(numero);
                                            if (total > 37) {
                                              capacidad.textContent = 'Excede limite de cupos por seccion'
                                              esValido = false;
                                            }
                                          }
                                        } else {
                                          event.preventDefault();
                                          capacidad.textContent = 'Solo caracteres numericos aca'
                                          esValido = false;
                                        }
                                      }
                                      async function enviarDatos(datos) {
                                        console.log('🚀 Iniciando enviarDatos con:', datos);

                                        let formData = new FormData();
                                        formData.append('capacidad', datos.capacidad);
                                        formData.append('nom_seccion', datos.nom_seccion);
                                        formData.append('grado', datos.grado);
                                        formData.append('turno', datos.turno);

                                        try {
                                          console.log('🔍 Paso 1: Verificando duplicados...');
                                          console.log('📤 Enviando a verificar.php:', {
                                            grado: datos.grado,
                                            seccion: datos.nom_seccion
                                          });
                                          let response2 = await fetch('/final/app/controllers/cursos/verificar.php', {
                                            method: 'POST',
                                            body: formData
                                          });

                                          let text2 = await response2.text();
                                          console.log('📨 Respuesta CRUDA de verificar:', text2);

                                          let data2;
                                          try {
                                            data2 = JSON.parse(text2);
                                            console.log('✅ JSON parseado de verificar:', data2);

                                            // ✅ DEBUG DETALLADO DE LA RESPUESTA
                                            console.log('📊 data2.success:', data2.success);
                                            console.log('📊 data2.message:', data2.message);
                                            console.log('📊 data2.data.existe:', data2.data?.existe);

                                            if (data2.success) {
                                              console.log('✅ VERIFICACIÓN: No hay duplicados, procediendo a crear...');

                                              try {
                                                console.log('🚀 Paso 2: Creando en createGrado.php...');
                                                let response = await fetch('/final/app/controllers/cursos/createGrado.php', {
                                                  method: 'POST',
                                                  body: formData
                                                });

                                                let text = await response.text();
                                                console.log('📨 Respuesta CRUDA de creación:', text);

                                                let data;
                                                try {
                                                  data = JSON.parse(text);
                                                  console.log('✅ JSON parseado de creación:', data);

                                                  if (data.success) {
                                                    console.log('🎉 Grado creado exitosamente');
                                                    alert('✅ ' + data.message);
                                                    // setTimeout(() => location.reload(), 1000);
                                                  } else {
                                                    console.log('❌ Error al crear:', data.message);
                                                    alert('❌ ' + data.message);
                                                  }

                                                } catch (error) {
                                                  console.error('❌ Error parseando JSON de creación:', error);
                                                  alert('❌ Error: Respuesta inválida del servidor al crear');
                                                }

                                              } catch (error) {
                                                console.error('🔥 Error de conexión al crear:', error);
                                                alert('❌ Error de conexión al crear');
                                              }

                                            } else {
                                              console.log('❌ VERIFICACIÓN: No podemos registrarlo - Motivo:', data2.message);
                                              alert('❌ ' + data2.message);
                                            }

                                          } catch (error) {
                                            console.error('❌ Error parseando JSON de verificación:', error);
                                            alert('❌ Error: Respuesta inválida del servidor de verificación');
                                          }

                                        } catch (error) {
                                          console.error('🔥 Error de conexión en verificación:', error);
                                          alert('❌ Error de conexión al servidor');
                                        }
                                      }
                                      // async function enviarDatos(datos) {
                                      //   let formData = new FormData();
                                      //   formData.append('capacidad', datos.capacidad);
                                      //   formData.append('nom_seccion', datos.nom_seccion);
                                      //   formData.append('grado', datos.grado);
                                      //   formData.append('turno', datos.turno);
                                      //   formData.append('id', datos.id);
                                      //   try {
                                      //     let response2 = await fetch('/final/app/controllers/cursos/verificar.php', {
                                      //       method: 'POST',
                                      //       body: formData
                                      //     })
                                      //     let text2 = await response2.text();
                                      //     console.log('Respuesta cruda de verificar text2: ', text2);

                                      //     let data2;
                                      //     try {
                                      //       data2 = JSON.parse(text2);
                                      //       console.log('Json valido ', data2);
                                      //       if (data2.success) {
                                      //         console.log('Si podemos registrarlo');
                                      //         try {
                                      //           let response = await fetch('/final/app/controllers/cursos/createGrado.php', {
                                      //             method: 'POST',
                                      //             body: formData
                                      //           });
                                      //           let text = await response.text();
                                      //           console.log('respuesta cruda al enviar a crear: ', text);
                                      //           let data;
                                      //           try {
                                      //             data = JSON.parse(text);
                                      //             console.log('Json valido despues de verificar:  ', data);
                                      //             // location.reload();
                                      //           } catch (error) {
                                      //             console.error('Erro parsenado JSON: ', error);
                                      //             console.error('Respuesta no es JSON válido: ', text.substring(0, 100));
                                      //           }
                                      //         } catch (error) {

                                      //         }
                                      //       } else {
                                      //         console.log('No podemos registrarlo');


                                      //       };
                                      //       // location.reload();
                                      //     } catch (error) {
                                      //       console.error('Erro parsenado JSON: ', error);
                                      //       console.error('Respuesta no es JSON válido: ', text2.substring(0, 100));

                                      //     }

                                      //   } catch (error) {
                                      //     console.error('🔥 Error de conexión:', error);
                                      //     alert('Error de conexión: ' + error.message);
                                      //   }
                                      // }
                                    </script>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>