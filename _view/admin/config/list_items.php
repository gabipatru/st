<!-- Main -->
<div id="main">
	<div class="cl">&nbsp;</div>
	
	<!-- Content -->
	<div id="content">
	
	<?php if ($configName):?>
	<form id="saveForm" action="<?php echo MVC_MODULE_URL?>/save_all.html" method="post">
		<input type="hidden" name="configName" value="<?php echo $configName?>" />
	<?php foreach ($aConfig[$configName] as $configKey => $aConfigItem):?>
		<!-- Box -->
		<div class="box">
		
			<a href="#">
			<!-- Box Head -->
			<div class="box-head">
				<h2 class="left"><?php echo $configKey?></h2>
				<div class="right">
					<label>expand</label>
				</div>
			</div>
			<!-- End Box Head -->
			</a>
			
			<!-- Table -->
			<div class="table">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php foreach ($aConfigItem as $itemName => $aItem):?>
				<tr>
					<td><h3><?php echo $itemName?></h3></td>
					<td><input type="text" name="config<?php echo $aItem['config_id']?>" value="<?php echo $aItem['value']?>" /></td>
				</tr>
				<input type="hidden" name="config_ids[]" value="<?php echo $aItem['config_id']?>" />
				<?php endforeach;?>
				</table>
			</div>
			<!-- End Table -->
		
		</div>
		<!-- End Box -->
	<?php endforeach;?>
		<input type="submit" class="button" value="Save" />
	</form>
	<?php endif;?>
	
	</div>
	<!-- End Content -->
			
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
			<a href="<?php echo href_admin('config/add')?>" class="add-button"><span>Add new Config</span></a>
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
				<a href="<?php echo MVC_ACTION_URL?>?name=<?php echo $configName?>" class="box-link"><?php echo $configName;?></a>
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