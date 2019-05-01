<div id="main_user">
		<div class="page-header">
					<div class="pull-left">
						<h1></h1>
					</div>
		</div>


		<div class="row-fluid">
						<div class="span12">
							<div class="box box-bordered">
               				 	<a href="<?=$formSaraDataEdit."&enroll=true"?>" class="btn btn-primary" >
                					<i class="icon-pencil"></i>
                					FORMATO DE MATRICULA
                				</a> 


								<div class="box-title">
									<h3><i class="icon-edit"></i> EDITAR ESTUDIANTE</h3>
								</div>
								<div class="box-content nopadding">
                           

									<form action="index.php" method="POST" class='form-horizontal form-bordered'>

                    <div class="control-group">
											<label for="textfield" class="control-label">Estado</label>
											<div class="controls">
												<div class="input-prepend">
													<select name="estado">
														<option <?=($userDataByID['ESTADO']==1)?"selected":""?> value="1" >ACTIVO</option>
														<option <?=($userDataByID['ESTADO']==2)?"selected":""?> value="2" >RETIRADO</option>
														<option <?=($userDataByID['ESTADO']==3)?"selected":""?> value="3" >DESERTOR</option>
														<option <?=($userDataByID['ESTADO']==4)?"selected":""?> value="4" >TRASLADADO</option>
														<option <?=($userDataByID['ESTADO']==5)?"selected":""?> value="5" >MATRICULADO</option>
													</select>
												</div>
											</div>
										</div>

                    <div class="control-group">
											<label for="textfield" class="control-label">Identificacion</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="identificacion" type="text" value="<?=$userDataByID['IDENT']?>">
												</div>
												<span class="help-block">

												</span>
											</div>
										</div>

										<div class="control-group">
											<label for="textfield" class="control-label">Nombre</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="nombre" type="text" value="<?=$userDataByID['NOMBRE']?>" >
												</div>
												<span class="help-block">

												</span>
											</div>
										</div>

										<div class="control-group">
											<label for="textfield" class="control-label">Nombre 2</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="nombre2" type="text" value="<?=$userDataByID['NOMBRE2']?>">
												</div>
												<span class="help-block">

												</span>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Apellido</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="apellido"  value="<?=$userDataByID['APELLIDO']?>" type="text" >
												</div>
												<span class="help-block">

												</span>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Apellido 2</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="apellido2"  value="<?=$userDataByID['APELLIDO2']?>" type="text" >
												</div>
												<span class="help-block">

												</span>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Correo</label>
											<div class="controls">
												<div class="input-prepend">
													<span class="add-on">@</span>
													<input name="email"  value="<?=$userDataByID['CORREO']?>" type="text" placeholder="Correo">
												</div>
											</div>
										</div>

										<div class="control-group">
											<label for="textfield" class="control-label">Usuario</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="userid" type="hidden" value="<?=$userDataByID['ID']?>" />
													<input type="text" value="<?=$userDataByID['ID']?>" disabled="true" >
												</div>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Clave</label>
											<div class="controls">
												<div class="input-append">
													<input name="password" type="text" placeholder="Secret key" class='input-medium'>
													<span class="add-on"><i class="icon-key"></i></span>
												</div>
												<div class="input-append">
													<input name="passwordc" type="text" placeholder="Secret key" class='input-medium'>
													<span class="add-on"><i class="icon-key"></i></span>
												</div>
												<span class="help-block">
													Deja los campos en blanco si no quieres actualizar la clave
												</span>
											</div>

										</div>
										
										<input type="hidden" name="role[]" value="3" id="c5" class='icheck-me' data-skin="square" data-color="blue" >
									
										<div class="control-group">
											<label for="textfield" class="control-label">NUI</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="nui" value="<?=$userDataByID['NUI']?>" type="text">
												</div>
												<span class="help-block" for="numberfield">NUI SIMAT</span>
											</div>
										</div>

										<div class="control-group">
											<label for="textfield" class="control-label">Cursos</label>
											<div class="controls">
													<?php
													$sedeValues=explode(",",$userDataByID['SEDE']);
													foreach($courseList as $key=>$value):

													?>	<br/>
														<?php echo $key; ?>
														<hr/>
														<br/>

														<?php

															foreach($value as $name=>$data):

															$checked="";

															if(in_array($data['IDCOURSE'],$courseByUser) !== FALSE) {
																$checked="checked";
															}
														?>

															<div class="check-line" style="width: 200px !important;display: block; float: left;">
																<input type="radio" name="course" value="<?=$data['IDCOURSE']?>" class='icheck-me' data-skin="square" data-color="blue" <?=$checked?> >
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
											<button type="submit" class="btn btn-primary">Guardar Cambios</button>
											<button type="button" class="btn">Cancel</button>
										</div>

										<input type='hidden' name='formSaraData' value="<?=$formSaraData?>">

									</form>
								</div>
							</div>
						</div>
					</div>

</div>
