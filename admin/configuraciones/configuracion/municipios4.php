<?php if (count($municipios) > 0): ?>
                        <?php foreach ($municipios as $municipio):
                          try {
                            $en_uso = $ubicacionController->municipioEnUso($municipio['id_municipio']);
                            $conteo_usos = $ubicacionController->obtenerConteoUsosMunicipio($municipio['id_municipio']);
                          } catch (Exception $e) {
                            $en_uso = false;
                            $conteo_usos = 0;
                          }
                        ?>
                          <tr data-id="<?php echo $municipio['id_municipio']; ?>"
                            data-nombre="<?php echo htmlspecialchars(strtolower($municipio['nom_municipio'])); ?>"
                            data-estado="<?php echo htmlspecialchars(strtolower($municipio['nom_estado'])); ?>"
                            data-id-texto="<?php echo $municipio['id_municipio']; ?>">
                            <td class="col-id"><?php echo $municipio['id_municipio']; ?></td>
                            <td class="col-nombre"><?php echo htmlspecialchars($municipio['nom_municipio']); ?></td>
                            <td class="col-estado"><?php echo htmlspecialchars($municipio['nom_estado']); ?></td>
                            <td class="col-creacion"><?php echo date('d/m/Y H:i', strtotime($municipio['creacion'])); ?></td>
                            <td class="col-actualizacion">
                              <?php
                              if ($municipio['actualizacion']) {
                                echo date('d/m/Y H:i', strtotime($municipio['actualizacion']));
                              } else {
                                echo 'No actualizado';
                              }
                              ?>
                            </td>
                            <td class="col-en-uso">
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
                            <td class="col-estatus">
                              <span class="badge badge-<?php echo $municipio['estatus'] == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $municipio['estatus'] == 1 ? 'Habilitado' : 'Inhabilitado'; ?>
                              </span>
                            </td>
                            <td class="col-acciones">
                              <form method="POST" class="d-inline">
                                <input type="hidden" name="id_municipio" value="<?php echo $municipio['id_municipio']; ?>">
                                <input type="hidden" name="estatus" value="<?php echo $municipio['estatus'] == 1 ? 0 : 1; ?>">
                                <button type="button"
                                  class="btn btn-sm btn-<?php echo $municipio['estatus'] == 1 ? 'warning' : 'success'; ?> btn-confirmar-municipio"
                                  data-id="<?php echo $municipio['id_municipio']; ?>"
                                  data-nombre="<?php echo htmlspecialchars($municipio['nom_municipio']); ?>"
                                  data-estado="<?php echo htmlspecialchars($municipio['nom_estado']); ?>"
                                  data-estatus="<?php echo $municipio['estatus']; ?>"
                                  data-en-uso="<?php echo $en_uso ? '1' : '0'; ?>"
                                  data-conteo-usos="<?php echo $conteo_usos; ?>"
                                  data-accion="<?php echo $municipio['estatus'] == 1 ? 'inhabilitar' : 'habilitar'; ?>"
                                  data-toggle="modal"
                                  data-target="#modalConfirmacionMunicipio">
                                  <i class="fas fa-<?php echo $municipio['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                  <?php echo $municipio['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                                </button>
                              </form>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="8" class="text-center">No hay municipios registrados</td>
                        </tr>
                      <?php endif; ?>