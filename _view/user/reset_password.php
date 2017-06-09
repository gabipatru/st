<div id="form-page">
	<div class="bg">
		<div class="row">
			<div class="col-sm-4">
				<h2 class="title text-center"><?php echo __('Reset password')?></h2>
			</div>
		</div>
<?php if (!$error):?>
	<div class="row">  
			<div class="col-sm-4">
				<div class="contact-form">
<form id="reset_passwd" class="contact-form row" action="<?php echo MVC_ACTION_URL?>" method="post">

	<div class="form-group col-md-12">
		<span><?php echo __('New password')?>:</span>
		<input type="password" class="form-control" name="password" id="password">
		<label id="password-error" class="error" for="password"><?php echo $FV->password_error?></label>
	</div>
	
	<div class="form-group col-md-12">
		<span><?php echo __('Retype new password')?>:</span>
		<input type="password" class="form-control" name="password2">
		<label id="password2-error" class="error" for="password2"><?php echo $FV->password2_error?></label>
	</div>
	
	<input type="hidden" name="code" value="<?php echo $confirmationCode?>" />
	<input type="hidden" name="token" value="<?php echo securityGetToken()?>" />
	<div class="form-group col-md-12">
		<input type="submit" class="btn btn-primary pull-right" value="<?php echo __('Reset')?>" />
	</div>

</form>
<?php echo $FV->_js_code;?>

				</div>
			</div>
		</div> <!-- End row -->
<?php endif;?>
	</div> <!-- End bg -->
</div> <!-- End form page -->