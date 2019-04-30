<div class="container-fluid">

  <div class="box box-color box-bordered">

    <?php  foreach($sedes as $idsede => $sede): ?>

      <div class="box-content nopadding">

        <div class="box-title">
          <h3><?php  echo $sede['NOMBRE']; ?></h3>
        </div>

        <table class="table table-nomargin table-bordered">
            <tr class="thefilter">
              <th>GRADO</th>
              <th># COMPETENCIAS <br/>POR CURSO</th>
              <th># ESTUDIANTES </th>
              <th># ESTUDIANTES <br/>AL DIA </th>
              <th># ESTUDIANTES <br/>CON  COMPETENCIAS <br/> PENDIENTES</th>
              <th># ESTUDIANTES <br/>SIN NOTAS</th>
              <th></th>
            </tr>

          <?php  foreach($courses[$idsede] as $idcourse => $course): ?>

            <tr>
              <td><?php echo $course["CURSO_NOMBRE"]; ?></td>
              <td><?php echo count($competencias[$course["GRADO_ID"]]) ?></td>
              <td><?php echo $a = isset($estudiantes[$idcourse])?count($estudiantes[$idcourse]):0; ?></td>
              <td><?php echo $b = count($course["ESTUDIANTES_ALDIA"]); ?></td>
              <td><?php echo $c = count($course["ESTUDIANTES_PENDIENTES"]); ?></td></td>
              <td><?php echo ($a-$b-$c); ?></td>
              <td style="width: 110px;">
                <a title="Ver Notas" href="<?php echo $course["LINK_NOTAS"];?>" target="_blank">
                  <img title="Ver Notas" src="/theme/admin/img/search109.png">
                </a>

              <?php if($this->controlAcceso->rol == 1 ): ?>
                <?php if(!$course["REPEAT_CIERRE"]): ?>
                <a title="Cerrar A&ntilde;o" href="<?php echo $course['LINK_CIERRE'];?>" target="_blank">
                  <img title="Cerrar A&ntilde;o" src="/theme/admin/img/tool685.png">
                </a>
                <?php else: ?>
                <a title="Cerrar A&ntilde;o" href="<?php echo $course['LINK_CIERRE'];?>" target="_blank">
                  <img title="Cerrar A&ntilde;o" src="/theme/admin/img/tooldis.png">
                </a>
                <?php endif; ?>
              <?php endif; ?>

                <a title="Imprimir Boletines"  href="<?php echo $course["LINK_BOLETIN"];?>" target="_blank">
                  <img title="Imprimir Boletines" src="/theme/admin/img/boletin50.png">
                </a>
              </td>
            </tr>

          <?php  endforeach; ?>
        </table>
      </div>
    <?php  endforeach; ?>
  </div>

</div>











			</div>

