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
		</h3>
		<input type="hidden" name="formSaraData" value="<?php echo $formSaraDataActionList; ?>">
	</form>


<div class="notas-competencia">
	<div class="box box-color box-bordered">
		<table class="table table-nomargin table-bordered" >
			<tr>
				<td><b>SEDE:</b> <?php echo $sedeByID['NOMBRE']; ?></td>
				<td>
          <a target="_blank" href="<?php echo $formSaraDataPrintLote; ?>">
            <img width="20px" src="/theme/admin/img/Test-paper-128.png" >
            Imprimir Lote
          </a>
          <b>GRADO:</b> <?php echo $cursoByID['NOMBRE']; ?>
          
        </td>
			</tr>
		</table>
	</div>
	<br/>
	<div class="box box-color box-bordered">
	<table class="table table-nomargin table-bordered" >
		<?php
		$e=0;
		while(isset($estudiantesPorCurso[$e][0])):
	?>
		<tr>
            <td class='hidden-480'  style="width: 200px;">
            <span class="h-icons">
              <a href='<?=$formSaraDataBoletin?>&estudiante=<?=$estudiantesPorCurso[$e]['ID']?>' >
                <img width="20px" src="/theme/admin/img/Test-paper-128.png" >Ver
              <a/>
            </span>
            <span class="h-icons">
              <a href='<?=$formSaraDataPrint?>&estudiante=<?=$estudiantesPorCurso[$e]['ID']?>' >
                <img width="20px" src="/theme/admin/img/Test-paper-128.png" >Imprimir
              <a/>
            </span>
             </td>
			<!--td style="padding: 0px !important;" >
				<?php echo $estudiantesPorCurso[$e]['CODIGO']; ?>
			</td-->
			<td style="padding: 0px !important;" >
				<?php echo $estudiantesPorCurso[$e]['APELLIDO']; ?>
				<?php echo $estudiantesPorCurso[$e]['APELLIDO2']; ?>
				<?php echo $estudiantesPorCurso[$e]['NOMBRE']; ?>
				<?php echo $estudiantesPorCurso[$e]['NOMBRE2']; ?>
			</td>
		<tr>
	<?php
		$e++;
		endwhile;
	?>
	</table>
	</div>
</div>
