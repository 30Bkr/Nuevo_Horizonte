<?php if (count($parroquias) > 0): ?>
                        <?php foreach ($parroquias as $parroquia):
                          try {
                            $en_uso = $ubicacionController->parroquiaEnUso($parroquia['id_parroquia']);
                            $conteo_usos = $ubicacionController->obtenerConteoUsosParroquia($parroquia['id_parroquia']);
                          } catch (Exception $e) {
                            $en_uso = false;
                            $conteo_usos = 0;
                          }
                        ?>
                          <tr>
                            <td class="col-id"><?php echo $parroquia['id_parroquia']; ?></td>
                            <td class="col-parroquia"><?php echo htmlspecialchars($parroquia['nom_parroquia']); ?></td>
                            <td class="col-municipio"><?php echo htmlspecialchars($parroquia['nom_municipio']); ?></td>
                            <td class="col-estado"><?php echo htmlspecialchars($parroquia['nom_estado']); ?></td>
                            <td class="col-creacion"><?php echo date('d/m/Y H:i', strtotime($parroquia['creacion'])); ?></td>
                            <td class="col-actualizacion">
                              <?php
                              if ($parroquia['actualizacion']) {
                                echo date('d/m/Y H:i', strtotime($parroquia['actualizacion']));
                              } else {
                                echo 'No actualizado';
                              }
                              ?>
                            </td>
                            <td class="col-en-uso">
                              <?php if ($en_uso): ?>
                                <span class="badge badge-warning" data-toggle="tooltip" title="Usada en <?php echo $conteo_usos; ?> dirección(es) activa(s)">
                                  <i class="fas fa-exclamation-triangle mr-1"></i>En uso (<?php echo $conteo_usos; ?>)
                                </span>
                              <?php else: ?>
                                <span class="badge badge-secondary">
                                  <i class="fas fa-check-circle mr-1"></i>Sin uso
                                </span>
                              <?php endif; ?>
                            </td>
                            <td class="col-estatus">
                              <span class="badge badge-<?php echo $parroquia['estatus'] == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $parroquia['estatus'] == 1 ? 'Habilitada' : 'Inhabilitada'; ?>
                              </span>
                            </td>
                            <td class="col-acciones">
                              <button type="button"
                                class="btn btn-sm btn-<?php echo $parroquia['estatus'] == 1 ? 'warning' : 'success'; ?> btn-confirmar-parroquia"
                                data-id="<?php echo $parroquia['id_parroquia']; ?>"
                                data-nombre="<?php echo htmlspecialchars($parroquia['nom_parroquia']); ?>"
                                data-municipio="<?php echo htmlspecialchars($parroquia['nom_municipio']); ?>"
                                data-estado="<?php echo htmlspecialchars($parroquia['nom_estado']); ?>"
                                data-estatus="<?php echo $parroquia['estatus']; ?>"
                                data-en-uso="<?php echo $en_uso ? '1' : '0'; ?>"
                                data-conteo-usos="<?php echo $conteo_usos; ?>"
                                data-accion="<?php echo $parroquia['estatus'] == 1 ? 'inhabilitar' : 'habilitar'; ?>"
                                data-toggle="modal"
                                data-target="#modalConfirmacionParroquia">
                                <i class="fas fa-<?php echo $parroquia['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                <?php echo $parroquia['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                              </button>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="9" class="text-center">
                            <?php if ($id_estado_filtro && empty($municipios)): ?>
                              No hay municipios disponibles para el estado seleccionado
                            <?php else: ?>
                              No hay parroquias registradas
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endif; ?>