                          <div class="modal fade" id="modal_asignacion<?= $lg->id_grados_secciones ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                              <div class="modal-content">
                                <div class="modal-header bg-gradient-primary">
                                  <h5 class="modal-title">
                                    <strong><?= $edicion[0]->grado . "°- " . $edicion[0]->nom_seccion ?></strong>
                                  </h5>
                                </div>
                                <form action="">
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <label for="grado">Grado</label>
                                      <input type="text" class="form-control" name="grado" id="grado<?php echo $lg->id_grados_secciones ?>" value="<?php echo $edicion[0]->grado ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                      <label for="nom_seccion">Sección</label>
                                      <input type="text" class="form-control" name="nom_seccion" id="nom_seccion<?php echo $lg->id_grados_secciones ?>" value="<?php echo $edicion[0]->nom_seccion ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                      <label for="capacidad">Capacidad</label>
                                      <input type="text" class="form-control" name="capacidad" id="capacidad<?php echo $lg->id_grados_secciones ?>" value="<?php echo $edicion[0]->capacidad ?>" onkeydown="manejarTecla<?php echo $edicion[0]->id_grados_secciones ?>(event)" maxlength="2">
                                      <span id="error_capacidad<?php echo $edicion[0]->id_grados_secciones ?>"></span>
                                    </div>
                                    <div class="form-group">
                                      <label for="turno">Turno</label>
                                      <select name="turno" id="turno<?php echo $lg->id_grados_secciones ?>" class="form-control">
                                        <?php
                                        foreach ($turnoss as $turnos) { ?>
                                          <?php
                                          if ($edicion[0]->turno == $turnos) {
                                          ?>
                                            <option value="<?= $turnos; ?>" selected><?php echo strtoupper($turnos) ?></option>
                                          <?php } else { ?>
                                            <option value="<?= $turnos; ?>"><?php echo strtoupper($turnos) ?></option>
                                          <?php } ?>

                                        <?php
                                        }
                                        ?>
                                      </select>
                                      <div id="error-turno"></div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button id="btn_<?php echo $lg->id_grados_secciones ?>" class="btn btn-lg bg-gradient-success" data-dismiss="modal" aria-label="Close">
                                      Actualizar
                                    </button>
                                    <script>
                                      let esValido<?php echo $edicion[0]->id_grados_secciones ?> = true;
                                      document.getElementById('btn_<?php echo $lg->id_grados_secciones ?>').addEventListener('click', function(e) {
                                        e.preventDefault();
                                        let id<?php echo $edicion[0]->id_grados_secciones ?> = <?php echo $edicion[0]->id_grados_secciones ?>;

                                        let capacidad<?php echo $lg->id_grados_secciones ?> = document.getElementById('capacidad<?php echo $lg->id_grados_secciones ?>').value;
                                        let nom_seccion<?php echo $lg->id_grados_secciones ?> = document.getElementById('nom_seccion<?php echo $lg->id_grados_secciones ?>').value;
                                        let grado<?php echo $lg->id_grados_secciones ?> = document.getElementById('grado<?php echo $lg->id_grados_secciones ?>').value;
                                        let turno<?php echo $lg->id_grados_secciones ?> = document.getElementById('turno<?php echo $lg->id_grados_secciones ?>').value;
                                        console.log('Agarramos el input');
                                        console.log();


                                        if (!esValido<?php echo $edicion[0]->id_grados_secciones ?>) {
                                          // capacidad.textContent = 'Por favor verificar los datos ingresados'
                                          console.log('No paso');
                                          window.alert('No se fue posible actualizar la tabla')
                                          return;
                                        } else {
                                          // capacidad.textContent = 'si mandamos el formulario'
                                          console.log('Si paso');


                                          enviarDatos<?php echo $edicion[0]->id_grados_secciones ?>({
                                            capacidad<?php echo $lg->id_grados_secciones ?>,
                                            nom_seccion<?php echo $lg->id_grados_secciones ?>,
                                            grado<?php echo $lg->id_grados_secciones ?>,
                                            turno<?php echo $lg->id_grados_secciones ?>,
                                            id<?php echo $edicion[0]->id_grados_secciones ?>
                                          })
                                        }

                                      });

                                      function manejarTecla<?php echo $edicion[0]->id_grados_secciones ?>(event) {
                                        let valor = event.target.value;
                                        let tecla = event.key;
                                        let capacidad = document.getElementById("error_capacidad<?php echo $edicion[0]->id_grados_secciones ?>");
                                        esValido<?php echo $edicion[0]->id_grados_secciones ?> = true;
                                        if (/^[0-9]+$/.test(tecla) || tecla == 'Backspace') {
                                          capacidad.textContent = ''
                                          esValido<?php echo $edicion[0]->id_grados_secciones ?> = true;
                                          if (valor.length > 1 && tecla != 'Backspace') {
                                            event.preventDefault();
                                            let total = parseInt(valor);
                                            if (total > 37) {
                                              capacidad.textContent = 'Excede limite de cupos disponibles'
                                              esValido<?php echo $edicion[0]->id_grados_secciones ?> = false;
                                            }
                                          } else {
                                            let numero = valor + tecla;
                                            let total = parseInt(numero);
                                            if (total > 37) {
                                              capacidad.textContent = 'Excede limite de cupos por seccion'
                                              esValido<?php echo $edicion[0]->id_grados_secciones ?> = false;
                                            }
                                          }
                                        } else {
                                          event.preventDefault();
                                          capacidad.textContent = 'Solo caracteres numericos'
                                          esValido<?php echo $edicion[0]->id_grados_secciones ?> = false;
                                        }
                                      }

                                      async function enviarDatos<?php echo $edicion[0]->id_grados_secciones ?>(datos) {
                                        let formData = new FormData();
                                        formData.append('capacidad', datos.capacidad<?php echo $edicion[0]->id_grados_secciones ?>);
                                        formData.append('nom_seccion', datos.nom_seccion<?php echo $edicion[0]->id_grados_secciones ?>);
                                        formData.append('grado', datos.grado<?php echo $edicion[0]->id_grados_secciones ?>);
                                        formData.append('turno', datos.turno<?php echo $edicion[0]->id_grados_secciones ?>);
                                        formData.append('id', datos.id<?php echo $edicion[0]->id_grados_secciones ?>);
                                        try {
                                          let response<?php echo $edicion[0]->id_grados_secciones ?> = await fetch('/final/app/controllers/cursos/editGrado.php', {
                                            method: 'POST',
                                            body: formData
                                          });
                                          let text<?php echo $edicion[0]->id_grados_secciones ?> = await response<?php echo $edicion[0]->id_grados_secciones ?>.text();
                                          console.log('respuesta cruda: ', text<?php echo $edicion[0]->id_grados_secciones ?>);
                                          let data<?php echo $edicion[0]->id_grados_secciones ?>;
                                          try {
                                            data<?php echo $edicion[0]->id_grados_secciones ?> = JSON.parse(text<?php echo $edicion[0]->id_grados_secciones ?>);
                                            console.log('Json valido ', data<?php echo $edicion[0]->id_grados_secciones ?>);
                                            location.reload();
                                          } catch (error) {
                                            console.error('Erro parsenado JSON: ', error);
                                            console.error('Respuesta no es JSON válido: ', text<?php echo $edicion[0]->id_grados_secciones ?>.substring(0, 100));

                                          }
                                        } catch (error) {
                                          console.error('🔥 Error de conexión:', error);
                                          alert('Error de conexión: ' + error.message);
                                        }
                                        // fetch('/final/app/controllers/cursos/editGrado.php', {
                                        //     method: 'POST',
                                        //     body: formData
                                        //   })
                                        //   .then(response => {
                                        //     console.log(response);
                                        //     return response.text().then(text => {
                                        //       console.log('Respuesta cruda:', text);

                                        //       try {
                                        //         // Intentar parsear como JSON

                                        //         return JSON.parse(text);
                                        //       } catch (e) {
                                        //         console.error('No es JSON válido:', text);
                                        //         throw new Error('Respuesta no es JSON válido: ' + text.substring(0, 100));
                                        //       }
                                        //     });

                                        //   })
                                        //   .then(data => {
                                        //     console.log('se actualizaron los datos correctamente.');
                                        //     console.log(data.id);
                                        //     console.log(data.turno);
                                        //     console.log(data.capacidad);


                                        //   })
                                        //   .catch(error => {
                                        //     console.log('Error al recibir info');
                                        //     console.log(data.id);
                                        //     console.log(data.turno);
                                        //     console.log(data.capacidad);
                                        //   })
                                      }
                                    </script>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>