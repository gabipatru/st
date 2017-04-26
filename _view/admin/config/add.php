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
						<div id="div-textarea" class="hidden">
							<textarea rows="6" cols="107" name="value" class="field size1"><?php echo $FV->value;?></textarea>
						</div>
						<div id="div-text" class="hidden">
							<input type="text" name="value" class="field size1" value="<?php echo $FV->value;?>" />
						</div>
						<div id="div-yesno" class="hidden">
							<select name="value" class="field size3">
								<option value="1"><?php echo __('Yes')?></option>
								<option value="0"><?php echo __('No')?></option>
							</select>
						</div>
					</p>
					
					<p>
						<label><?php echo __('Type')?></label>
						<select id="typeSelect" name="type" class="field size3">
							<option value=""><?php echo __('Choose a type')?></option>
							<option value="text"><?php echo __('Simple text config')?></option>
							<option value="textarea"><?php echo __('Textarea config')?></option>
							<option value="yesno"><?php echo __('Yes / No config')?></option>
						</select>
						<label id="type-error" class="error" for="type"><?php echo $FV->type_error?></label>
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