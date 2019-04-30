<div id="main_user">

	<br/><br/>
	<div class="accordion accordion-widget" id="accordion3">
		
		<!--Inicio Comercio-->

		
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#c2">
					FICHA FAMILIAR
				</a>
			</div>
			<div id="c2" class="accordion-body">
				<div class="accordion-inner">
					<div class="box-content nopadding">
						<ul class="tabs tabs-inline tabs-top"style="width: 735px;">
							<li class='active'>
								<a href="#first11" data-toggle='tab'><i class="icon-inbox"></i> Datos del Estudiante</a>
							</li>
							<li>
								<a href="#second22" data-toggle='tab'><i class="icon-share-alt"></i> Composición familiar</a>
							</li>
							<li>
								<a href="#second22" data-toggle='tab'><i class="icon-tag"></i> Relaciones Familiares</a>
							</li>
                            <li>
								<a href="#thirds3322" data-toggle='tab'><i class="icon-tag"></i> Relaciones Comunitarias</a>
							</li>
						</ul>
						<div class="tab-content padding tab-content-inline tab-content-bottom">
							<div class="tab-pane active" id="first11">
								<form id="academicoDataBasic"action="index.php" method="POST" class='form-horizontal form-bordered'>
								<div class="control-group">
									<label for="textfield" class="control-label">Sede</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
											<?PHP	
													foreach($categoryListCommerce as $valueList){
														$selected=($academico[$i]['IDTYPE']==$valueList['IDCATCOMMERCE'])?"selected":"";
														echo '<option value="'.$valueList['IDCATCOMMERCE'].'" type="text" '.$selected.' >'.$valueList['NAME']."</option>";
													}
											?>
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
								<div class="control-group">
									<label for="textfield" class="control-label">Primer apellido</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="Primer_apellido" value="<?=$academico[$i]['PRIMER_APELLIDO']?>" type="text" placeholder="">
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
								<div class="control-group">
									<label for="textfield" class="control-label">Segundo apellido</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="segundo_apellido"  value="<?=$academico[$i]['SEG_APELLIDO']?>"  type="text" placeholder="">
										</div>
										<!--span class="help-block">
											-
										</span-->
									</div>
								</div>
						       <div class="control-group">
									<label for="textfield" class="control-label">Nombre</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="nombre" value="<?=$academico[$i]['NOMBRE']?>" type="text" placeholder="">
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
  
  						       <div class="control-group">
									<label for="textfield" class="control-label">Fecha de nacimiento</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="nacimiento" value="<?=$academico[$i]['FECHA_NAC']?>" type="text" placeholder="">
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
								<div class="control-group">
									<label for="textfield" class="control-label">Tipo de identificación</label>
									<div class="controls">

										<label class="radio">
											 <input name="method" value="rc" type="radio" <?PHP echo $check=($academico[$i]['METHOD']=="IR")?"checked":"" ?> > RC 	
										</label>
										<label class="radio">
											 <input name="method" value="ti" type="radio" <?PHP echo $check=($academico[$i]['METHOD']=="NP")?"checked":"" ?> > TI 
										</label>
										<!--span class="help-block">
											-
										</span-->
									</div>
								</div>
                                
                               <div class="control-group">
									<label for="textfield" class="control-label">Número de identificación</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="numero" value="<?=$academico[$i]['NO_IDEN']?>" type="text" placeholder="">
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Fecha de ingreso</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="ingreso" value="<?=$academico[$i]['FECHA_INGRESO']?>" type="text" placeholder="">
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
                                
								<div class="control-group">
									<label for="textfield" class="control-label">Direccion de residencia</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="direccion"  value="<?=$academico[$i]['DIRECCION']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
								<div class="control-group">
									<label for="textfield" class="control-label">Distancia Casa - Escuela</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="distacia"  value="<?=$academico[$i]['DISTANCIA']?>"  type="text" placeholder="">
										</div>
										<span class="help-block">
											Tiempo de la casa a la escuela
										</span>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Tiempo de vivir en la vereda</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="tiempo"  value="<?=$academico[$i]['VEREDA']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Religión</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="religion"  value="<?=$academico[$i]['RELIGION']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Procedencia de la familia</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="familia"  value="<?=$academico[$i]['PROCEDENIA']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                               <div class="control-group">
									<label for="textfield" class="control-label">Seguridad Social</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="seguridad"  value="<?=$academico[$i]['SOCIAL']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                               <div class="control-group">
									<label for="textfield" class="control-label">Estrato</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="estrato"  value="<?=$academico[$i]['ESTRATO']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Discapacitado</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="discapacitado"  value="<?=$academico[$i]['DISCAPACITADO']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Desplazado</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="desplazado"  value="<?=$academico[$i]['DESPLAZADO']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Código</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="codigo"  value="<?=$academico[$i]['CODIGO']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Número de hermanos</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="hermanos"  value="<?=$academico[$i]['NUMERO_HER']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Lugar que ocupa dentro de la familia</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="lugar"  value="<?=$academico[$i]['LUGAR']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Vive con sus padres?</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="codigo"  value="<?=$academico[$i]['VIVE']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Nombres y apellidos del Padre</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="padre"  value="<?=$academico[$i]['NOM_PAD']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">No Cédula del padre</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="cedula_padre"  value="<?=$academico[$i]['CED_PAD']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Nombres y apellidos de la Madre</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="madre"  value="<?=$academico[$i]['NOM_MAD']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">No Cédula de la Madre</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="cedula_madre"  value="<?=$academico[$i]['CED_MAD']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Nombre del acudiente</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="acudiente"  value="<?=$academico[$i]['NOM_ACUD']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">No de cédula del acudiente</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="cedula_acudiente"  value="<?=$academico[$i]['CED_ACUD']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Teléfono</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="telefono"  value="<?=$academico[$i]['TELEFONO']?>"  type="text" placeholder="">
										</div>
									</div>
								</div>
                                
                                
								<div class="control-group">
									<label for="textfield" class="control-label">Imagenes</label>
									<div class="controls">
										<div class="input-prepend">
											<input name="imagen"  value="<?=$academico[$i]['CAPACITY']?>"  type="file" placeholder="">
										</div>
									</div>
								</div>
									<input type='hidden' name='optionValue' value="<?=$academico[$i]['IDCOMMERCE']?>">
									<input type='hidden' name='optionTab' value="basic">
									<div class="form-actions">
										<a onclick="updateForm('<?=$formSaraDataCommerce?>','#academicoDataBasic')" class="btn btn-primary">Actualizar</a>
									</div>
								</form>	
							</div>

							<!--TAB 2 -->

							<div class="tab-pane" id="second22">

								<form id="commerceDataFeatures" class="form-horizontal form-bordered" method="POST" action="index.php">

								<div class="control-group">
									<label for="textfield" class="control-label">Quien aporta para el sustento del hogar?</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
                                               <option value="1">Elije una opción</option> 
                                               <option value="2">Padre</option> 
                                               <option value="3">Madre</option>
                                               <option value="4">Otros</option> 
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
                                
                                								<div class="control-group">
									<label for="textfield" class="control-label">Quien colabora con las tareas escolares?</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
                                               <option value="1">Elije una opción</option> 
                                               <option value="2">Padre</option> 
                                               <option value="3">Madre</option>
                                               <option value="4">Otros</option> 
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
                                								<div class="control-group">
									<label for="textfield" class="control-label">Quien colabora en los cuidados cotidianos?</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
                                               <option value="1">Elije una opción</option> 
                                               <option value="2">Padre</option> 
                                               <option value="3">Madre</option>
                                               <option value="4">Otros</option> 
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
                                <div class="control-group">
									<label for="textfield" class="control-label">Quien toma las decisiones respecto a la educación de los hijos?</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
                                               <option value="1">Elije una opción</option> 
                                               <option value="2">Padre</option> 
                                               <option value="3">Madre</option>
                                               <option value="4">Otros</option> 
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
                                								<div class="control-group">
									<label for="textfield" class="control-label">Quien participa  en el juego?</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
                                               <option value="1">Elije una opción</option> 
                                               <option value="2">Padre</option> 
                                               <option value="3">Madre</option>
                                               <option value="4">Otros</option> 
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
                                <div class="control-group">
									<label for="textfield" class="control-label">Quien corrige al niño?</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
                                               <option value="1">Elije una opción</option> 
                                               <option value="2">Padre</option> 
                                               <option value="3">Madre</option>
                                               <option value="4">Otros</option> 
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>

									<input type='hidden' name='optionValue' value="<?=$academico[$i]['IDCOMMERCE']?>">
									<input type='hidden' name='optionTab' value="features">
									<div class="form-actions">
										<a onclick="updateForm('<?=$formSaraDataCommerce?>','#commerceDataFeatures')" class="btn btn-primary">Actualizar</a>
									</div>
								</form>
							</div>
                            
                            
							<div class="tab-pane" id="thirds3322">
								<form id="commerceDataTime" class="form-horizontal form-bordered" method="POST" action="index.php">
                                <div class="control-group">
									<label for="textfield" class="control-label">Comparte con los vecinos actividades:</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
                                               <option value="1">Elije una opción</option> 
                                               <option value="2">Recreativas</option> 
                                               <option value="3">Culturales</option>
                                               <option value="4">Cívicas</option> 
                                               <option value="5">otras</option> 
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="textfield" class="control-label">Los problemas personales principalmente los comparte con:</label>
									<div class="controls">
										<div class="input-prepend">
											<select name="commercetype"  id="commercetype" >
                                               <option value="1">Elije una opción</option> 
                                               <option value="2">Familiares</option> 
                                               <option value="3">Amigos</option>
                                               <option value="4">Vecinos</option> 
                                               <option value="5">No los comparte</option> 
                                              
											</select>
										</div>
										<!--span class="helprocessEditprocessEditp-block">
											-
										</span-->
									</div>
								</div>					
							
									<input type='hidden' name='optionValue' value="<?=$academico[$i]['IDCOMMERCE']?>">
									<input type='hidden' name='optionTab' value="time">
									<div class="form-actions">
										<a onclick="updateForm('<?=$formSaraDataCommerce?>','#commerceDataTime')" class="btn btn-primary">Actualizar</a>
									</div>
								</form>
							</div>

							</div>
									
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!--Fin Comercio-->




	</div>
		
					
</div>
