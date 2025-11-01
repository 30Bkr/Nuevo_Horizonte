                          <div class="modal fade" id="modal_asignacion<?= $lg->id_grados_secciones ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                              <div class="modal-content">
                                <div class="modal-header bg-gradient-primary">
                                  <h5 class="modal-title">
                                    <strong><?= $edicion[0]->grado . "°- " . $edicion[0]->nom_seccion ?></strong>
                                  </h5>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group">
                                    <label for="año">Año</label>
                                    <input type="text" class="form-control" name="año" id="año<?php $lg->id_grados_secciones ?>" value="<?php echo $edicion[0]->grado ?>" readonly>
                                  </div>
                                  <div class="form-group">
                                    <label for="seccion">Sección</label>
                                    <input type="text" class="form-control" name="seccion" id="seccion<?php $lg->id_grados_secciones ?>" value="<?php echo $edicion[0]->nom_seccion ?>" readonly>
                                  </div>
                                  <div class="form-group">
                                    <label for="capacidad">Capacidad</label>
                                    <input type="text" class="form-control" name="capacidad" id="capacidad<?php $lg->id_grados_secciones ?>" value="<?php echo $edicion[0]->capacidad ?>" onkeydown="manejarTecla<?php echo $edicion[0]->id_grados_secciones ?>(event)" maxlength="2">
                                    <span id="error_capacidad<?php echo $edicion[0]->id_grados_secciones ?>">hol</span>
                                  </div>
                                  <div class="form-group">
                                    <label for="turno">Turno</label>
                                    <input type="text" class="form-control" name="turno" id="turno<?php $lg->id_grados_secciones ?>" value="<?php echo $edicion[0]->turno ?>">
                                    <div id="error-turno"></div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" id="btn_<?php $lg->id_grados_secciones ?>" class="btn btn-lg bg-gradient-success" data-dismiss="modal" aria-label="Close">
                                    Actualizar
                                  </button>
                                  <script>
                                    document.getElementById('btn_<?php $lg->id_grados_secciones ?>').addEventListener('submit', function(e) {
                                      e.preventDefault();

                                      let capacidad = document.getElementById('capacidad').value;
                                      let turno = document.getElementById('turno').value;

                                      console.log('ingresamos al script. con:', seccion);
                                      if (!validarDatos(turno, capacidad)) {
                                        return;
                                      }
                                      enviarDatosPHP({
                                        turno,
                                        capacidad
                                      })
                                    });

                                    function manejarTecla<?php echo $edicion[0]->id_grados_secciones ?>(event) {
                                      let valor = event.target.value;
                                      let tecla = event.key;
                                      let capacidad = document.getElementById("error_capacidad<?php echo $edicion[0]->id_grados_secciones ?>");

                                      let valido = true;
                                      if (/^[0-9]+$/.test(tecla) || tecla == 'Backspace') {
                                        capacidad.textContent = ''
                                        if (valor.length > 1 && tecla != 'Backspace') {
                                          event.preventDefault();
                                          valido = false;
                                          let total = parseInt(valor);
                                          if (total > 40) {
                                            console.log('Excede limite de alumnos por salon');
                                            capacidad.textContent = 'Excede limite de cupos disponibles'
                                          }
                                        } else {
                                          let numero = valor + tecla;
                                          let total = parseInt(numero);
                                          if (total > 40) {
                                            capacidad.textContent = 'Excede limite de cupos por seccion'

                                          }
                                        }
                                      } else {
                                        event.preventDefault();
                                        capacidad.textContent = 'Solo caracteres numericos'
                                      }
                                    }

                                    function validarDatos(turno, capacidad) {
                                      let capacidad1 = capacidad.toUpperCase();

                                      if (capacidad1 != 'MAÑANA' || capacidad1 != 'TARDE') {
                                        console.error('No se puede enviar el turno indicado');
                                        return false;
                                      }
                                    }
                                  </script>
                                </div>
                              </div>
                            </div>
                          </div>