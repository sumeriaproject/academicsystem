<div id="main_user">
	<div class="page-header">
		<div class="pull-left">
			<h1>ESTABLECIMIENTOS</h1>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="icon-table"></i>
						Consultar Establecimientos
					</h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-hover table-nomargin table-bordered dataTable-columnfilter dataTable">
						<thead>
							<tr class='thefilter'>
								<th>Nombre</th>
								<th>Descripcion</th>
								<th class='hidden-350'>Contacto</th>
								<th class='hidden-480'>Email</th>
								<th class='hidden-480'></th>
							</tr>
							<tr>
								<th>Nombre</th>
								<th>Descripcion</th>
								<th class='hidden-350'>Contacto</th>
								<th class='hidden-480'>Email</th>
								<th class='hidden-480'>Opciones</th>

							</tr>
						</thead>
						<tbody>
					
							<?php
							$i=0;
							while(isset($companyList[$i][0])){

								$link=$this->getUrlLinksbyId($companyList[$i]['IDCOMPANY']);
															

							?>	
							<tr>
								<td><?=$companyList[$i]['NOMBRE']?></td>
								<td><?=$companyList[$i]['DESCRIPCION']?></td>
								<td><?=$companyList[$i]['CONTACTO']?></td>																			
								<td><?=$companyList[$i]['EMAIL']?></td>	
								<td class='hidden-480'>
									<!--a style="cursor:pointer" onclick="showForm('<?=$link['view']?>')" class="btn" rel="tooltip" title="View"><i class="icon-search"></i></a-->
									<a style="cursor:pointer" href="<?=$link['edit']?>" class="btn" rel="tooltip" title="Edit"><i class="icon-edit"></i></a>
									<a style="cursor:pointer" onclick="if (confirm('Esta seguro de eliminar este establecimiento? Esta operacion no se puede deshacer')){ DeleteCompany('<?=$link['delete']?>','<?=$link['postDelete']?>') }"  class="btn" rel="tooltip" title="Delete"><i class="icon-remove"></i></a>
								</td>
							</tr>
							<?php
							$i++;
							}
							?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

