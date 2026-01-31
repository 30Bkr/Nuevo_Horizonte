 <?php if (count($estados) > 0): ?>
                        <?php foreach ($estados as $estado):
                          try {
                            $en_uso = $ubicacionController->estadoEnUso($estado['id_estado']);
                            $conteo_usos = $ubicacionController->obtenerConteoUsosEstado($estado['id_estado']);
                          } catch (Exception $e) {
                            $en_uso = false;
                            $conteo_usos = 0;
                          }
                        ?>
                          <tr data-id="<?php echo $estado['id_estado']; ?>"
                            data-nombre="<?php echo htmlspecialchars(strtolower($estado['nom_estado'])); ?>"
                            data-id-texto="<?php echo $estado['id_estado']; ?>">
                            <td class="col-id"><?php echo $estado['id_estado']; ?></td>
                            <td class="col-nombre"><?php echo htmlspecialchars($estado['nom_estado']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($estado['creacion'])); ?></td>
                            <td>
                              <?php
                              if ($estado['actualizacion']) {
                                echo date('d/m/Y H:i', strtotime($estado['actualizacion']));
                              } else {
                                echo 'No actualizado';
                              }
                              ?>
                            </td>
                            <td>
                              <?php if ($en_uso): ?>
                                <span class="badge badge-warning" data-toggle="tooltip" title="Usado en <?php echo $conteo_usos; ?> dirección(es) activa(s)">
                                  <i class="fas fa-exclamation-triangle mr-1"></i>En uso (<?php echo $conteo_usos; ?>)
                                </span>
                              <?php else: ?>
                                <span class="badge badge-secondary">
                                  <i class="fas fa-check-circle mr-1"></i>Sin uso
                                </span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <span class="badge badge-<?php echo $estado['estatus'] == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $estado['estatus'] == 1 ? 'Habilitado' : 'Inhabilitado'; ?>
                              </span>
                            </td>
                            <td>
                              <form method="POST" class="d-inline">
                                <input type="hidden" name="id_estado" value="<?php echo $estado['id_estado']; ?>">
                                <input type="hidden" name="estatus" value="<?php echo $estado['estatus'] == 1 ? 0 : 1; ?>">
                                <button type="button"
                                  class="btn btn-sm btn-<?php echo $estado['estatus'] == 1 ? 'warning' : 'success'; ?> btn-confirmar"
                                  data-id="<?php echo $estado['id_estado']; ?>"
                                  data-nombre="<?php echo htmlspecialchars($estado['nom_estado']); ?>"
                                  data-estatus="<?php echo $estado['estatus']; ?>"
                                  data-en-uso="<?php echo $en_uso ? '1' : '0'; ?>"
                                  data-conteo-usos="<?php echo $conteo_usos; ?>"
                                  data-accion="<?php echo $estado['estatus'] == 1 ? 'inhabilitar' : 'habilitar'; ?>"
                                  data-toggle="modal"
                                  data-target="#modalConfirmacion">
                                  <i class="fas fa-<?php echo $estado['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                  <?php echo $estado['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                                </button>
                              </form>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="7" class="text-center">No hay estados registrados</td>
                        </tr>
                      <?php endif; ?>              