<?php
foreach($menuList as $key=>$submenu){
?>
	<div class="subnav">
		<div class="subnav-title">
			<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span><?=$key?></span></a>
		</div>
		<ul class="subnav-menu">
			<?php
				foreach($submenu as $namesubmenu=>$value){
			?>	
					<li>
						<a  href="<?=$this->makeURL($value['PARAMETRO'],$value['PAGINA'])?>">
							<i class="icon-plus"></i>
							<?=$value['NOMBRE']?>
						</a>
					</li>

			<?php								
				}
			?>
		</ul>
	</div>							
	

<?php								
}
?>	


