
<br/> 
<div id="main_user"> 

	<?php  foreach($grados as $key=>$value){ 
		$class = ($value['NOMBRENUMERO'] == $grados[$id_grado]['NOMBRENUMERO']) ? 'btn-warning' : 'btn-primary';
	?>   
		<a class="btn <?=$class?>" href="<?=$formSaraDataList?>&grado=<?=$value['ID']?>" class="course-link"><?=$value['NOMBRENUMERO']?></a>
	<?php  } ?>	
	
	
	<a class="btn btn-success" href="<?=$formSaraDataNew?>" >Crear Competencia</a> 
	<div class="clear"></div>
	
	
	
	<?php 
	$a = 0;
	$upd = 1;
	while(isset($areas[$a][0])){ ?>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						AREA: <?=$areas[$a]['AREA']?>
					</h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-nomargin table-bordered">
						<thead>
							<tr class='thefilter'>
								
								<?php if($id_grado != 1) : ?>
									<th class='small-columns' rowspan='2' >ID</th>
									<th class='small-columns' rowspan='2' >P</th>
									<th rowspan='2' >Competencia</th>
									<th colspan='4' >Desempeño</th>
								<?php else: ?>
									<th class='small-columns' >ID</th>
									<th>P</th>
									<th>Competencia</th>
									<th class='small-columns'></th>
								<?php endif; ?>
							</tr>
							<tr>
								<?php if($id_grado != 1) : ?>
									<th>Basico</th>  
									<th>Alto</th>
									<th>Superior</th>
									<th class='small-columns'></th>
								<?php endif; ?>	
							</tr>
						</thead>
						<tbody>
							
							<?php 
							$c=0;
							while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0])){?> 
								<tr class="<?=($competenciasPorArea[$areas[$a]['ID']][$c]['ESTADO'] == 0)?'disabled':''?>" >
									<td class='small-columns' ><?=$competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR']?></td> 
									<td class='small-columns' ><?=$competenciasPorArea[$areas[$a]['ID']][$c]['PERIODO']?></td>
									<td><?=$competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']?></td>
									<?php if($id_grado != 1) : ?>
										<td><?=$competenciasPorArea[$areas[$a]['ID']][$c]['BASICO']?></td>
										<td><?=$competenciasPorArea[$areas[$a]['ID']][$c]['ALTO']?></td>
										<td><?=$competenciasPorArea[$areas[$a]['ID']][$c]['SUPERIOR']?></td> 
									<?php endif; ?>	
									<td class='small-columns'>
										<a style="cursor:pointer" href="<?=$formSaraDataEdit."&optionValue=".$competenciasPorArea[$areas[$a]['ID']][$c]['ID']?>" class="btn" rel="tooltip" title="Editar"><i class="icon-edit"></i></a>  
									</td>
								</tr>
							<?php 
							$c++;
							} 
							?>
						</tbody>
						
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php
	$a++;
	} ?>
</div>

