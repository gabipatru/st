<div id="form-page">
  <div class="bg">
    <div class="row">
      <div class="col-sm-12">
        <h2 class="title text-center"><?php echo $this->__('Create account')?></h2>
      </div>
    </div>

    <?php if (!$userAdded) : ?>
      <div class="row">
        <div class="col-sm-4">
          <div class="contact-form">
            <form id="newUser" class="contact-form row" action="<?php echo MVC_ACTION_URL?>" method="post">
              <div class="form-group col-md-12">
                <span><?php echo $this->__('Email address')?>:</span>
                <input type="text" class="form-control" id="email" name="email" placeholder="<?php echo $this->__('Email address')?>" value="<?php echo $FV->email?>">
                <label id="email-error" class="error" for="email"><?php echo $FV->email_error?></label>
              </div>
              <div class="form-group col-md-12">
                <span><?php echo $this->__('Username')?>:</span>
                <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo $this->__('Username')?>" value="<?php echo $FV->username?>">
                <label id="username-error" class="error" for="username"><?php echo $FV->username_error?></label>
              </div>
              <div class="form-group col-md-12">
                <span><?php echo $this->__('Password')?>:</span>
                <meter id="password-strength-meter" max="4"></meter>
                <span id="password-strength-text"></span>
                <input type="password" class="form-control" id="password" name="password">
                <label id="password-error" class="error" for="password"><?php echo $FV->password_error?></label>
              </div>
              <div class="form-group col-md-12">
                <span><?php echo $this->__('Retype Password')?>:</span>
                <input type="password" class="form-control" id="password2" name="password2">
                <label id="password2-error" class="error" for="password2"><?php echo $FV->password2_error?></label>
              </div>
              <div class="form-group col-md-12">
                <span><?php echo $this->__('First Name')?>:</span>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php echo $this->__('First Name')?>" value="<?php echo $FV->first_name?>">
                <label id="first_name-error" class="error" for="first_name"><?php echo $FV->first_name_error?></label>
              </div>
              <div class="form-group col-md-12">
                <span><?php echo $this->__('Last Name')?>:</span>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php echo $this->__('Last Name')?>" value="<?php echo $FV->last_name?>">
                <label id="last_name-error" class="error" for="last_name"><?php echo $FV->last_name_error?></label>
              </div>
    
              <div class="form-group col-md-12">
                <input type="submit" id="submit-form" class="btn btn-primary pull-right" value="<?php echo $this->__('Create User')?>">
              </div>
              <input type="hidden" name="token" value="<?php echo $this->securityGetToken()?>">
            </form>
          <?php echo $FV->_js_code;?>

          </div>
        </div>
      </div> <!-- End row -->
    <?php endif;?>
  </div> <!-- End bg -->
</div> <!-- End form page -->