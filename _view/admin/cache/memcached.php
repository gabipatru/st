<!-- Main -->
<div id="main">
	<div class="cl">&nbsp;</div>
	
	<!-- Content -->
	<div id="content">
	
		<!-- Box -->
		<div class="box">
		
			<!-- Box Head -->
			<div class="box-head">
				<h2 class="left"><?php echo __('Memcached Stats')?></h2>
			</div>
			<!-- End Box Head -->
			
			<!-- Table -->
			<div class="table">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php foreach ($aMemcacheStats as $statName => $statValue):?>
				<tr>
					<td><h3><?php echo $statName?></h3></td>
					<td><?php echo $statValue?></td>
				</tr>
				<?php endforeach;?>
				</table>
			</div>
			<!-- End Table -->
		
		</div>
		<!-- End Box -->
		
		<!-- Box -->
		<div class="box">
			
			<!-- Box Head -->
			<div class="box-head">
				<h2 class="left"><?php echo __('Clear one Memcached key')?></h2>
			</div>
			<!-- End Box Head -->
			
			<form id="memcacheFlush" action="<?php echo MVC_MODULE_URL?>/flush_memcached.html" method="post">
			
				<!-- Form -->
				<div class="form">
					<p>
						<label><?php echo __('Flush one Memcached key')?> <span>(<?php echo __('Required Field')?>)</span></label>
						<input type="text" name="memcached_key" class="field size1" />
					</p>
				</div>
				<!-- End Form -->
				
				<!-- Form Buttons -->
				<div class="buttons">
					<input type="submit" class="button" value="<?php echo __('Flush')?> !" />
				</div>
				<!-- End Form Buttons -->
				<input type="hidden" name="token" value="<?php echo securityGetToken()?>">
				<?php echo $FV->_js_code;?>
			</form>
		
		</div>
		<!-- End Box -->
		
		<!-- Box -->
		<div class="box">
		
			<!-- Box Head -->
			<div class="box-head">
				<h2 class="left"><?php echo __('Clear all Memcached keys')?></h2>
			</div>
			<!-- End Box Head -->
			
			<form id="memcacheAllFlush" action="<?php echo MVC_MODULE_URL?>/flush_all_memcached.html" method="post">
				<!-- Form -->
				<div class="form">
					<p><label><?php echo __('Flush all Memcached keys')?> ! </label></p>
				</div>
				<!-- End Form -->
				
				<!-- Form Buttons -->
				<div class="buttons">
					<input type="submit" class="button" value="<?php echo __('Flush')?> !" />
				</div>
				<input type="hidden" name="token" value="<?php echo securityGetToken()?>">
				<!-- End Form Buttons -->
			</form>
		
		</div>
		<!-- End Box -->
	
	</div>
	<!-- End Content -->
	
	<div class="cl">&nbsp;</div>

</div>
<!-- Main -->