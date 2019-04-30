<div id="main_user">
		<div class="row-fluid">
						<div class="span12">
							<div class="box box-bordered">
								<center>
                <div class="box-title">
                  <h3>FORMATO DE MATRICULA - 2018 - <?=$userDataByID['APELLIDO']?> <?=$userDataByID['APELLIDO2']?> <?=$userDataByID['NOMBRE']?> <?=$userDataByID['NOMBRE2']?></h3>
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
													<input <?=($userEnrollByID['tipo_estudiante']=='NUEVO')?"checked":""?> type="radio" name="tipo_estudiante" value="NUEVO" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">NUEVO</label>
												</div>
                        <div class="input-prepend">
                          <input <?=($userEnrollByID['tipo_estudiante']=='ANTIGUO')?"checked":""?> type="radio" name="tipo_estudiante" value="ANTIGUO" class='icheck-me' data-skin="square" data-color="blue" >
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
													<input <?=($userEnrollByID['tipo_documento']=='CC')?"checked":""?> type="radio" name="tipo_documento" value="CC" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">CC</label>
												</div>
                        <div class="input-prepend">
                          <input <?=($userEnrollByID['tipo_documento']=='RC')?"checked":""?> type="radio" name="tipo_documento" value="RC" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">RC</label>
												</div>
                        <div class="input-prepend">
                          <input <?=($userEnrollByID['tipo_documento']=='TI')?"checked":""?> type="radio" name="tipo_documento" value="TI" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">TI</label>
												</div>
                        <div class="input-prepend">
                          <input <?=($userEnrollByID['tipo_documento']=='CE')?"checked":""?> type="radio" name="tipo_documento" value="CE" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">CE</label>
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
													<select onchange="getMunicipio(this.value,'<?=$formSaraDataMun?>','#munc_exp_doc_id')" name="dept_exp_doc">
														<? foreach($departamentos as $kd => $vd) { ?>
																<option <?=($userEnrollByID['dept_exp_doc']==$kd)?"selected":""?> value="<?=$kd?>" ><?=$vd?></option>
														<? } ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="munc_exp_doc" id="munc_exp_doc_id">
                            <? if(!empty($userEnrollByID['munc_exp_doc'])) { ?>
																<option value="<?=$userEnrollByID['munc_exp_doc']?>" ><?=$userEnrollByID['munc_exp_doc']?></option>
														<? } ?>
													</select>
												</div>
											</div>
										</div>

                    <div class="control-group error">
											<label for="textfield" class="control-label">G&eacute;nero</label>
											<div class="controls">
												<div class="input-prepend">
													<input  <?=($userEnrollByID['genero']=='M')?"checked":""?> type="radio" name="genero" value="M" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">MASCULINO</label>
												</div>
                        <div class="input-prepend">
                          <input  <?=($userEnrollByID['genero']=='F')?"checked":""?> type="radio" name="genero" value="F" class='icheck-me' data-skin="square" data-color="blue" >
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
													<input name="fecha_nacimiento" value="<?=isset($userEnrollByID['fecha_nacimiento'])?$userEnrollByID['fecha_nacimiento']:""?>" type="text">
												</div>
                         <span class="help-block">YYYY-MM-DD</span> 
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Lugar de Nacimiento</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DEPARTAMENTO</label>
													<select onchange="getMunicipio(this.value,'<?=$formSaraDataMun?>','#munc_nacimiento_id')" name="dept_nacimiento">
														<? foreach($departamentos as $kd => $vd) { ?>
																<option <?=($userEnrollByID['dept_nacimiento']==$kd)?"selected":""?> value="<?=$kd?>" ><?=$vd?></option>
														<? } ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="munc_nacimiento" id="munc_nacimiento_id" >
                            <? if(!empty($userEnrollByID['munc_nacimiento'])) { ?>
																<option value="<?=$userEnrollByID['munc_nacimiento']?>" ><?=$userEnrollByID['munc_nacimiento']?></option>
														<? } ?>                          
													</select>
												</div>
											</div>
										</div>
                    
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Residencia</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DIRECCI&Oacute;N</label>
													<input name="dir_residencia"  value="<?=isset($userEnrollByID['dir_residencia'])?$userEnrollByID['dir_residencia']:""?>" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">BARRIO</label>
													<input name="barr_residencia"  value="<?=isset($userEnrollByID['barr_residencia'])?$userEnrollByID['barr_residencia']:""?>" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">zona de Residencia</label>
											<div class="controls">
												<div class="input-prepend">
													<input <?=($userEnrollByID['zona_residencia']=='RURAL')?"checked":""?> type="radio" name="zona_residencia" value="RURAL" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">RURAL</label>
												</div>
                        <div class="input-prepend">
                          <input <?=($userEnrollByID['zona_residencia']=='URBANA')?"checked":""?> type="radio" name="zona_residencia" value="URBANA" class='icheck-me' data-skin="square" data-color="blue" >
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
													<select onchange="getMunicipio(this.value,'<?=$formSaraDataMun?>','#munc_residencia_id')" name="dept_residencia">
														<? foreach($departamentos as $kd => $vd) { ?>
																<option <?=($userEnrollByID['dept_residencia']==$kd)?"selected":""?> value="<?=$kd?>" ><?=$vd?></option>
														<? } ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="munc_residencia" id="munc_residencia_id">
                            <? if(!empty($userEnrollByID['munc_residencia'])) { ?>
																<option value="<?=$userEnrollByID['munc_residencia']?>" ><?=$userEnrollByID['munc_residencia']?></option>
														<? } ?>                            
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
													<input name="ult_grado"  value="<?=isset($userEnrollByID['ult_grado'])?$userEnrollByID['ult_grado']:""?>" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">&Uacute;LTIMO A&Ntilde;O </label>
													<input name="ult_anio"  value="<?=isset($userEnrollByID['ult_anio'])?$userEnrollByID['ult_anio']:""?>" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">&Uacute;LTIMO PLANTEL</label>
													<input name="ult_plantel"  value="<?=isset($userEnrollByID['ult_plantel'])?$userEnrollByID['ult_plantel']:""?>" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">APROBO</label>
													<input type="radio" <?=($userEnrollByID['ult_estado']=='APROBO')?"checked":""?> name="ult_estado" value="APROBO" class='icheck-me' data-skin="square" data-color="blue" >
                        </div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">REPROBO</label>
                          <input type="radio" <?=($userEnrollByID['ult_estado']=='REPROBO')?"checked":""?> name="ult_estado" value="REPROBO" class='icheck-me' data-skin="square" data-color="blue" >
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">DESERTO</label>
													<input type="radio" <?=($userEnrollByID['ult_estado']=='DESERTO')?"checked":""?> name="ult_estado" value="DESERTO" class='icheck-me' data-skin="square" data-color="blue" >
												</div>
                       <label class='inline' for="c5">INTERNO:</label>
                        <div class="input-prepend">
                          <div class="input-prepend">
                            <label class='inline'>SI</label>
                            <input type="radio" <?=($userEnrollByID['ult_interno']=='SI')?"checked":""?> name="ult_interno" value="SI" class='icheck-me' data-skin="square" data-color="blue" >
                          </div>
                          <div class="input-prepend">
                            <label class='inline' for="c5">NO</label>
                            <input type="radio" <?=($userEnrollByID['ult_interno']=='NO')?"checked":""?> name="ult_interno" value="NO" class='icheck-me' data-skin="square" data-color="blue" >
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
														<option value="0" <?=($userEnrollByID['grado_ingresa']=="0")?"selected":""?> >0</option>
                            <option value="1" <?=($userEnrollByID['grado_ingresa']=="1")?"selected":""?> >1</option>
                            <option value="2" <?=($userEnrollByID['grado_ingresa']=="2")?"selected":""?> >2</option>
                            <option value="3" <?=($userEnrollByID['grado_ingresa']=="3")?"selected":""?> >3</option>
                            <option value="4" <?=($userEnrollByID['grado_ingresa']=="4")?"selected":""?> >4</option>
                            <option value="5" <?=($userEnrollByID['grado_ingresa']=="5")?"selected":""?> >5</option>
                            <option value="6" <?=($userEnrollByID['grado_ingresa']=="6")?"selected":""?> >6</option>
                            <option value="7" <?=($userEnrollByID['grado_ingresa']=="7")?"selected":""?> >7</option>
                            <option value="8" <?=($userEnrollByID['grado_ingresa']=="8")?"selected":""?> >8</option>
                            <option value="9" <?=($userEnrollByID['grado_ingresa']=="9")?"selected":""?> >9</option> 
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">PREESCOLAR</label>
													<input type="radio" <?=($userEnrollByID['nivel_ingresa']=='PREESCOLAR')?"checked":""?> name="nivel_ingresa" value="PREESCOLAR" class='icheck-me' data-skin="square" data-color="blue" >
                        </div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">B&Aacute;SICA PRIMARIA</label>
                          <input type="radio" <?=($userEnrollByID['nivel_ingresa']=='PRIMARIA')?"checked":""?> name="nivel_ingresa" value="PRIMARIA" class='icheck-me' data-skin="square" data-color="blue" >
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">B&Aacute;SICA SECUNDARIA</label>
													<input type="radio" <?=($userEnrollByID['nivel_ingresa']=='SECUNDARIA')?"checked":""?> name="nivel_ingresa" value="SECUNDARIA" class='icheck-me' data-skin="square" data-color="blue" >
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">SEDE</label> 
													<select name="sede_ingresa">
														<?php  $s=0;
															while(isset($sedeList[$s][0])){?>
																<option <?=($userEnrollByID['sede_ingresa']==$sedeList[$s]['ID'])?"selected":""?> value="<?=$sedeList[$s]['ID']?>" ><?=$sedeList[$s]['NOMBRE']?></option> 
														<?php  $s++;
															} ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">DOCENTE</label>
													<select name="docente_ingresa">
														<?php  $s=0;
															while(isset($teacherList[$s][0])){?>
																<option <?=($userEnrollByID['docente_ingresa']==$teacherList[$s]['ID'])?"selected":""?> value="<?=$teacherList[$s]['ID']?>" ><?=$teacherList[$s]['NOMBRE']?> <?=$teacherList[$s]['NOMBRE2']?> <?=$teacherList[$s]['APELLIDO']?> <?=$teacherList[$s]['APELLIDO2']?></option>
														<?php  $s++;
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
													<input name="EPS"  value="<?=isset($userEnrollByID['EPS'])?$userEnrollByID['EPS']:""?>" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">IPS Asignada</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="IPS"  value="<?=isset($userEnrollByID['IPS'])?$userEnrollByID['IPS']:""?>" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Tipo de Sangre y RH</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">TIPO</label>
													<select name="tipo_sangre">
														<option value="A" <?=($userEnrollByID['tipo_sangre']=='A')?"selected":""?> >A</option>
														<option value="B" <?=($userEnrollByID['tipo_sangre']=='B')?"selected":""?> >B</option>
														<option value="AB"<?=($userEnrollByID['tipo_sangre']=='AB')?"selected":""?> >AB</option>
														<option value="O" <?=($userEnrollByID['tipo_sangre']=='O')?"selected":""?> >O</option>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">RH</label>
													<select name="rh_sangre">
														<option value="POSITIVO" <?=($userEnrollByID['rh_sangre']=='POSITIVO')?"selected":""?> >POSITIVO</option>
														<option value="NEGATIVO" <?=($userEnrollByID['rh_sangre']=='NEGATIVO')?"selected":""?>>NEGATIVO</option>
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
													<input type="radio" name="tipo_victima" <?=($userEnrollByID['tipo_victima']=='DGA')?"checked":""?>  value="DGA" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">DESVINCULADOS DE GRUPOS ARMADOS</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_victima" <?=($userEnrollByID['tipo_victima']=='SD')?"checked":""?> value="SD" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">EN SITUACION DE DESPLAZAMIENTO</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_victima" <?=($userEnrollByID['tipo_victima']=='HAD')?"checked":""?> value="HAD" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">HIJO DE ADULTOS DESMOVILIZADOS</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" name="tipo_victima" <?=($userEnrollByID['tipo_victima']=='NA')?"checked":""?> value="NA" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">NO APLICA</label>
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
                          <select onchange="getMunicipio(this.value,'<?=$formSaraDataMun?>','#munc_expulsion_id')" name="dept_expulsion">
														<? foreach($departamentos as $kd => $vd) { ?>
																<option <?=($userEnrollByID['dept_expulsion']==$kd)?"selected":""?> value="<?=$kd?>" ><?=$vd?></option>
														<? } ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="munc_expulsion" id="munc_expulsion_id">
                            <? if(!empty($userEnrollByID['munc_expulsion'])) { ?>
																<option value="<?=$userEnrollByID['munc_expulsion']?>" ><?=$userEnrollByID['munc_expulsion']?></option>
														<? } ?>                            
													</select>
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Fecha de Expulsi&oacute;n</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="fecha_expulsion"  value="<?=isset($userEnrollByID['fecha_expulsion'])?$userEnrollByID['fecha_expulsion']:""?>" type="text">
												</div>
                        <span class="help-block">YYYY-MM-DD</span>
											</div>
										</div>
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">Certificado</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" <?=($userEnrollByID['certif_expulsion']=='SI')?"checked":""?>  name="certif_expulsion" value="SI" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">SI</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['certif_expulsion']=='NO')?"checked":""?> name="certif_expulsion" value="NO" class='icheck-me' data-skin="square" data-color="blue" >
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
											<label for="textfield" class="control-label">Tipo de Discapacidad</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" <?=($userEnrollByID['discapacidad']=='SP')?"checked":""?>  name="discapacidad" value="SP" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">SORDERA PROFUNDA</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['discapacidad']=='BV')?"checked":""?>  name="discapacidad" name="discapacidad" value="BV" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">BAJA VISION DIAGNOSTICADA</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['discapacidad']=='BA')?"checked":""?>  name="discapacidad" name="discapacidad" value="BA" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">HIPOACUSIA-BAJA AUDICION</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['discapacidad']=='PC')?"checked":""?>  name="discapacidad" name="discapacidad" value="PC" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">PARALISIS CELEBRAL</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['discapacidad']=='C')?"checked":""?>  name="discapacidad" name="discapacidad" value="C" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">CEGUERA</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['discapacidad']=='A')?"checked":""?>  name="discapacidad" name="discapacidad" value="A" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">AUTISMO</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['discapacidad']=='M')?"checked":""?>  name="discapacidad" name="discapacidad" value="M" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">MULTIPLE</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['discapacidad']=='DC')?"checked":""?>  name="discapacidad" name="discapacidad" value="DC" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">DEFICIENCIA COGNITIVA</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['discapacidad']=='NA')?"checked":""?>  name="discapacidad" name="discapacidad" value="NA" class='icheck-me' data-skin="square" data-color="blue" >
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
													<input type="radio" <?=($userEnrollByID['acud_tipo_documento']=='CC')?"checked":""?>  name="acud_tipo_documento" value="CC" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">CC</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_tipo_documento']=='RC')?"checked":""?>  name="acud_tipo_documento" value="RC" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">RC</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_tipo_documento']=='TI')?"checked":""?>  name="acud_tipo_documento" value="TI" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">TI</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_tipo_documento']=='CE')?"checked":""?>  name="acud_tipo_documento" value="CE" class='icheck-me' data-skin="square" data-color="blue" ><label class='inline' for="c5">CE</label>
												</div>
												<span class="help-block">
												</span>
											</div>
										</div>                 
                    
                    
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">N&uacutemero de Documento</label>
											<div class="controls">
												<div class="input-prepend">
													<input name="acud_documento" value="<?=isset($userEnrollByID['acud_documento'])?$userEnrollByID['acud_documento']:""?>" type="text" >
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
													<select onchange="getMunicipio(this.value,'<?=$formSaraDataMun?>','#acud_munc_exp_doc_id')" name="acud_dept_exp_doc">
														<? foreach($departamentos as $kd => $vd) { ?>
																<option <?=($userEnrollByID['acud_dept_exp_doc']==$kd)?"selected":""?> value="<?=$kd?>" ><?=$vd?></option>
														<? } ?>
													</select>
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">MUNICIPIO</label>
													<select name="acud_munc_exp_doc" id="acud_munc_exp_doc_id">
                            <? if(!empty($userEnrollByID['acud_munc_exp_doc'])) { ?>
																<option value="<?=$userEnrollByID['acud_munc_exp_doc']?>" ><?=$userEnrollByID['acud_munc_exp_doc']?></option>
														<? } ?>                            
													</select>
												</div>
											</div>
										</div>

										<div class="control-group">
											<label for="textfield" class="control-label">Nombres</label>
											<div class="controls">
                        <div class="input-prepend">
													<input name="acud_nombre" type="text" value="<?=isset($userEnrollByID['acud_nombre'])?$userEnrollByID['acud_nombre']:""?>">
                          <label class='inline' for="c5">PRIMER NOMBRE</label>
												</div>
												<div class="input-prepend">
													<input name="acud_nombre2" type="text" value="<?=isset($userEnrollByID['acud_nombre2'])?$userEnrollByID['acud_nombre2']:""?>">
                          <label class='inline' for="c5">SEGUNDO NOMBRE</label>
												</div>
												<span class="help-block"></span>
											</div>
										</div>


										<div class="control-group">
											<label for="textfield" class="control-label">Apellidos</label>
											<div class="controls">
                        <div class="input-prepend">
													<input name="acud_apellido" type="text" value="<?=isset($userEnrollByID['acud_apellido'])?$userEnrollByID['acud_apellido']:""?>">
                          <label class='inline' for="c5">PRIMER APELLIDO</label>
												</div>
												<div class="input-prepend">
													<input name="acud_apellido2" type="text" value="<?=isset($userEnrollByID['acud_apellido2'])?$userEnrollByID['acud_apellido2']:""?>">
                          <label class='inline' for="c5">SEGUNDO APELLIDO</label>
												</div>
												<span class="help-block"></span>
											</div>
										</div>                    
                    
                    <div class="control-group error">
											<label for="textfield" class="control-label">Parentezco</label>
											<div class="controls">
												<div class="input-prepend">
													<input type="radio" <?=($userEnrollByID['acud_parentezco']=='P')?"checked":""?>  name="acud_parentezco" value="P" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">PADRE</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_parentezco']=='M')?"checked":""?>  name="acud_parentezco" value="M" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">MADRE</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_parentezco']=='T')?"checked":""?>  name="acud_parentezco" value="T" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">TIO(A)</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_parentezco']=='HI')?"checked":""?>  name="acud_parentezco" value="HI" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">HIJO(A)</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_parentezco']=='A')?"checked":""?>  name="acud_parentezco" value="A" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">ABUELO(A)</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_parentezco']=='HE')?"checked":""?>  name="acud_parentezco" value="HE" class='icheck-me' data-skin="square" data-color="blue" >
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
													<input type="radio" <?=($userEnrollByID['acud_tipo']=='S')?"checked":""?> name="acud_tipo" value="S" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">SI</label>
												</div>
                        <div class="input-prepend">
                          <input type="radio" <?=($userEnrollByID['acud_tipo']=='N')?"checked":""?> name="acud_tipo" value="N" class='icheck-me' data-skin="square" data-color="blue" >
                          <label class='inline' for="c5">NO</label>
												</div>
											</div>
										</div>
                    
                  
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">Residencia</label>
											<div class="controls">
												<div class="input-prepend">
                          <label class='inline' for="c5">DIRECCI&Oacute;N</label>
													<input name="acud_dir_residencia"  value="<?=isset($userEnrollByID['acud_dir_residencia'])?$userEnrollByID['acud_dir_residencia']:""?>" type="text">
												</div>
                        <div class="input-prepend">
                          <label class='inline' for="c5">TEL&Eacute;FONO</label>
													<input name="acud_telefono1"  value="<?=isset($userEnrollByID['acud_telefono1'])?$userEnrollByID['acud_telefono1']:""?>" type="text">
												</div>
											</div>
										</div>
                    
                    <div class="control-group">
											<label for="textfield" class="control-label">TEL&Eacute;FONO DEL TRABAJO</label>
											<div class="controls">
                        <div class="input-prepend">
													<input name="acud_telefono2"  value="<?=isset($userEnrollByID['acud_telefono2'])?$userEnrollByID['acud_telefono2']:""?>" type="text">
												</div>
											</div>
										</div>
                    <br/>
										<div class="form-actions"> 
                      <input name="optionValue" type="hidden" value="<?=$userDataByID['ID']?>" />
                      <button type="submit" class="btn btn-primary">Guardar</button>
										</div>
										<input type='hidden' name='formSaraData' value="<?=$formSaraData?>">
									</form>
                  <a target="_blank" style="cursor:pointer" href="<?=$formSaraDataPrint?>" class="btn" rel="tooltip" title="Imprimir"><i class="icon-edit">IMPRIMIR FORMATO</i></a>  

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