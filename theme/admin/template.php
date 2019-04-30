<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- Apple devices fullscreen -->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<!-- Apple devices fullscreen -->
	<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	
	<title>Sistema Academico</title>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/bootstrap.min.css">
	<!-- Bootstrap responsive -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/bootstrap-responsive.min.css">
	<!-- jQuery UI -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/jquery-ui/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/jquery-ui/smoothness/jquery.ui.theme.css">
	<!-- PageGuide -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/pageguide/pageguide.css">
	<!-- Fullcalendar -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/fullcalendar/fullcalendar.css">
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/fullcalendar/fullcalendar.print.css" media="print">
	<!-- chosen -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/chosen/chosen.css">
	<!-- select2 -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/select2/select2.css">
	<!-- icheck -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/icheck/all.css">
	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/style.css">
	<!-- Color CSS -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/themes.css">

	<!-- multi select -->
	<link rel="stylesheet" href="<?=$rutaTema?>/css/plugins/multiselect/multi-select.css">


	<!-- jQuery -->

	<!-- Nice Scroll -->
	<script src="<?=$rutaTema?>/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
	<!-- jQuery UI -->
	<script src="<?=$rutaTema?>/js/plugins/jquery-ui/jquery.ui.core.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/jquery-ui/jquery.ui.widget.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/jquery-ui/jquery.ui.mouse.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/jquery-ui/jquery.ui.draggable.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/jquery-ui/jquery.ui.resizable.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/jquery-ui/jquery.ui.sortable.min.js"></script>
	<!-- Touch enable for jquery UI -->
	<script src="<?=$rutaTema?>/js/plugins/touch-punch/jquery.touch-punch.min.js"></script>
	<!-- slimScroll -->
	<script src="<?=$rutaTema?>/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<!-- Bootstrap -->
	<script src="<?=$rutaTema?>/js/bootstrap.min.js"></script>
	<!-- vmap -->
	<script src="<?=$rutaTema?>/js/plugins/vmap/jquery.vmap.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/vmap/jquery.vmap.world.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/vmap/jquery.vmap.sampledata.js"></script>
	<!-- Bootbox -->
	<script src="<?=$rutaTema?>/js/plugins/bootbox/jquery.bootbox.js"></script>
	<!-- Flot -->
	<script src="<?=$rutaTema?>/js/plugins/flot/jquery.flot.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/flot/jquery.flot.bar.order.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/flot/jquery.flot.pie.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/flot/jquery.flot.resize.min.js"></script>
	<!-- imagesLoaded -->
	<script src="<?=$rutaTema?>/js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>
	<!-- PageGuide -->
	<script src="<?=$rutaTema?>/js/plugins/pageguide/jquery.pageguide.js"></script>
	<!-- FullCalendar -->
	<script src="<?=$rutaTema?>/js/plugins/fullcalendar/fullcalendar.min.js"></script>
	<!-- Chosen -->
	<script src="<?=$rutaTema?>/js/plugins/chosen/chosen.jquery.min.js"></script>
	<!-- select2 -->
	<script src="<?=$rutaTema?>/js/plugins/select2/select2.min.js"></script>
	<!-- icheck -->
	<script src="<?=$rutaTema?>/js/plugins/icheck/jquery.icheck.min.js"></script>

	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

	<!-- Theme framework -->
	<script src="<?=$rutaTema?>/js/eakroko.min.js"></script>
	<!-- Theme scripts -->
	<script src="<?=$rutaTema?>/js/application.min.js"></script>
	<!-- Just for demonstration -->
	<script src="<?=$rutaTema?>/js/demonstration.min.js"></script>

	<script src="<?=$rutaTema?>/js/plugins/datatable/jquery.dataTables.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/datatable/TableTools.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/datatable/ColReorderWithResize.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/datatable/ColVis.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/datatable/jquery.dataTables.grouping.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/datatable/jquery.dataTables.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/select2/select2.min.js"></script>
	<script src="<?=$rutaTema?>/js/plugins/multiselect/jquery.multi-select.js"></script>


	
	<!--[if lte IE 9]>
		<script src="<?=$rutaTema?>/js/plugins/placeholder/jquery.placeholder.min.js"></script>
		<script>
			$(document).ready(function() {
				$('input, textarea').placeholder();
			});
		</script>
	<![endif]-->

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?=$rutaTema?>/img/favicon.ico" />
	<!-- Apple devices Homescreen icon -->
	<link rel="apple-touch-icon-precomposed" href="<?=$rutaTema?>/img/apple-touch-icon-precomposed.png" />

</head>

<body>
	<div id="new-task" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Add new task</h3>
		</div>
		<form action="#" class='new-task-form form-horizontal form-bordered'>
			<div class="modal-body nopadding">
				<div class="control-group">
					<label for="tasktitel" class="control-label">Icon</label>
					
				</div>
				<div class="control-group">
					<label for="task-name" class="control-label">Task</label>
					<div class="controls">
						<input type="text" name="task-name">
					</div>
				</div>
				<div class="control-group">
					<label for="tasktitel" class="control-label"></label>
					<div class="controls">
						<label class="checkbox"><input type="checkbox" name="task-bookmarked" value="yep"> Mark as important</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-primary" value="Add task">
			</div>
		</form>

	</div>
	<!--div id="modal-user" class="modal hide">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="user-infos">Jane Doe</h3>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span2">
					<img src="<?=$rutaTema?>/img/demo/user-1.jpg" alt="">
				</div>
				<div class="span10">
					<dl class="dl-horizontal" style="margin-top:0;">
						<dt>Full name:</dt>
						<dd>Jane Doe</dd>
						<dt>Email:</dt>
						<dd>jane.doe@janedoesemail.com</dd>
						<dt>Address:</dt>
						<dd>
							<address> <strong>John Doe, Inc.</strong>
								<br>
								7195 JohnsonDoes Ave, Suite 320
								<br>
								San Francisco, CA 881234
								<br> <abbr title="Phone">P:</abbr>
								(123) 456-7890
							</address>
						</dd>
						<dt>Social:</dt>
						<dd>
							<a href="#" class='btn'><i class="icon-facebook"></i></a>
							<a href="#" class='btn'><i class="icon-twitter"></i></a>
							<a href="#" class='btn'><i class="icon-linkedin"></i></a>
							<a href="#" class='btn'><i class="icon-envelope"></i></a>
							<a href="#" class='btn'><i class="icon-rss"></i></a>
							<a href="#" class='btn'><i class="icon-github"></i></a>
						</dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
		</div>
	</div-->
	<div id="navigation">
		<div class="container-fluid">
			<a href="#" id="brand">SISTEMA DE INFORMACIÓN ACADÉMICO</a>
			<a href="#" class="toggle-nav" rel="tooltip" data-placement="bottom" title="Toggle navigation"><i class="icon-reorder"></i></a>
			<!--ul class='main-nav'>
				<li class='active'>
					<a href="index.html">
						<span>General</span>
					</a>
				</li>
				<li>
					<a href="index.html">
						<span>Administrar Usuarios</span>
					</a>
				</li>				
			</ul-->
			<div class="user">
				<ul class="icon-nav">
						
				</ul>
				<div class="dropdown">
					<a href="#" class='dropdown-toggle' data-toggle="dropdown">Bienvenido(a) <?=$userName?></a>
					<ul class="dropdown-menu pull-right">
						<!--li>
							<a href="more-userprofile.html">Edit profile</a>
						</li>
						<li>
							<a href="#">Account settings</a>
						</li-->
						<li>
							<a href="<?=$linkEnd?>">Terminar Sesion</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" id="content">
		<div id="left">

			
			
			<?php if(isset($salida["navigation"])){ include($salida["navigation"]); } ?>


			
		</div>
		<div id="main">
			<img class='banner-logo' src="http://www.ceruralrestrepo.com/sites/ceruralrestrepo.com/files/header.png">
			
			<div class="container-fluid">
					<?php if(isset($mensaje) && $mensaje<>""){  ?>
					<div class="alert alert-info">
						<h4>Mensaje:</h4>
						<?=$mensaje?>
					</div>
					<?php } ?> 
					
			      <?php if(isset($salida["content_main"])){ include($salida["content_main"]); } ?>
				  <div class="clear"></div>
			</div>
		</div>
	</div>
		
	</body>

	</html>

