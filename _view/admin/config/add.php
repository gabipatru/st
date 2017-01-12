<!-- Main -->
<div id="main">
	<div class="cl">&nbsp;</div>
	
	<!-- Content -->
	<div id="content">
		
		<!-- Box -->
		<div class="box">
			
			<div class="box-head">
				<h2>Add New Article</h2>
			</div>
			
			<form id="addForm" action="<?php echo MVC_MODULE_URL?>/add.html" method="post">
				<!-- Form -->
				<div class="form">
				
					<p>
						<span class="req">max 255 symbols</span>
						<label>Path <span>(Required Field)</span></label>
						<input type="text" class="field size1" name="path" />
					</p>
					
					<p>
						<label>Value</label>
						<textarea rows="6" cols="107" name="value"></textarea>
					</p>
				
				</div>
				<!-- End Form -->
				
				<div class="buttons">
					<input type="submit" class="button" value="Save" />
				</div>
			</form>
			<?php echo $FV->_js_code;?>
		
		</div>
		<!-- End Box -->
		
	</div>
	<!-- End Content -->
			
	<div class="cl">&nbsp;</div>			
</div>
<!-- Main -->