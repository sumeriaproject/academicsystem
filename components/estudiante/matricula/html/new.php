<div id="main_user">
		<div class="row-fluid">
						<div class="span12">
							<div class="box box-bordered">
								<center>
                <div class="box-title">
                  <h3>FORMATO DE MATRICULA DE ESTUDIANTES</h3>
                </div>
                </center>
                  
								<div class="box-content nopadding">
                  
									<form action="index.php" method="POST" class='form-horizontal form-bordered form-validate' novalidate="novalidate">
                    
                  <div class="box-title">
                    <h3><i class="icon-edit"></i>DATOS DE IDENTIFICACI&Oacute;N</h3>
                  </div>
                  
                    <div class="control-group error">
											<label for="textfield" class="control-label">Tipo de Estudiante</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="tipo_estudiante" value="N" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">NUEVO</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_estudiante" value="A" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">ANTIGUO</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div>
                 
 
                    <div class="control-group error">
											<label for="textfield" class="control-label">Tipo de Identificaci&oacute;n</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="tipo_documento" value="CC" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">CC</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_documento" value="RC" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">RC</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_documento" value="TI" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">TI</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_documento" value="CE" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">CE</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div>                 
                    
                    
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">N&uacutemero de Identificaci&oacute;n</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="identificacion" type="text" >
												</div>
												<span class="help-block">

												</span>
											</div>
										</div>
                    
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Lugar de Expedici&oacute;n</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DEPARTAMENTO</label>
													<select name="dept_exp_doc">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="munc_exp_doc">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
											</div>
										</div>

										<div class="control-group">
											<label for="textfield" class="control-label">Nombres</label>
											<div class="controls">
                        <div class="input-prepend">
													<input name="nombre" type="text" value="">
                          <label class='inline' for="c5">PRIMER NOMBRE</label>
												</div>
												<div class="input-prepend">
													<input name="nombre2" type="text" value="">
                          <label class='inline' for="c5">SEGUNDO NOMBRE</label>
												</div>
												<span class="help-block"></span>
											</div>
										</div>


										<div class="control-group">
											<label for="textfield" class="control-label">Apellidos</label>
											<div class="controls">
                        <div class="input-prepend">
													<input name="apellido" type="text" value="">
                          <label class='inline' for="c5">PRIMER APELLIDO</label>
												</div>
												<div class="input-prepend">
													<input name="apellido2" type="text" value="">
                          <label class='inline' for="c5">SEGUNDO APELLIDO</label>
												</div>
												<span class="help-block"></span>
											</div>
										</div>                    
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">G&eacute;nero</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="genero" value="M" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">MASCULINO</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="genero" value="F" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">FEMENINO</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Fecha de Nacimiento</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="fecha_nacimiento"  value="" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Lugar de Nacimiento</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DEPARTAMENTO</label>
													<select name="dept_nacimiento">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="munc_nacimiento">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
											</div>
										</div>
                    
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Residencia</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DIRECCI&Oacute;N</label>
													<input name="dir_residencia"  value="" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">BARRIO</label>
													<input name="barr_residencia"  value="" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">zona de Residencia</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="zona_residencia" value="R" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">RURAL</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="zona_residencia" value="U" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">URBANA</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Lugar de Residencia</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DEPARTAMENTO</label>
													<select name="dept_residencia">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="munc_residencia">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
											</div>
										</div>
                    
                    <div class="box-title">
                      <h3><i class="icon-edit"></i>INFORMACI&Oacute;N ACAD&Eacute;MICA</h3>
                    </div>
                  
										<div class="control-group">
											<label for="textfield" class="control-label">&Uacute;ltimo a&ntilde;o cursado</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">&Uacute;LTIMO GRADO</label>
													<input name="ult_grado"  value="" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">&Uacute;LTIMO A&Ntilde;O </label>
													<input name="ult_anio"  value="" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">&Uacute;LTIMO PLANTEL</label>
													<input name="ult_plantel"  value="" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">APROBO</label>
													<input type="radio" name="ult_estado" value="A" class='icheck-me' data-skin="square" data-color="blue" >
                        </div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">REPROBO</label>
                          <input type="radio" name="ult_estado" value="R" class='icheck-me' data-skin="square" data-color="blue" >
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">DESERTO</label>
													<input type="radio" name="ult_estado" value="D" class='icheck-me' data-skin="square" data-color="blue" >
												</div>
                       <label class='inline' for="c5">INTERNO:</label>
                        <div class="input-prepend">
                          <div class="input-prepend">
                            <label class='inline'>SI</label>
                            <input type="radio" name="ult_interno" value="S" class='icheck-me' data-skin="square" data-color="blue" >
                          </div>
                          <div class="input-prepend">
                            <label class='inline' for="c5">NO</label>
                            <input type="radio" name="ult_interno" value="N" class='icheck-me' data-skin="square" data-color="blue" >
                          </div>
                        </div>  
											</div>
                      
										</div>
                    
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Grado al que ingresa</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">GRADO</label>
													<select name="grado_ingresa">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">PREESCOLAR</label>
													<input type="radio" name="nivel_ingresa" value="PREESCOLAR" class='icheck-me' data-skin="square" data-color="blue" >
                        </div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">B&Aacute;SICA PRIMARIA</label>
                          <input type="radio" name="nivel_ingresa" value="PRIMARIA" class='icheck-me' data-skin="square" data-color="blue" >
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">B&Aacute;SICA SECUNDARIA</label>
													<input type="radio" name="nivel_ingresa" value="SECUNDARIA" class='icheck-me' data-skin="square" data-color="blue" >
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">SEDE</label>
													<select name="sede_ingresa">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">DOCENTE</label>
													<select name="docente_ingresa">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
											</div>
										</div>
                    
                    
                    <div class="box-title">
                      <h3><i class="icon-edit"></i>SISTEMA DE SALUD</h3>
                    </div>
                                      
                    <div class="control-group">
											<label for="textfield" class="control-label">EPS a la cual esta afiliado</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="EPS"  value="" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">IPS Asignada</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="IPS"  value="" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Tipo de Sangre y RH</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">TIPO</label>
													<select name="tipo_sangre">
														<option value="A" >A</option>
														<option value="B" >B</option>
														<option value="AB" >AB</option>
														<option value="O" >O</option>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">RH</label>
													<select name="rh_sangre">
														<option value="P" >+</option>
														<option value="N" >-</option>
													</select>
												</div>
											</div>
										</div>
                  
                <div class="box-title">
                  <h3><i class="icon-edit"></i>PROGRAMAS ESPECIALES (UNICAMENTE PARA LA POBLACION VICTIMA DEL CONFLICTO)</h3>
                </div>
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">Tipo de Victima</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="tipo_victima" value="DGA" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">DESVINCULADOS DE GRUPOS ARMADOS</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_victima" value="SD" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">EN SITUACION DE DESPLAZAMIENTO</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_victima" value="HAD" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">HIJO DE ADULTOS DESMOVILIZADOS</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_victima" value="NA" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">NO APLICA</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div> 
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Lugar de Expulsi&oacute;n</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DEPARTAMENTO</label>
													<select name="dept_expulsion">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="munc_expulsion">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Fecha de Expulsi&oacute;n</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="fecha_expulsion"  value="" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">Certificado</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="certif_expulsion" value="S" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">SI</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="certif_expulsion" value="N" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">NO</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div>
                    
                <div class="box-title">
                  <h3><i class="icon-edit"></i>DISCAPACIDADES Y CAPACIDADES EXCEPCIONALES</h3>
                </div>
                
										<div class="control-group error">
											<label for="textfield" class="control-label">Discapacidades</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="discapacidad" value="SP" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">SORDERA PROFUNDA</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="discapacidad" value="BV" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">BAJA VISION DIAGNOSTICADA</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="discapacidad" value="BA" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">HIPOACUSIA-BAJA AUDICION</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="discapacidad" value="PC" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">PARALISIS CELEBRAL</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="discapacidad" value="C" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">CEGUERA</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="discapacidad" value="A" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">AUTISMO</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="discapacidad" value="M" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">MULTIPLE</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="discapacidad" value="DC" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">DEFICIENCIA COGNITIVA</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="discapacidad" value="NA" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">NO APLICA</label>
												</div>
												<span class="help-block">
												</span> 
											</div>
										</div>
              
                <div class="box-title">
                    <h3><i class="icon-edit"></i>INFORMACI&Oacute;N FAMILIAR</h3>
                  </div>
                  
                    <div class="control-group error">
											<label for="textfield" class="control-label">Tipo de Documento</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="acud_tipo_documento" value="CC" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">CC</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_tipo_documento" value="RC" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">RC</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_tipo_documento" value="TI" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">TI</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_tipo_documento" value="CE" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">CE</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div>                 
                    
                    
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">N&uacutemero de Documento</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="acud_documento" type="text" >
												</div>
												<span class="help-block">

												</span>
											</div>
										</div>
                    
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Lugar de Expedici&oacute;n</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DEPARTAMENTO</label>
													<select name="acud_dept_exp_doc">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="acud_munc_exp_doc">
														<?  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['ID']."-".$sedeList[$s]['NOMBRE']?></option>
														<?  $s++;
															} ?>
													</select>
												</div>
											</div>
										</div>

										<div class="control-group">
											<label for="textfield" class="control-label">Nombres</label>
											<div class="controls">
                        <div class="input-prepend">
													<input name="acud_nombre" type="text" value="">
                          <label class='inline' for="c5">PRIMER NOMBRE</label>
												</div>
												<div class="input-prepend">
													<input name="acud_nombre2" type="text" value="">
                          <label class='inline' for="c5">SEGUNDO NOMBRE</label>
												</div>
												<span class="help-block"></span>
											</div>
										</div>


										<div class="control-group">
											<label for="textfield" class="control-label">Apellidos</label>
											<div class="controls">
                        <div class="input-prepend">
													<input name="acud_apellido" type="text" value="">
                          <label class='inline' for="c5">PRIMER APELLIDO</label>
												</div>
												<div class="input-prepend">
													<input name="acud_apellido2" type="text" value="">
                          <label class='inline' for="c5">SEGUNDO APELLIDO</label>
												</div>
												<span class="help-block"></span>
											</div>
										</div>                    
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">Parentezco</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="acud_parentezco" value="P" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">PADRE</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_parentezco" value="M" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">MADRE</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_parentezco" value="T" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">TIO(A)</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_parentezco" value="HI" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">HIJO(A)</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_parentezco" value="A" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">ABUELO(A)</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_parentezco" value="HE" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">HERMANO(A)</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div>
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">Acudiente</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" name="acud_tipo" value="S" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">SI</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="acud_tipo" value="N" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">NO</label>
												</div>
											</div>
										</div>
                    
                  
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Residencia</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DIRECCI&Oacute;N</label>
													<input name="acud_dir_residencia"  value="" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">TEL&Eacute;FONO</label>
													<input name="acud_telefono1"  value="" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">TEL&Eacute;FONO DEL TRABAJO</label>
											<div class="controls">
                        <div class="input-prepend">
													<input name="acud_telefono2"  value="" type="text">
												</div>
											</div>
										</div>
                    
                   
                    <br/><br/><br/><br/><br/>
										<!--div class="form-actions">
											<input class="btn btn-primary" type="submit" value="Guardar Cambios">
											<button type="button" class="btn">Cancel</button>
										</div-->

										<input type='hidden' name='formSaraData' value="<?=$formSaraData?>">

									</form>
								</div>
							</div>
						</div>
					</div>

</div>
<style>
label {
  margin-left:5px; 
}
</style>