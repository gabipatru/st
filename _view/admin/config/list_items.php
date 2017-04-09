<!-- Main -->
<div id="main">
	<div class="cl">&nbsp;</div>
			
	<!-- Sidebar -->
	<div id="sidebar">
				
	<!-- Box -->
	<div class="box">
			
		<!-- Box Head -->
		<div class="box-head">
			<h2>Management</h2>
		</div>
		<!-- End Box Head-->
					
		<div class="box-content">
			<a href="<?php echo MVC_MODULE_URL . '/add.html'?>" class="add-button"><span>Add new Config</span></a>
		<div class="cl">&nbsp;</div>
		</div>
	</div>
	
	<!-- Box -->
	<div class="box">
			
		<!-- Box Head -->
		<div class="box-head">
			<h2>Config</h2>
		</div>
		<!-- End Box Head-->
					
		<div class="box-content">
			<?php foreach ($aConfig as $configName => $ConfigItem):?>
				<a href="<?php echo CURRENT_URL?>?name=<?php echo $configName?>" class="box-link"><?php echo $configName;?></a>
			<?php endforeach;?>
		<div class="cl">&nbsp;</div>
		</div>
	</div>
	<!-- End Box -->
	</div>
	<!-- End Sidebar -->
			
	<div class="cl">&nbsp;</div>			
</div>
<!-- Main -->