<style type="text/css">
<!--
	table { 
    table-layout: fixed;
    width: 100%;
    border-collapse: collapse;
    font-size:12px;
  }

  td {
    white-space: -o-pre-wrap; 
    word-wrap: break-word;
    white-space: pre-wrap; 
    white-space: -moz-pre-wrap; 
    white-space: -pre-wrap; 
    overflow: hidden;
    max-width: 400px;
  }

-->
</style>
<page>
<div class="notas-competencia">
	<div class="box box-color box-bordered">
		<table border="1" >
			<tr>
				<td><b>SEDE:</b> <?php echo $sedeByID['NOMBRE']; ?></td>
				<td><b>GRADO:</b> <?php echo $cursoByID['NOMBRE']; ?></td>
				<td><b>AREA:</b> <?php echo $areaByID['NOMBRE']; ?></td>
			</tr>
		
		</table>
    <b>COMPETENCIA:</b> <?php echo $competencia['COMPETENCIA']; ?>
	</div>
	<br/> 
	<div class="box box-color box-bordered">
	<table border="1"  >
		<thead>
			<tr>
				<td  rowspan="2" class="box-title">COD</td>
				<td  rowspan="2" class="box-title">ESTUDIANTE</td>
				<?php
					foreach($criteriosPorCompetencia as $key=>$value):
				?>
						<td colspan="<?php echo count($criteriosPorCompetencia[$key]); ?>" class="criterio box-title">
							<?php echo $key; ?>
						</td>
				<?php
					endforeach;
				?>
				<td  rowspan="2" class="box-title" >NOTA<br/>FINAL</td>
				<td  rowspan="2" class="box-title" >NOTA<br/>%</td>
				<td  rowspan="2" class="box-title" >DESEMP </td>
			</tr>
			<tr>
				<?php
					foreach($criteriosPorCompetencia as $key=>$value):
						$c=0;
						while(isset($value[$c][0])):
				?>
							<td  class="criterio box-title"  title="<? echo $value[$c]['NOMBRE']; ?>" >[<?php echo $value[$c]['ID']; ?>]<br/><?=$value[$c]['PORCENTAJE']; ?>%</td>
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
			<td style="padding: 0px !important;" >
				<?php echo $estudiantesPorCurso[$e]['CODIGO']; ?>
			</td>
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
              $nota=$notasPorCompetencia[$estudiantesPorCurso[$e]['ID']][$value[$c]['ID']]['NOTA'];
            }else{
              $nota="";
            }
			?>
            <td style="text-align:center;  padding: 0px !important;" >
              <?php echo $nota; ?>
            </td>
			<?php
          $c++;
          endwhile;
	      endforeach;
			?>
				<td id="not_final_<?php echo $estudiantesPorCurso[$e]['ID']; ?>">
					<?php echo $notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['NOTA_FINAL']; ?>
				</td>
				<td id="not_final_porcentaje_<?php echo $estudiantesPorCurso[$e]['ID']; ?>">
					<?php echo $notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['NOTA_PORCENTUAL']; ?>
				</td>
				<td id="not_final_desempenio_<?php echo $estudiantesPorCurso[$e]['ID']; ?>">
					<?php echo $notasFinalesPorCompetencia[$estudiantesPorCurso[$e]['ID']]['DESEMPENIO']; ?>
				</td>
		</tr>
	<?php
		$e++;
		endwhile;
	?>
	</table>
	</div>
</div>
</page>