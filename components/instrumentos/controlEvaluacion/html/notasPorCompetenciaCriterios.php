<div class="<?=isset($_REQUEST['print'])?'print':''?>" >
	<?php if(!isset($_REQUEST['print'])) { ?>
		<a href="<?=$formSaraDataUrl?>" type="submit" class="btn btn-inverse disabled">
			<i class="icon-white icon-arrow-left"></i>
			Regresar
		</a>
		<a class="btn btn-primary" target="_blank" href="<?php echo $formSaraDataXML; ?>" >Imprimir</a>
	<?php } ?>

	<div class="notas-competencia">
		<div class="box box-color box-bordered">
			<table class="table table-nomargin table-bordered" >
				<tr>
					<td><b>SEDE:</b> <?php echo $sedeByID['NOMBRE']; ?></td>
					<td><b>GRADO:</b> <?php echo $cursoByID['NOMBRE']; ?></td>
					<td><b>AREA:</b> <?php echo $areaByID['NOMBRE']; ?></td>
				</tr>
				<tr>
					<td colspan="3"><b>COMPETENCIA:</b> <?php echo $competencia['COMPETENCIA']; ?></td>
				</tr>
				<tr>
					<td class="label-basico" >BASICO</td>
					<td class="label-alto" >ALTO</td>
					<td class="label-superior" >SUPERIOR</td>
				</tr>
	      <tr>
					<td><?php echo $competencia['BASICO']; ?></td>
					<td><?php echo $competencia['ALTO']; ?></td>
					<td><?php echo $competencia['SUPERIOR']; ?></td>
				</tr>
			</table>
		</div>
		<br/>
		<div class="box box-color box-bordered">
		<table class="table table-nomargin table-bordered" >
			<thead>
				<tr>
					<!--td  rowspan="2" class="box-title">COD</td-->
					<td rowspan="2" class="box-title">ESTUDIANTE</td>
					<?php
						foreach($criteriosPorCompetencia as $key=>$value):
					?>
							<td colspan="<?php echo count($criteriosPorCompetencia[$key]); ?>" class="criterio box-title">
								<?php echo $key; ?>
							</td>
					<?php
						endforeach;
					?>
					<td rowspan="2" class="box-title" >NOTA<br/>FINAL</td>
					<td rowspan="2" class="box-title" >NOTA<br/>%</td>
					<td rowspan="2" class="box-title" >DESEMP </td>
				</tr>
				<tr>
					<?php
						foreach($criteriosPorCompetencia as $key=>$value):
							$c=0;
							while(isset($value[$c][0])):
					?>
								<td class="criterio box-title"  title="<?php echo $value[$c]['NOMBRE']; ?>" >[<?php echo $value[$c]['ID']; ?>]<br/><?=$value[$c]['PORCENTAJE']; ?>%</td>
					<?php
							$c++;
							endwhile;
						endforeach;
					?>
				</tr>
			</thead>
		<?php
			$e=0;
			while(isset($estudiantesPorCurso[$e][0])):
		?>
			<tr>
				<!--td style="padding: 0px !important;" >
					<?php echo $estudiantesPorCurso[$e]['CODIGO']; ?>
				</td-->
				<td style="padding: 0px !important;" >
					<?php echo $estudiantesPorCurso[$e]['APELLIDO']; ?>
					<?php echo $estudiantesPorCurso[$e]['APELLIDO2']; ?>
					<?php echo $estudiantesPorCurso[$e]['NOMBRE']; ?>
					<?php echo $estudiantesPorCurso[$e]['NOMBRE2']; ?>
				</td>
				<?php
					foreach($criteriosPorCompetencia as $key=>$value):
	          $c=0;
	          while(isset($value[$c][0])):

	            if(isset($notasPorCompetencia[$estudiantesPorCurso[$e]['ID']][$value[$c]['ID']]['NOTA'])){
	              $nota = $notasPorCompetencia[$estudiantesPorCurso[$e]['ID']][$value[$c]['ID']]['NOTA'];
	            }else{
	              $nota = "";
	            }
				?>
	            <td style="text-align:center;  padding: 0px !important;" >
	              <input porcentaje="<?=$value[$c]['PORCENTAJE']; ?>" onchange="actualizarNotaCriterio('<?=$formSaraDataNota; ?>',$(this))" class="<?=$estudiantesPorCurso[$e]['ID']; ?>"  id="<?=$value[$c]['ID']; ?>" type="text" style="text-align:center; width:30px; height:30px; padding:0px; margin:0px" value="<?=$nota; ?>" >
	            </td>
				<?php
	          $c++;
	          endwhile;
		      endforeach;
				?>
					<td id="not_final_<?php echo $estudiantesPorCurso[$e]['ID']; ?>">
						<?php 
							if(isset($notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['NOTA_FINAL'])) {
								echo $notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['NOTA_FINAL']; 
							}	
						?>
					</td>
					<td id="not_final_porcentaje_<?php echo $estudiantesPorCurso[$e]['ID']; ?>">
						<?php 
							if(isset($notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['NOTA_PORCENTUAL'])) {
								echo $notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['NOTA_PORCENTUAL']; 
							}	
						?>
					</td>
					<td id="not_final_desempenio_<?php echo $estudiantesPorCurso[$e]['ID']; ?>">
						<?php 
							if(isset($notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['DESEMPENIO'])) {
								echo $notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['DESEMPENIO']; 
							}	
						?>
					</td>
			</tr>
		<?php
			$e++;
			endwhile;
		?>
		</table>
		</div>
	</div>
</div>