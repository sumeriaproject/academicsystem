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
			</table>
		</div>
		<br/>
		<div class="box box-color box-bordered">
		<table class="table table-nomargin table-bordered" >
			<thead>
				<tr>
					<td class="box-title">ESTUDIANTE</td>
					<td class="box-title" >NOTA<br/>FINAL</td>
				</tr>
			</thead>
		<?php
			$e = 0;
			while(isset($estudiantesPorCurso[$e][0])):
		?>
			<tr>
				<td style="padding: 0px !important;" >
					<?php echo $estudiantesPorCurso[$e]['APELLIDO']; ?>
					<?php echo $estudiantesPorCurso[$e]['APELLIDO2']; ?>
					<?php echo $estudiantesPorCurso[$e]['NOMBRE']; ?>
					<?php echo $estudiantesPorCurso[$e]['NOMBRE2']; ?>
				</td>
				<td id="not_final_<?php echo $estudiantesPorCurso[$e]['ID']; ?>">

					<?php if(!isset($_REQUEST['print'])) { ?>

						<select class="<?=$estudiantesPorCurso[$e]['ID']; ?>"  onchange="actualizarNotaCualitativa('<?=$formSaraDataNota; ?>',$(this))" >
								<option value="" ></option>
						<?php
							foreach($cualitativasPorCompetencia as $value):

								if(isset($notasPorCompetencia[$estudiantesPorCurso[$e]['ID']]) && $notasPorCompetencia[$estudiantesPorCurso[$e]['ID']]['NOTA'] == $value['ID'] ) {
									$selected = 'selected';
								}else {
									$selected = '';
								}
						?>
								<option <?=$selected?> value="<?php echo $value['ID'] ?>" ><?php echo $value['ABREVIACION'] ?></option>
						<?php
							endforeach;
						?>
						</select>
					<?php } else { 	
						if(isset($notasPorCompetencia[$estudiantesPorCurso[$e]['ID']])) {
							echo $cualitativasPorCompetencia[$notasPorCompetencia[$estudiantesPorCurso[$e]['ID']]['NOTA']]['ABREVIACION'];
						}
					 } ?>	
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
