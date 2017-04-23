<h1>Login</h1>
<form id="login" action="<?php echo MVC_ACTION_URL?>" method="post">
	<p>
		<span><?php echo __('Username')?>:</span>
		<input type="text" name="username" value="<?php echo $FV->username?>">
		<label id="username-error" class="error" for="username"><?php echo $FV->username_error?></label>
	</p>
	<p>
		<span><?php echo __('Password')?>:</span>
		<input type="password" name="password">
		<label id="password-error" class="error" for="password"><?php echo $FV->password_error?></label>
	</p>
	
	<input type="hidden" name="token" value="<?php echo securityGetToken()?>">
	<input type="submit" value="<?php echo __('Login')?>">
</form>
<?php echo $FV->_js_code;?>