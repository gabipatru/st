<div id="form-page">
    <div class="bg">
        <div class="row">            
            <div class="col-sm-12">                               
                <h2 class="title text-center"><?php echo $this->__('Contact Us')?></h2>                                                        
            </div>                     
        </div>
<?php if ($messageSent):?>
    <h3><?php echo $this->__('The message was sent. Thank you.')?></h3>
<?php else:?>
        <div class="row">      
            <div class="col-sm-8">
                <div class="contact-form">
                    <h2 class="title text-center"><?php echo $this->__('Get In Touch')?></h2>
                    <form id="contact-form" class="contact-form row" name="contact-form" method="post">
                        <div class="form-group col-md-6">
                            <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo $this->__('Name')?>" value="<?php echo $FV->name?>">
                            <label id="path-error" class="error" for="path"><?php echo $FV->name_error?></label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="email" id="email" name="email" class="form-control" placeholder="<?php echo $this->__('Email address')?>" value="<?php echo $FV->email?>">
                            <label id="path-error" class="error" for="path"><?php echo $FV->email_error?></label>
                        </div>
                        <div class="form-group col-md-12">
                            <input type="text" id="subject" name="subject" class="form-control" placeholder="<?php echo $this->__('Subject')?>" value="<?php echo $FV->subject?>">
                            <label id="path-error" class="error" for="path"><?php echo $FV->subject_error?></label>
                        </div>
                        <div class="form-group col-md-12">
                            <textarea name="message" id="message" class="form-control" rows="8" placeholder="<?php echo $this->__('Your Message')?>"><?php echo $FV->message?></textarea>
                            <label id="path-error" class="error" for="path"><?php echo $FV->message_error?></label>
                        </div>                        
                        <div class="form-group col-md-12">
                            <input type="submit" id="submit-form" name="submit" class="btn btn-primary pull-right" value="<?php echo $this->__('Send')?>">
                        </div>
                        <input type="hidden" name="token" value="<?php echo $this->securityGetToken()?>">
                    </form>
                    <?php echo $FV->_js_code;?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="contact-info">
                    <h2 class="title text-center"><?php echo $this->__('Contact Info')?></h2>
                    <address>
                        <p>Surprize Turbo</p>
                        <p><?php echo ($oTranslations->getLanguage() == 'en' ? Config::configByPath('HTML/Contact Page/Address EN') : Config::configByPath('HTML/Contact Page/Address RO'))?></p>
                        <p><?php echo ($oTranslations->getLanguage() == 'en' ? Config::configByPath('HTML/Contact Page/City EN') : Config::configByPath('HTML/Contact Page/City RO'))?></p>
                        <p><?php echo $this->__('Mobile')?>: <?php echo ($oTranslations->getLanguage() == 'en' ? Config::configByPath('HTML/Header/Phone Number EN') : Config::configByPath('HTML/Header/Phone Number RO'))?></p>
                    </address>
                    <div class="social-networks">
                        <h2 class="title text-center"><?php echo $this->__('Social Networking')?></h2>
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
</div> <!-- End form page -->