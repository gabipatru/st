<div id="form-page">
	<div class="bg">
		<div class="row">    		
			<div class="col-sm-4">    			   			
				<h2 class="title text-center"><?php echo __('Forgot my password')?></h2>
			</div>			 		
		</div>
<?php if (!$emailSent):?>
	<div class="row">  
			<div class="col-sm-4">
				<div class="contact-form">
<form id="forgot_passwd" class="contact-form row" action="<?php echo MVC_ACTION_URL?>" method="post">

	<div class="form-group col-md-12">
		<span><?php echo __('Email')?>:</span>
		<input type="text" class="form-control" name="email" placeholder="<?php echo __('Email address')?>" value="<?php echo $FV->email?>">
		<label id="email-error" class="error" for="email"><?php echo $FV->email_error?></label>
	</div>
	
	<input type="hidden" name="token" value="<?php echo securityGetToken()?>" />
	<div class="form-group col-md-12">
		<input type="submit" class="btn btn-primary pull-right" value="<?php echo __('Send')?>" />
	</div>

</form>
<?php echo $FV->_js_code;?>

				</div>
			</div>
		</div> <!-- End row -->
<?php endif;?>
	</div> <!-- End bg -->
</div> <!-- End form page -->