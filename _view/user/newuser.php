<h1>New user</h1>
<form id="newuser" action="<?php echo MVC_ACTION_URL?>" method="post">
	<p>
		<span><?php echo _('Email address')?>:</span>
		<input type="text" name="email" value="<?php echo $FV->email?>">
		<label id="email-error" class="error" for="email"><?php echo $FV->email_error?></label>
	</p>
	<p>
		<span><?php echo _('Username')?>:</span>
		<input type="text" name="username" value="<?php echo $FV->username?>">
		<label id="username-error" class="error" for="username"><?php echo $FV->username_error?></label>
	</p>
	<p>
		<span><?php echo _('Password')?>:</span>
		<input type="password" name="password">
		<label id="password-error" class="error" for="password"><?php echo $FV->password_error?></label>
	</p>
	<p>
		<span><?php echo _('Retype Password')?>:</span>
		<input type="password" name="password2">
		<label id="password2-error" class="error" for="password2"><?php echo $FV->password2_error?></label>
	</p>
	<p>
		<span><?php echo _('First Name')?>:</span>
		<input type="text" name="first_name" value="<?php echo $FV->first_name?>">
		<label id="first_name-error" class="error" for="first_name"><?php echo $FV->first_name_error?></label>
	</p>
	<p>
		<span><?php echo _('Last Name')?>:</span>
		<input type="text" name="last_name" value="<?php echo $FV->last_name?>">
		<label id="last_name-error" class="error" for="last_name"><?php echo $FV->last_name_error?></label>
	</p>
	
	<input type="submit" value="<?php echo _('Create')?>">
</form>