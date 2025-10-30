                          <div class="modal fade" id="modal_asignacion<?= $listaGrados[$i]->id_grados_secciones ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <input type="text" class="form-control" name="año" id="año" value="<?php echo $edicion[0]->grado ?>">
                                  </div>
                                  <div class="form-group">
                                    <label for="seccion">Sección</label>
                                    <input type="text" class="form-control" name="seccion" id="seccion" value="<?php echo $edicion[0]->nom_seccion ?>">
                                  </div>
                                  <div class="form-group">
                                    <label for="capacidad">Capacidad</label>
                                    <input type="text" class="form-control" name="capacidad" id="capacidad" value="<?php echo $edicion[0]->capacidad ?>">
                                  </div>
                                  <div class="form-group">
                                    <label for="turno">Turno</label>
                                    <input type="text" class="form-control" name="turno" id="turno" value="<?php echo $edicion[0]->turno ?>">
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-lg bg-gradient-success" data-dismiss="modal" aria-label="Close">
                                    Actualizar
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>