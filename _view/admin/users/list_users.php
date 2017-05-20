<!-- Main -->
<div id="main">
	<div class="cl">&nbsp;</div>
	
	<!-- Content -->
	<div id="content">
	
	<?php if (!$oUserCol):?>
		<p><?php __('No users found')?></p>
	<?php else:?>
		<!-- Box -->
		<div class="box">
		
			<!-- Box Head -->
			<div class="box-head">
			
			<!-- Search and filters form -->
			<form id="searchAndFilters" method="get" action="<?php echo MVC_ACTION_URL?>" >
				<h2 class="left"><?php echo __('Users list')?></h2>
				<div class="right">
					<label><?php echo __('Search for users')?></label>
					<input name="search" type="text" class="field small-field" value="<?php echo $search?>" />
					<input type="submit" class="button" value="<?php echo __('Search')?>" />
				</div>
				
				<div class="cl">&nbsp;</div>
				<div class="box-continue">
					<h2 class="left"><?php echo __('Filter')?></h2>
					<div class="right">
						<label><?php echo __('Filter by status')?></label>
						<span class="GF-select"><?php echo $GF->GFSelect('status');?></span>
					</div>
				</div>
				
				<input type="hidden" name="page" value="<?php echo $oPagination->getPage()?>">
			</form>	
			<!-- End search and filters form -->
			
			</div>
			<!-- End Box Head -->
		
		<!-- Table -->
		<div class="table">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th><?php echo __('ID')?></th>
				<th><?php echo __('Username')?></th>
				<th><?php echo __('Email')?></th>
				<th><?php echo __('First Name')?></th>
				<th><?php echo __('Last Name')?></th>
				<th><?php echo __('Status')?></th>
				<th><?php echo __('Created at')?></th>
				<th><?php echo __('Last login')?></th>
			</tr>
		<?php foreach ($oUserCol as $user):?>
			<tr>
				<td><?php echo $user->getUserId()?></td>
				<td><?php echo $user->getUsername()?></td>
				<td><?php echo $user->getEmail()?></td>
				<td><?php echo $user->getFirstName()?></td>
				<td><?php echo $user->getLastName()?></td>
				<td><?php echo $user->getStatus()?></td>
				<td><?php echo $user->getCreatedAt()?></td>
				<td><?php echo $user->getLastLogin()?></td>
			</tr>
		<?php endforeach;?>
		</table>
		
		<div class="pagging">
			<?php echo $oPagination->getHtml()?>
		</div>
		
		</div> <!-- End table -->
		</div> <!-- End box -->
	<?php endif;?>
	
	</div>
	<!-- End Content -->
	
	<div class="cl">&nbsp;</div>

</div>
<!-- Main -->