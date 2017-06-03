<div id="form-page">
	<div class="bg">
		<div class="row">    		
			<div class="col-sm-12">    			   			
				<h2 class="title text-center"><?php echo __('Login')?></h2>    			    				    				
			</div>			 		
		</div>
	
		<div class="row">  
			<div class="col-sm-4">
				<div class="contact-form">
				
<form id="login" class="contact-form row" action="<?php echo MVC_ACTION_URL?>" method="post">
	<div class="form-group col-md-12">
		<span><?php echo __('Username')?>:</span>
		<input type="text" class="form-control" name="username" value="<?php echo $FV->username?>">
		<label id="username-error" class="error" for="username"><?php echo $FV->username_error?></label>
	</div>
	<div class="form-group col-md-12">
		<span><?php echo __('Password')?>:</span>
		<input type="password" class="form-control" name="password">
		<label id="password-error" class="error" for="password"><?php echo $FV->password_error?></label>
	</div>
	
	<input type="hidden" name="token" value="<?php echo securityGetToken()?>" />
	<input type="hidden" name="return" value="<?php echo (!empty($FV->return) ? $FV->return : $return)?>" />
	<div class="form-group col-md-12">
		<input type="submit" class="btn btn-primary pull-right" value="<?php echo __('Login')?>" />
	</div>
</form>
<?php echo $FV->_js_code;?>

				</div>
			</div>
		</div> <!-- End row -->
	</div> <!-- End bg -->
</div> <!-- End form page -->