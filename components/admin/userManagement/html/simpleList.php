<?php 
foreach($access as $sede => $curso):
foreach($curso as $key => $value):

?>
<div id="main_user">
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title"> 
					<h3>
						<?php echo $value["SEDE_NOMBRE"]; ?> - Grado: <?php echo $value["GRADO_NOMBRE_LETRA"]; ?>
					</h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-nomargin table-bordered ">
						<thead>

							<tr>
								<th>Código</th>
								<th>Identificación</th>  
								<th>Nombre</th>
								<th class='hidden-480'>Ver</th>
							</tr>
						</thead>
						<tbody>
					
							<?php
              if(is_array($userAllList[$value["SEDE_ID"]][$value["CURSO_ID"]])):
                foreach($userAllList[$value["SEDE_ID"]][$value["CURSO_ID"]] as $datos):
							?>	
                  <tr>
                    <td><?=$datos['USUARIO']?></td>
                    <td><?=$datos['IDENT']?></td>
                    <td><?=$datos['APELLIDO']?> <?=$datos['APELLIDO2']?> <?=$datos['NOMBRE']?> <?=$datos['NOMBRE2']?> </td>
                    <td class='hidden-480'>
                      <a style="cursor:pointer" href="<?=$formSaraDataEdit."&optionValue=".$datos['USUARIO']?>" class="btn" rel="tooltip" title="Editar"><i class="icon-edit"></i></a>  
                    </td>
                  </tr>
							<?php
								endforeach;
              endif;
							?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
endforeach;
endforeach;
?>
