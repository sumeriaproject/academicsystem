<br/>
	<form>
		<h3>
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
			AÑO
			<select name="anio" onchange="this.form.submit()" >
				<?php for($a=2015; $a<=2017; $a++): ?>
					<option  <?=($a==$anio)?"selected":""?> value="<?php echo $a; ?>" >
						<?php echo $a; ?>
					</option>
				<?php endfor; ?>
			</select>
		</h3>
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
        <b>Código</b>
      </td>
      <td style="padding: 0px !important;" >
        <b>Estudiante</b>
      </td>
      <td class='hidden-480'  style="width: 200px;">
        <b>Control de notas</b>
        <br/>
        <a target="_blank" style="cursor: default;"href='<?=$formSaraDataPrintControl?>' >
          <img width="20px" src="/theme/admin/img/Test-paper-128.png" >
          Imprimir Resumen
        </a>
      </td>
      <td class='hidden-480'  style="width: 200px;">
        <b>Boletin periodo</b>
      </td>
      <td class='hidden-480'  style="width: 200px;">
        <b>Boletin Final</b>
      </td>
    <tr>

		<?php
		$e=0;
		while(isset($estudiantesPorCurso[$e][0])):
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
      <td class='hidden-480'  style="width: 200px;">
        <span class="h-icons">
          <a style="color:#DDD; pointer-events: none; cursor: default;"href='<?=$formSaraDataBoletin?>&estudiante=<?=$estudiantesPorCurso[$e]['ID']?>' >
            <img width="20px" src="/theme/admin/img/Test-paper-128.png" >Ver
          <a/>
        </span>
        <span class="h-icons">
          <a style="color:#DDD; pointer-events: none; cursor: default;" href='<?=$formSaraDataPrint?>&estudiante=<?=$estudiantesPorCurso[$e]['ID']?>' >
            <img width="20px" src="/theme/admin/img/Test-paper-128.png" >Imprimir
          <a/>
        </span>
      </td>
      <td class='hidden-480'  style="width: 200px;">
        <span class="h-icons">
          <a href='<?=$formSaraDataBoletin?>&estudiante=<?=$estudiantesPorCurso[$e]['ID']?>' >
            <img width="20px" src="/theme/admin/img/Test-paper-128.png" >Ver
          <a/>
        </span>
        <span class="h-icons">
          <a style="color:#DDD; pointer-events: none; cursor: default;"href='<?=$formSaraDataPrint?>&estudiante=<?=$estudiantesPorCurso[$e]['ID']?>' >
            <img width="20px" src="/theme/admin/img/Test-paper-128.png" >Imprimir
          <a/>
        </span>
      </td>
      <td class='hidden-480'  style="width: 200px;">
        <span class="h-icons">
          <a style="color:#DDD; pointer-events: none; cursor: default;" href='<?=$formSaraDataBoletinFinal?>&estudiante=<?=$estudiantesPorCurso[$e]['ID']?>' >
            <img width="20px" src="/theme/admin/img/Test-paper-128.png" >Ver
          <a/>
        </span>
        <span class="h-icons">
          <a target="_blank" style="cursor: default;"href='<?=$formSaraDataBoletinFinal?>&estudiante=<?=$estudiantesPorCurso[$e]['ID']?>' >
            <img width="20px" src="/theme/admin/img/Test-paper-128.png" >Imprimir
          <a/>
        </span>
      </td>
		<tr>
	<?php
		$e++;
		endwhile;
	?>
	</table>
	</div>
</div>
