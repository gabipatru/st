<h1>New user</h1>
<form id="newuser" action="<?php echo MVC_ACTION_URL?>" method="post">
	<p>
		<span><?php echo _('Email address')?>:</span>
		<input type="text" name="email">
	</p>
	<p>
		<span><?php echo _('Username')?>:</span>
		<input type="text" name="username">
	</p>
	<p>
		<span><?php echo _('Password')?>:</span>
		<input type="password" name="password">
	</p>
	<p>
		<span><?php echo _('Repeat Password')?>:</span>
		<input type="password" name="password2">
	</p>
	<p>
		<span><?php echo _('First Name')?>:</span>
		<input type="text" name="first_name">
	</p>
	<p>
		<span><?php echo _('Last Name')?>:</span>
		<input type="text" name="last_name">
	</p>
	
	<input type="submit" value="<?php echo _('Create')?>">
</form>