<div id="main_user">
	<div class="page-header">
		<div class="pull-left">
			<h1></h1>
		</div>
	</div>
	<script>
		$(document).ready(function(){
		  $("#sede").change(function(){
				location.replace("<?=$formSaraDataUrl?>"+"&sede="+$("#sede").val());
		  });
		});
	</script>
	SEDE:
	<select id="sede">
		<option <?=($variable['sede']=="")?"selected":""?> value="" >TODAS</option>
		<? foreach($sedeList as $keySede => $valueSede ) { ?>
		<option <?=($variable['sede']==$valueSede['ID'])?"selected":""?> value="<?=$valueSede['ID']?>" ><?=$valueSede['NOMBRE']?></option>
		<? } ?>
	</select>

	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="icon-table"></i>
						Listado Usuarios
					</h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-nomargin table-bordered dataTable dataTable-tools dataTable-columnfilter">
						<thead>
							<tr class='thefilter'>
								<!--th>Usuario</th-->
								<th>Nombre</th>
								<th class='hidden-350'>Curso</th>
								<th class='hidden-1024'>Identificacion</th>
								<th class='hidden-1024'>Estado</th>
								<th class='hidden-480'></th>	
							</tr>
							<tr>
								<!--th>Usuario</th-->
								<th>Nombre</th>
								<th class='hidden-350'>Grado</th>
								<th class='hidden-1024'>Identificacion</th>  
								<th class='hidden-1024'>Estado</th>
								<th class='hidden-480'>Ver</th>
							</tr>
						</thead>
						<tbody>
					
							<?php
						
							foreach($userList as $key=>$value){
							
								//$link=$this->getUrlLinksbyId($userList[$i]['ID']);
															

							?>	
							<tr>
								<!--td><?=$value['USUARIO']?></td-->
								<td><?=$value['NOMBRE']?></td>
								<td><?=isset($userListbyCourse[$key])?$courseList[$userListbyCourse[$key]['IDCOURSE']]['NAMECOURSE']:''?></td>
								<td ><?=$value['IDENT']?></td>
								<td >
                  
                  <?php if($value['ESTADO']==1): ?> <span class='label label-satgreen'>Activo</span> <?php endif; ?>
                  <?php if($value['ESTADO']==2): ?> <span class='label label-lightred'>Retirado</span> <?php endif; ?>
                  <?php if($value['ESTADO']==3): ?> <span class='label label-lightred'>Desertor</span> <?php endif; ?>
                  <?php if($value['ESTADO']==4): ?> <span class='label label-lightblue'>Trasladado</span> <?php endif; ?>
                  <?php if($value['ESTADO']==5): ?> <span class='label label-lightyellow'>Matriculado</span> <?php endif; ?>
                  
                </td>
								<td >
									<a style="cursor:pointer" href="<?=$formSaraDataEdit."&optionValue=".$key?>" class="btn"><i class="icon-edit"></i></a>  
                  <?php
                    /*if ($this->rol == 1):
                  ?>
                  <a style="cursor:pointer" onclick="if (confirm('Esta seguro de eliminar este usuario?, esta operacion no se puede deshacer')){ location.replace('<?=$formSaraDataDelete."&optionValue=".$key?>')  }"  class="btn" rel="tooltip" title="Eliminar"><i class="icon-remove"></i></a>
                  <?php
                    endif;*/
                  ?>
                </td>
							</tr>
							<?php
						
							}
							?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

