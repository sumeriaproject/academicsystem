<br/>
<?php if(isset($_REQUEST['anio'])): ?>
  <h3>HISTORICO <?php echo $_REQUEST['anio']; ?></h3>
<?php endif; ?>

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
		foreach ($areasPorTipo as $tipo => $areas) {
			if($tipo == 'CRITERIOS') {
				include_once($this->ruta."/html/areasTipoCriterio.php");
			}else if ($tipo == 'CUALITATIVA') {
				include_once($this->ruta."/html/areasTipoCualitativa.php");
			}
		}
	?>

	
</div>

