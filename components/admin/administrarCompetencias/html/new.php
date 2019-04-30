<script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script>

<div id="root">

</div>


<script>


//console.dir(registers);
/*class InputText extends React.Component {

	componentDidMount(){
		console.log('mount');
	}
    handleChange = (evt) => {
        this.props.onChange(evt.target.value);
    };
 	render() {

	  	const input = React.createElement("input", {
	  		type: 'text',
	  		value: this.props.value,
		    onChange: this.handleChange
		});
	    return React.createElement('div', null, input);
  	}
}

class Form extends React.Component {

	state = {
		registers:[],
		filterRegisters:[]
	};
	 	
	componentDidMount(){
		const registers = [];
		for (let i = 0; i < 1000000; i++) {
			let register = {};
			register['id'] = i;
			register['name'] = i + '-name';
			register['home'] = i + '-name';
			register['address'] = i + '-address';
			register['state'] = i + '-state';
			registers.push(register);
		}
		this.setState({registers:registers, filterRegisters:registers });
	}

	handleInputChange = (value) => {
		let filter = this.state.registers.filter(register => register.name.includes(value));
        this.setState({filterRegisters:filter});
    }

 	render() {

  		const inputNameFilter = React.createElement(InputText, {onChange:this.handleInputChange});
  		let dataTable = [];
		const totalRows = 20;

		if(this.state.registers && this.state.registers.length > 0 ) {
			for(let i = 0; i < this.state.filterRegisters.length; i++) {
				if (i > totalRows) { break; }
				let register = this.state.filterRegisters[i];
				let trData = React.createElement('tr', {key:'tr'+i}, 
		  						[
							  		React.createElement('td', {key:'2asdf1'+i}, register['id']),
							  		React.createElement('td', {key:'2asdf1s'+i}, register.name),
							  		React.createElement('td', {key:'2dsf1'+i}, register.home),
							  		React.createElement('td', {key:'1aree2'+i}, register.address),
							  		React.createElement('td', {key:'2d1'+i}, register.state)
		  						]
	  						);
	  			dataTable.push(trData);
			}
	    }

  		const table =
		  	React.createElement('table', {key:'table'},
		  		[
			  		React.createElement('thead', {key:'thead'},
				     	React.createElement('tr', {key:'trh'},
						    [
						        React.createElement('td', {key:'1'}, 'Chocolate'),
						        React.createElement('td', {key:'2'}, 'Vanilla'),
						        React.createElement('td', {key:'3'}, 'Banana'),
						        React.createElement('td', {key:'4'}, 'Banana'),
						        React.createElement('td', {key:'5'}, 'Banana')
						    ]
				    	)
					),
					React.createElement('tbody', {key:'tbody'}, dataTable)
				   	 
				]	   
			);


		const template = React.createElement('div', {key:'content'}, 
						 [
						 	inputNameFilter,
						 	table
						 ]
						 );

    	return React.createElement('div', {key:'template'}, template);
  	}
}


class App extends React.Component {
  render() {

  	const form = React.createElement(Form,{key:'form'});

    return React.createElement('div', {key:'divform'}, form);
  }
}

ReactDOM.render(
  React.createElement(App, {key:'app'}),
  document.getElementById('root')
); */

</script>




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

										<div class="control-group" id="desempenios" style="display:none">
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
