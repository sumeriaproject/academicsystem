<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<div id="main_user">
		<div class="row-fluid">
						<div class="span12">
							<div class="box box-bordered">
								<div class="box-title">
									<h3><i class="icon-edit"></i> Editar competencia</h3>
								</div>
								<div class="box-content nopadding">
									<form action="index.php" id='deleteForm'>
										<input type='hidden' method="POST" name='formSaraData' value="<?=$formSaraDeleteAction?>">
									</form>	

									<form action="index.php" method="POST" class='form-horizontal form-bordered form-validate'>

										<div class="control-group">
											<label for="textfield" class="control-label">Grado</label>
											<div class="controls">
												<div >
													<select onchange="changeGrado($(this).val())"  style="width:90%"  name="grado">
														<?php  $s=0;
															while(isset($grados[$s][0])): ?> 
																<option <?=($competencia['GRADO']==$grados[$s]['ID'])?"selected":""?> value="<?=$grados[$s]['ID']?>" ><?=$grados[$s]['NOMBRENUMERO']?></option>
														<?php  $s++;
															endwhile; ?>	
													</select>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label for="textfield" class="control-label"># Identificador</label>
											<div class="controls">
												<div>
													<input type="number" name="identificador" value="<?=$competencia['IDENTIFICADOR']?>" required >
												</div>
											</div>
										</div> 										
										<div class="control-group">
											<label for="textfield" class="control-label">Area</label>
											<div class="controls">
												<div >
													<select style="width:90%"  name="area">
														<?php  $s=0;
															while(isset($areas[$s][0])): ?> 
																<option <?=($competencia['ID_AREA']==$areas[$s]['ID'])?"selected":""?> value="<?=$areas[$s]['ID']?>" ><?=$areas[$s]['AREA']?></option>
														<?php  $s++;
															endwhile; ?>	
													</select> 
												</div>
											</div>
										</div> 
										<div class="control-group">
											<label for="textfield" class="control-label">Periodo</label>
											<div class="controls">  
												<div>
													<input type="radio" <?=($competencia['PERIODO']=='1')?"checked":""?> name="periodo" value="1"> 1 
													<input type="radio" <?=($competencia['PERIODO']=='2')?"checked":""?> name="periodo" value="2"> 2  
													<input type="radio" <?=($competencia['PERIODO']=='3')?"checked":""?> name="periodo" value="3"> 3 
													<input type="radio" <?=($competencia['PERIODO']=='4')?"checked":""?> name="periodo" value="4"> 4 
												</div>
											</div>
										</div> 

										<div class="control-group"> 
											<label for="textfield" class="control-label">Competencia</label>
											<div class="controls">
												<div >
													<textarea name="competencia" style="width:90%"  value="" type="text" required ><?=$competencia['COMPETENCIA']?></textarea>
												</div>
												<span class="help-block">
													
												</span>
											</div>
										</div>

										<div class="control-group" id="desempenios" style="display:none">
											<div class="control-group">
												<label for="textfield" class="control-label">Desempeño Básico</label>
												<div class="controls">
													<div >
														<textarea style="width:90%" name="dbasico"  value="" type="text" ><?=$competencia['BASICO']?></textarea>
													</div>
													<span class="help-block">
														
													</span>
												</div>
											</div>
											<div class="control-group">
												<label for="textfield" class="control-label">Desempeño Alto</label>
												<div class="controls">
													<div >
														<textarea style="width:90%" name="dalto"  value="" type="text" ><?=$competencia['ALTO']?></textarea>
													</div>
													<span class="help-block">
														
													</span>
												</div>
											</div>
											<div class="control-group">
												<label for="textfield" class="control-label">Desempeño Superior</label>
												<div class="controls">
													<div >
														<textarea style="width:90%" name="dsuperior"  value="" type="text" ><?=$competencia['SUPERIOR']?></textarea>
													</div>
													<span class="help-block">
														
													</span>
												</div>
											</div>
										</div>

										<div class="control-group">
											<label for="textfield" class="control-label">Estado</label>
											<div class="controls">
												<div >
													<select style="width:40%" name="estado">
														<option <?=($competencia['ESTADO']==1)?"selected":""?> value="1" >Activo</option>	
														<option <?=($competencia['ESTADO']==0)?"selected":""?> value="0" >Inactivo</option>	
													</select>
												</div>
											</div>
										</div> 

										<div class="form-actions">
											<button type="submit" class="btn btn-primary">
												<i class="icon-white icon-ok"></i>
												Guardar
											</button>
											<a href="<?=$formSaraDataUrl?>" type="submit" class="btn btn-inverse disabled">
												<i class="icon-white icon-arrow-left"></i>
												Regresar
											</a>

											<!--button onclick="$('#deleteForm').submit()" class="btn btn-primary">
												<i class="icon-white icon-ok"></i>
												Eliminar
											</button-->
										</div>

										<input type='hidden' name='formSaraData' value="<?=$formSaraDataAction?>">

									</form>
									
								</div>
							</div>
						</div>
					</div>
					
</div>
