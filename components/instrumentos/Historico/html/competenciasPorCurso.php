<br/>

<div id="main_user">
  <div class="box box-color box-bordered">
		<table class="table table-nomargin table-bordered" >
			<tr>
				<td style="border: 1px solid #CCC !important;"><b>ESTUDIANTE:</b> 
        	<?php echo $estudianteByID[0]['APELLIDO']; ?>
          <?php echo $estudianteByID[0]['APELLIDO2']; ?>
          <?php echo $estudianteByID[0]['NOMBRE']; ?>
          <?php echo $estudianteByID[0]['NOMBRE2']; ?> 
        </td>
				<td style="border: 1px solid #CCC !important;"><b>SEDE:</b> <?php echo $sedeByID['NOMBRE']; ?></td>
				<td style="border: 1px solid #CCC !important;"><b>GRADO:</b> <?php echo $cursoByID['NOMBRE']; ?></td> 
			</tr>
		</table>
	</div>
   
	<?php
	$a=0;
	while(isset($areas[$a][0])):
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-content nopadding">
					<table class="table table-nomargin table-bordered">
						<thead>
							<tr class='thefilter'>
								<th>AREA: <?=$areas[$a]['AREA']?></th>
								<th>(D)</th>
								<th>(V)</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$c=0;
							while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0])): ?>
								<tr>
									<td style="padding:5px"><b  style="font-weight: bold;">Competencia <?php echo $competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR']; ?>: </b><?php echo $competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']; ?></td>
									<td class='hidden-480'  style="width:50px; padding:5px">
										<?php echo $notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["DESEMPENIO"]; ?>
									</td>
                  <td class='hidden-480'  style="width:50px; padding:5px">
										<?php echo $notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"]; ?>
									</td>
								</tr>
							<?php
							$c++;
							endwhile;
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php
	$a++;
	endwhile;
	?>
</div>

