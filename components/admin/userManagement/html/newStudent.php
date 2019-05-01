<div id="main_user">
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-bordered">
				<div class="box-title">
					<h3><i class="icon-edit"></i> Crear Estudiante</h3>
				</div>
				<div class="box-content nopadding">

					<form action="index.php" method="POST" class='form-horizontal form-bordered form-validate'>
    
    					<div class="control-group">
							<label for="textfield" class="control-label">Estado</label>
							<div class="controls">
								<div class="input-prepend">
									<select name="estado">
										<option value="1" >ACTIVO</option>
										<option value="2" >RETIRADO</option>
										<option value="3" >DESERTOR</option>
										<option value="4" >TRASLADADO</option>
										<option value="5" selected >MATRICULADO</option>
									</select>
								</div>
							</div>
						</div>
   						<div class="control-group ">
							<label for="textfield" class="control-label">Identificacion</label>
							<div class="controls">
								<div class="input-prepend">
									<input name="identificacion" type="text" required >
								</div>
								<span class="help-block">

								</span>
							</div>
						</div>

						<div class="control-group ">
							<label for="textfield" class="control-label">Nombre</label>
							<div class="controls">
								<div class="input-prepend">
									<input name="nombre" type="text" value="" placeholder="Primer Nombre" required>
								</div>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="control-group ">
							<label for="textfield" class="control-label">Nombre 2</label>
							<div class="controls">
								<div class="input-prepend">
									<input name="nombre2" type="text" value="" placeholder="Segundo Nombre">
								</div>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="control-group">
							<label for="textfield" class="control-label">Apellido</label>
							<div class="controls">
								<div class="input-prepend">
									<input name="apellido" required value="" type="text" placeholder="Primer Apellido">
								</div>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="control-group">
							<label for="textfield" class="control-label">Apellido 2</label>
							<div class="controls">
								<div class="input-prepend">
									<input name="apellido2"  value="" type="text" placeholder="Segundo Apellido">
								</div>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="control-group">
							<label for="textfield" class="control-label">Correo</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on">@</span>
									<input name="email"  value="" type="text" placeholder="Correo">
								</div>
							</div>
						</div>
    					<div class="control-group">
							<label for="textfield" class="control-label">NUI</label>
							<div class="controls">
								<div class="input-prepend">
									<input name="nui" value="" type="text">
								</div>
								<span class="help-block" for="numberfield">NUI SIMAT</span>
							</div>
						</div>
						
						<input type="hidden" name="role[]" value="3" id="c5" class='icheck-me' data-skin="square" data-color="blue" >

						<div class="control-group">
							<label for="textfield" class="control-label">Cursos</label>
							<div class="controls">
								<?php
								foreach($courseList as $key=>$value):

								?>	<br/>
									<?=$key?>
									<hr/>
									<br/>

									<?php
										foreach($value as $name=>$data):
									?>
										<div class="check-line" style="width: 200px !important;display: block; float: left;">
											<input type="checkbox" name="course[]" value="<?=$data['IDCOURSE']?>" class='icheck-me' data-skin="square" data-color="blue" >
											<label class='inline' for="c5"><?=$data['NAMECOURSE']?></label>
										</div>
									<?php endforeach; ?>
									<div style="clear:both" ></div>
								<?php
								endforeach;
								?>
							</div>

						</div>

						<div class="form-actions">
							<input class="btn btn-primary" type="submit" value="Guardar Cambios">
							<button type="button" class="btn">Cancel</button>
						</div>

						<input type='hidden' name='formSaraData' value="<?=$formSaraData?>">

					</form>
				</div>
			</div>
		</div>
	</div>
</div>
