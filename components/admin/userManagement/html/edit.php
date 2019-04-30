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
									<h3><i class="icon-edit"></i> Detalle Usuario</h3>
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
											<label for="textfield" class="control-label">Sede</label>
											<div class="controls">
												<div class="input-prepend">
													<select name="usersede">
													
															<option <?=(""==$userDataByID['IDSEDE'])?"selected":""?> value="" >NINGUNA</option>
															
														<?  $s=0;
																
															while(isset($sedeList[$s][0])){?> 
																<option <?=($sedeList[$s]['ID']==$userDataByID['IDSEDE'])?"selected":""?> value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>	
													</select>
												</div>
											</div>
										</div> 
										 
										<div class="control-group">
											<label for="textfield" class="control-label">Usuario</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="userid" type="hidden" value="<?=$userDataByID['USUARIO']?>" />
													<input type="text" value="<?=$userDataByID['USUARIO']?>" disabled="true" >
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
										<div class="control-group">
											<label for="textfield" class="control-label">Rol</label>
											<div class="controls">
												<div class="check-demo-col">
														
													<?php
													$rolValues=explode(",",$userDataByID['ROL']);
													foreach($roleList as $name=>$value){
														$checked="";
														if(in_array($value['ROL'],$rolValues) !== FALSE) {
															$checked="checked";
														}
													?>
														<div class="check-line">
															<input type="checkbox" name="role[]" value="<?=$value['ID']?>" id="c5" class='icheck-me' data-skin="square" data-color="blue" <?=$checked?> > 
															<label class='inline' for="c5"><?=$value['ROL']?></label>
														</div>
													<?php
													}
													?>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label">Cursos</label>
											<div class="controls">
													<?php
													$sedeValues=explode(",",$userDataByID['SEDE']);
													foreach($courseList as $key=>$value){
														
													?>	<br/>
														<?php echo $key; ?>
														<hr/>
														<br/>
														
														<? 
														 
															foreach($value as $name=>$data){ 
														
															$checked="";
															
															if(in_array($data['IDCOURSE'],$courseByUser) !== FALSE) {
																$checked="checked";
															}
														?>

															<div class="check-line" style="width: 200px !important;display: block; float: left;">
																<input type="checkbox" name="course[]" value="<?=$data['IDCOURSE']?>" class='icheck-me' data-skin="square" data-color="blue" <?=$checked?> > 
																<label class='inline' for="c5"><?=$data['NAMECOURSE']?></label>
															</div>
														<? } ?>
														
														<div style="clear:both" ></div>
													<?php
													}
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
