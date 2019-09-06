<div id="main_user">
		<div class="row-fluid">
						<div class="span12">
							<div class="box box-bordered">
								<div class="box-title">
									<h3><i class="icon-edit"></i> Crear competencia</h3>
								</div>
								<div class="box-content nopadding">
									
									<form action="index.php" method="POST" class='form-horizontal form-bordered form-validate'>

										<div class="control-group">
											<label for="textfield" class="control-label">Grado</label>
											<div class="controls">
												<div >
													<select onchange="changeGrado($(this).val())" style="width:90%"  name="grado">
														<?php  $s=0;
															while(isset($grados[$s][0])):?> 
																<option value="<?=$grados[$s]['ID']?>" ><?=$grados[$s]['NOMBRENUMERO']?></option>
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
													<input type="number" name="identificador" required >
												</div>
											</div>
										</div> 
										<div class="control-group">
											<label for="textfield" class="control-label">Area</label>
											<div class="controls">
												<div >
													<select style="width:90%"  name="area">
														<?php  $s=0;
															while(isset($areas[$s][0])):?> 
																<option value="<?=$areas[$s]['ID']?>" ><?=$areas[$s]['AREA']?></option>
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
													<input type="radio" name="periodo" value="1" required> 1 
													<input type="radio" name="periodo" value="2"> 2  
													<input type="radio" name="periodo" value="3"> 3 
													<input type="radio" name="periodo" value="4"> 4 
												</div>
											</div>
										</div> 

										<div class="control-group"> 
											<label for="textfield" class="control-label">Competencia</label>
											<div class="controls">
												<div >
													<textarea name="competencia" style="width:90%"  value="" type="text" required ></textarea>
												</div>
												<span class="help-block">
													
												</span>
											</div>
										</div>

										<div class="control-group" id="desempenios">
												<div class="control-group">
													<label for="textfield" class="control-label">Desempeño Básico</label>
													<div class="controls">
														<div >
															<textarea style="width:90%" name="dbasico"  value="" type="text" ></textarea>
														</div>
														<span class="help-block">
															
														</span>
													</div>
												</div>
												<div class="control-group">
													<label for="textfield" class="control-label">Desempeño Alto</label>
													<div class="controls">
														<div >
															<textarea style="width:90%" name="dalto"  value="" type="text" ></textarea>
														</div>
														<span class="help-block">
															
														</span>
													</div>
												</div>
												<div class="control-group">
													<label for="textfield" class="control-label">Desempeño Superior</label>
													<div class="controls">
														<div >
															<textarea style="width:90%" name="dsuperior"  value="" type="text" ></textarea>
														</div>
														<span class="help-block">
															
														</span>
													</div>
												</div>
										</div>
										
										<div class="form-actions">
											<button type="submit" class="btn btn-primary">
												<i class="icon-white icon-ok"></i>
												Crear
											</button>
											<a href="<?=$formSaraDataUrl?>" type="submit" class="btn btn-inverse disabled">
												<i class="icon-white icon-arrow-left"></i>
												Cancelar
											</a>
										</div>
										<input type='hidden' name='formSaraData' value="<?=$formSaraDataAction?>">

									</form>
								</div>
							</div>
						</div>
					</div>
					
</div>
