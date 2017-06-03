<div id="contact-page" class="container">
	<div class="bg">
		<div class="row">    		
			<div class="col-sm-12">    			   			
				<h2 class="title text-center"><?php echo __('Contact Us')?></h2>    			    				    				
			</div>			 		
		</div>
<?php if ($messageSent):?>
	<h3><?php echo __('The message was sent. Thank you.')?></h3>
<?php else:?>
    	<div class="row">  	
	    	<div class="col-sm-8">
	    		<div class="contact-form">
	    			<h2 class="title text-center"><?php echo __('Get In Touch')?></h2>
	    			<div class="status alert alert-success" style="display: none"></div>
			    	<form id="main-contact-form" class="contact-form row" name="contact-form" method="post">
			            <div class="form-group col-md-6">
			                <input type="text" name="name" class="form-control" placeholder="<?php echo __('Name')?>">
			            </div>
			            <div class="form-group col-md-6">
			                <input type="email" name="email" class="form-control" placeholder="<?php echo __('Email address')?>">
			            </div>
			            <div class="form-group col-md-12">
			                <input type="text" name="subject" class="form-control" placeholder="<?php echo __('Subject')?>">
			            </div>
			            <div class="form-group col-md-12">
			                <textarea name="message" id="message" class="form-control" rows="8" placeholder="<?php echo __('Your Message')?>"></textarea>
			            </div>                        
			            <div class="form-group col-md-12">
			                <input type="submit" name="submit" class="btn btn-primary pull-right" value="<?php echo __('Send')?>">
			            </div>
			        </form>
	    		</div>
	    	</div>
	    	<div class="col-sm-4">
	    		<div class="contact-info">
	    			<h2 class="title text-center"><?php echo __('Contact Info')?></h2>
	    			<address>
	    				<p>Surprize Turbo</p>
						<p><?php echo ($oTranslations->getLanguage() == 'en' ? Config::configByPath('HTML/Contact Page/Address EN') : Config::configByPath('HTML/Contact Page/Address RO'))?></p>
						<p><?php echo ($oTranslations->getLanguage() == 'en' ? Config::configByPath('HTML/Contact Page/City EN') : Config::configByPath('HTML/Contact Page/City RO'))?></p>
						<p><?php echo __('Mobile')?>: <?php echo ($oTranslations->getLanguage() == 'en' ? Config::configByPath('HTML/Header/Phone Number EN') : Config::configByPath('HTML/Header/Phone Number RO'))?></p>
	    			</address>
	    			<div class="social-networks">
	    				<h2 class="title text-center"><?php echo __('Social Networking')?></h2>
						<ul>
							<li>
								<a href="#"><i class="fa fa-facebook"></i></a>
							</li>
							<li>
								<a href="#"><i class="fa fa-twitter"></i></a>
							</li>
							<li>
								<a href="#"><i class="fa fa-google-plus"></i></a>
							</li>
							<li>
								<a href="#"><i class="fa fa-youtube"></i></a>
							</li>
						</ul>
	    			</div>
	    		</div>
    		</div>    			
	    </div>
<?php endif;?>
	</div> <!-- End bg -->
</div><!-- End contact-page-->