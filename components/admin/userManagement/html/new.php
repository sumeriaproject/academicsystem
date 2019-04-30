<div id="main_user">
		<div class="row-fluid">
						<div class="span12">
							<div class="box box-bordered">
								<div class="box-title">
									<h3><i class="icon-edit"></i> Crear Usuario</h3>
								</div>
								<div class="box-content nopadding">

									<form action="index.php" method="POST" class='form-horizontal form-bordered form-validate' novalidate="novalidate">

                    <div class="control-group error">
											<label for="textfield" class="control-label">Identificacion</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="identificacion" type="text" >
												</div>
												<span class="help-block">

												</span>
											</div>
										</div>

										<div class="control-group  error">
											<label for="textfield" class="control-label">Nombre</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="nombre" type="text" value="" placeholder="Primer Nombre">
												</div>
												<span class="help-block"></span>
											</div>
										</div>
										<div class="control-group  error">
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
													<input name="apellido"  value="" type="text" placeholder="Primer Apellido">
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
											<label for="textfield" class="control-label">Sede</label>
											<div class="controls">
												<div class="input-prepend">
													<select name="usersede">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
											</div>
										</div>
										<!--div class="control-group error">
											<label for="textfield" class="control-label">Usuario</label>
											<div class="controls">
												<div class="input-prepend">

													<input name="usuario" data-rule-required="true" data-rule-number="true" value="" type="text" placeholder="Número sin letras ni espacios">
												</div>
												<span class="help-block error" for="numberfield">Este codigo será el identificador permanente del usuario .</span>
												<span class="help-block">
													Sugerencia: Estudiante: CODIGOSEDE - DIGITO5CIFRAS (XXX-XXXXX) Docente: Número de Cédula
													<br/>Códigos Sugeridos:
													<?php
													foreach($codes as $k=>$v){
															echo "[".$k.":".$v."]";
													}
													?>
												</span>
											</div>
										</div-->
                    <!--div class="control-group error">
											<label for="textfield" class="control-label">NUI</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="nui" data-rule-required="true" value="" type="text" placeholder="Número único de identificación">
												</div>
												<span class="help-block error" for="numberfield">NUI SIMAT</span>
											</div>
										</div-->
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
										<div class="control-group error">
											<label for="textfield" class="control-label">Rol</label>
											<div class="controls">
												<div class="check-demo-col">

													<?php
													foreach($roleList as $name=>$value){

													?>
														<div class="check-line error">
															<input type="checkbox" name="role[]" value="<?=$value['ID']?>" id="c5" class='icheck-me' data-skin="square" data-color="blue" >
															<label class='inline' for="c5"><?=$value['ROL']?></label>
														</div>
													<?php
													}
													?>
												</div>
												<span class="help-block error" for="numberfield">Este campo es obligatorio .</span>
											</div>

										</div>
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
