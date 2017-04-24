<!-- Main -->
<div id="main">
	<div class="cl">&nbsp;</div>
	
	<!-- Content -->
	<div id="content">
		
		<!-- Box -->
		<div class="box">
			
			<div class="box-head">
				<h2><?php echo __('Add New Article')?></h2>
			</div>
			
			<form id="addForm" action="<?php echo MVC_MODULE_URL?>/add.html" method="post">
				<!-- Form -->
				<div class="form">
				
					<p>
						<span class="req"><?php echo __('max 255 symbols')?></span>
						<label><?php echo __('Path')?> <span>(<?php echo __('Required Field')?>)</span></label>
						<input type="text" class="field size1" name="path" value="<?php echo $FV->path;?>" />
						<label id="path-error" class="error" for="path"><?php echo $FV->path_error?></label>
					</p>
					
					<p>
						<label><?php echo __('Value')?></label>
						<textarea rows="6" cols="107" name="value"><?php echo $FV->value;?></textarea>
					</p>
				
				</div>
				<!-- End Form -->
				
				<div class="buttons">
					<input type="submit" class="button" value="<?php echo __('Save')?>" />
				</div>
				<input type="hidden" name="token" value="<?php echo securityGetToken()?>">
			</form>
			<?php echo $FV->_js_code;?>
		
		</div>
		<!-- End Box -->
		
	</div>
	<!-- End Content -->
			
	<div class="cl">&nbsp;</div>			
</div>
<!-- Main -->