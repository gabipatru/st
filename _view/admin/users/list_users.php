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
			<th>
				<?php echo ($sort == 'user_id' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
				<a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('user_id', $sort_crit)?>">
					<?php echo __('ID')?>
				</a>
			</th>
			<th>
				<?php echo ($sort == 'username' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
				<a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('username', $sort_crit)?>">
					<?php echo __('Username')?>
				</a>
			</th>
			<th>
				<?php echo ($sort == 'email' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
				<a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('email', $sort_crit)?>">
					<?php echo __('Email')?>
				</a>
			</th>
			<th>
				<?php echo ($sort == 'first_name' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
				<a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('first_name', $sort_crit)?>">
					<?php echo __('First Name')?>
				</a>
			</th>
			<th>
				<?php echo ($sort == 'last_name' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
				<a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('last_name', $sort_crit)?>">
					<?php echo __('Last Name')?>
				</a>
			</th>
			<th><?php echo __('Status')?></th>
			<th>
				<?php echo ($sort == 'created_at' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
				<a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('created_at', $sort_crit)?>">
					<?php echo __('Created at')?>
				</a>
			</th>
			<th>
				<?php echo ($sort == 'last_login' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
				<a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('last_login', $sort_crit)?>">
					<?php echo __('Last login')?>
				</a>
			</th>
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