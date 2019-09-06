<div id="main_user">
	<?php if($id_grado <> 1) { ?>
		<a target="_blank" class="btn btn-primary" href="<?php echo $formSaraDataPrint; ?>">Imprimir Resumen</a>
	<?php } ?>

	<form>
		<h3>
			SEDE
			<select name="sede" onchange="this.form.submit()" >
				<?php foreach($sedes as $key=>$value): ?>
					<option <?=($key == $id_sede)?"selected":""?> value="<?php echo $key; ?>" >
						<?php echo $value['NOMBRE']; ?>
					</option>
				<?php endforeach; ?>
			</select>
			GRADO
			<select name="grado" onchange="this.form.submit()" >
				<?php foreach($grados as $key=>$value): ?>
					<option  <?=($key == $id_grado)?"selected":""?> value="<?php echo $key; ?>" >
						<?php echo $value['NOMBRE']; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</h3>
		<input type="hidden" name="formSaraData" value="<?php echo $formSaraDataActionList; ?>">
	</form>
  
  <?php 
    $c=0;
    if(count($cursos) > 1) {
      while(isset($cursos[$c][0])){
  ?>    
      <a class="btn <?=($cursos[$c]['ID']==$id_curso)?'btn-warning':''?>" href="<?=$formSaraDataList."&curso=".$cursos[$c]['ID']?>" ><?=$cursos[$c]['NAME']?></a>
  <?php  
      $c++;
      }
    }  
  ?>
  
  
	<?php
  if(empty($mensajeCierre)):
	$a=0;
	while(isset($areas[$a][0])):
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<?=$areas[$a]['AREA']?>
					</h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-nomargin table-bordered">
						<thead>
							<tr>
								<th class='small-columns' >ID</th>
								<th>Competencia</th>
								<th></th>
							</tr>
							<tr>
							</tr>
						</thead>
						<tbody>
							<?php
							$c=0;
							while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0])): ?>
								<tr>
									<td class='small-columns' ><?php echo $competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR']; ?></td>
									<td><?php echo $competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']; ?></td>
									<td style="width: 200px;">
										<span class="h-icons">
											<a href='<?=$formSaraDataCurso?>&competencia=<?=$competenciasPorArea[$areas[$a]['ID']][$c]['ID']?>' >
												<img width="30px" src="/theme/admin/img/Test-paper-128.png" >Cargar Notas
											<a/>
										</span>
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
  else:
 	?>
    <div class="alert alert-info">
      <?php echo $mensajeCierre; ?>
    </div>
    
  <?php
  endif;
 	?>  
</div>

