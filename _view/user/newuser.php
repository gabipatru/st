<div id="form-page">
	<div class="bg">
		<div class="row">    		
			<div class="col-sm-12">    			   			
				<h2 class="title text-center"><?php echo __('Create account')?></h2>    			    				    				
			</div>			 		
		</div>
	
		<div class="row">  
			<div class="col-sm-4">
				<div class="contact-form">
				
<form id="newUser" class="contact-form row" action="<?php echo MVC_ACTION_URL?>" method="post">
	<div class="form-group col-md-12">
		<span><?php echo __('Email address')?>:</span>
		<input type="text" class="form-control" name="email" value="<?php echo $FV->email?>">
		<label id="email-error" class="error" for="email"><?php echo $FV->email_error?></label>
	</div>
	<div class="form-group col-md-12">
		<span><?php echo __('Username')?>:</span>
		<input type="text" class="form-control" name="username" value="<?php echo $FV->username?>">
		<label id="username-error" class="error" for="username"><?php echo $FV->username_error?></label>
	</div>
	<div class="form-group col-md-12">
		<span><?php echo __('Password')?>:</span>
		<input type="password" class="form-control" name="password" id="password">
		<label id="password-error" class="error" for="password"><?php echo $FV->password_error?></label>
	</div>
	<div class="form-group col-md-12">
		<span><?php echo __('Retype Password')?>:</span>
		<input type="password" class="form-control" name="password2">
		<label id="password2-error" class="error" for="password2"><?php echo $FV->password2_error?></label>
	</div>
	<div class="form-group col-md-12">
		<span><?php echo __('First Name')?>:</span>
		<input type="text" class="form-control" name="first_name" value="<?php echo $FV->first_name?>">
		<label id="first_name-error" class="error" for="first_name"><?php echo $FV->first_name_error?></label>
	</div>
	<div class="form-group col-md-12">
		<span><?php echo __('Last Name')?>:</span>
		<input type="text" class="form-control" name="last_name" value="<?php echo $FV->last_name?>">
		<label id="last_name-error" class="error" for="last_name"><?php echo $FV->last_name_error?></label>
	</div>
	
	<div class="form-group col-md-12">
		<input type="submit" class="btn btn-primary pull-right" value="<?php echo __('Create User')?>">
	</div>
	<input type="hidden" name="token" value="<?php echo securityGetToken()?>">
</form>
<?php echo $FV->_js_code;?>

				</div>
			</div>
		</div> <!-- End row -->
	</div> <!-- End bg -->
</div> <!-- End form page -->