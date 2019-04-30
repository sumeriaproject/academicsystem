

<div id="main_user">
		<div class="page-header">
					<div class="pull-left">
						<h1>ADMINISTRAR USUARIOS</h1>
					</div>
		</div>
		<div class="row-fluid">
						<div class="span12">
							<div class="box box-bordered">
									<a style="cursor:pointer" onclick="showForm('<?=$link['edit']?>')" class="btn" rel="tooltip" title="Edit"><i class="icon-edit">Editar</i></a>								
									<div class="box-title">
									<h3><i class="icon-edit"></i> Ver Usuario</h3>
									
								</div>
								<div class="box-content nopadding">
									
									<form action="index.php" method="POST" class='form-horizontal form-bordered'>
										
										<div class="control-group">
											<label for="textfield" class="control-label">Nombre</label>
											<div class="controls">
												<?=$userDataByID['NOMBRE']?>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Apellido</label>
											<div class="controls">
												<?=$userDataByID['APELLIDO']?>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Correo</label>
											<div class="controls">
												<?=$userDataByID['CORREO']?>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Rol</label>
											<div class="controls">
												<?=$userDataByID['ROL']?>
											</div>
										</div>
										
										<div class="control-group">
											<label for="textfield" class="control-label">Nivel de Acceso</label>
											<div class="controls">
												
												<?php
												$companyValues=explode(",",$userDataByID['EMPRESA']);
												foreach($companyList as $name=>$value){
												
													if(in_array($value['ID'],$companyValues) !== FALSE) {
														echo $value['NOMBRE'];
													}
											
												}
												?>
										
											</div>
											
										</div>
										

									<input type='hidden' name='formSaraData' value="<?=$formSaraData?>">

									</form>
								</div>
							</div>
						</div>
					</div>
					
</div>
