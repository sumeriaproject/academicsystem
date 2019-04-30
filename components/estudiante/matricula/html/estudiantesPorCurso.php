<br/>
	<form>
			SEDE
			<select name="sede" onchange="this.form.submit()" >
				<?php foreach($sedes as $key=>$value): ?>
					<option <?=($key==$id_sede)?"selected":""?> value="<?php echo $key; ?>" >
						<?php echo $value['NOMBRE']; ?>
					</option>
				<?php endforeach; ?>
			</select>
      GRADO
      <select name="grado" onchange="this.form.submit()" >
        <?php foreach($grados as $key=>$value): ?>
          <option  <?=($key==$id_grado)?"selected":""?> value="<?php echo $key; ?>" >
            <?php echo $value['NOMBRE']; ?>
          </option>
        <?php endforeach; ?>
      </select>
			PERIODO
			<select name="periodo" onchange="this.form.submit()" >
				<?php for($a=1; $a<=4; $a++): ?>
					<option  <?=($a==$id_periodo)?"selected":""?> value="<?php echo $a; ?>" >
						<?php echo $a; ?>
					</option>
				<?php endfor; ?>
			</select>
		<input type="hidden" name="formSaraData" value="<?php echo $formSaraDataActionList; ?>">
	</form>


<div class="notas-competencia">
	<div class="box box-color box-bordered">
		<table class="table table-nomargin table-bordered" >
			<tr>
				<td><b>SEDE:</b> <?php echo $sedeByID['NOMBRE']; ?></td>
				<td>
          <b>GRADO:</b> <?php echo $cursoByID['NOMBRE']; ?>

        </td>
			</tr>
		</table>
	</div>
	<br/>
	<div class="box box-color box-bordered">
	<table class="table table-nomargin table-bordered" >
    <tr>

      <td style="padding: 0px !important;" >
        Código
      </td>
      <td style="padding: 0px !important;" >
        Estudiante
      </td>
      <td class='hidden-480'  style="width: 100px;">
        Nota
      </td>
      <td class='hidden-480'  style="width: 500px;">
        Observación
      </td>
    <tr>

		<?php
		$e=0;
		while(isset($estudiantesPorCurso[$e][0])):
      $est = $estudiantesPorCurso[$e]['ID'];
      $notper = "NOT".$id_periodo;
      $obsper = "OBS".$id_periodo;
    ?>
		<tr>

			<td style="padding: 0px !important;" >
				<?php echo $estudiantesPorCurso[$e]['CODIGO']; ?>
			</td>
			<td style="padding: 0px !important;" >
				<?php echo $estudiantesPorCurso[$e]['APELLIDO']; ?>
				<?php echo $estudiantesPorCurso[$e]['APELLIDO2']; ?>
				<?php echo $estudiantesPorCurso[$e]['NOMBRE']; ?>
				<?php echo $estudiantesPorCurso[$e]['NOMBRE2']; ?>
			</td>
      <td class='hidden-480'  style="width: 100px;">
        <input  value="<?=$compEstudiante[$est][$notper]?>" type="text" onchange="actualizarNota('<?=$formSaraDataNota; ?>',$(this))" class="<?=$estudiantesPorCurso[$e]['ID']; ?>"  type="text" style="text-align:center; width:30px; height:30px; padding:0px; margin:0px" />
      </td>
      <td class='hidden-480'  style="width: 500px;">
        <textarea onchange="actualizarObservacion('<?=$formSaraDataNota; ?>',$(this))" class="<?=$estudiantesPorCurso[$e]['ID']; ?>"  style="width:100%;" ><?=trim($compEstudiante[$est][$obsper])?></textarea>
      </td>
		<tr>
	 <?php
		$e++;
		endwhile;
	  ?>
	</table>
	</div>
</div>
