

<div id="main_user">
		<div class="page-header">
					<div class="pull-left">
						<h1></h1>
					</div>
		</div>
		<div class="row-fluid">
						<div class="span12">
							<div class="box box-bordered">
									<div class="box-title">
									<h3><i class="icon-edit"></i> Mi Usuario</h3>
									
								</div>
								<div class="box-content nopadding">
									
									<form action="index.php" method="POST" class='form-horizontal form-bordered'>
										<div class="control-group">
											<label for="textfield" class="control-label">Código</label>
											<div class="controls">
												<?=$userDataByID['ID']?>
											</div>
										</div>
                    <div class="control-group">
											<label for="textfield" class="control-label">Identificación</label>
											<div class="controls">
												<?=$userDataByID['IDENT']?>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Nombre</label>
											<div class="controls">
												<?=$userDataByID['NOMBRE']?> <?=$userDataByID['NOMBRE2']?>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Apellido</label>
											<div class="controls">
												<?=$userDataByID['APELLIDO']?> <?=$userDataByID['APELLIDO2']?>
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
										
										
										

									<input type='hidden' name='formSaraData' value="<?=$formSaraData?>">

									</form>
								</div>
							</div>
						</div>
					</div>
					
</div>
