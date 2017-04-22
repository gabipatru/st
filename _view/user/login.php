<h1>Login</h1>
<form id="login" action="<?php echo MVC_ACTION_URL?>" method="post">
	<p>
		<span><?php echo _('Username')?>:</span>
		<input type="text" name="username">
	</p>
	<p>
		<span><?php echo _('Password')?>:</span>
		<input type="password" name="password">
	</p>
	
	<input type="submit" value="<?php echo _('Login')?>">
</form>