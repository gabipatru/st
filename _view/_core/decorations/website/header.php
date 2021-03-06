<body class="landing">
  <header id="header">
    <div class="header_top">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="contactinfo">
              <ul class="nav nav-pills">
                <li><a href="#"><i class="fa fa-phone"></i><?php echo ($oTranslations->getLanguage() == 'en_EN' ? Config::configByPath('HTML/Header/Phone Number EN') : Config::configByPath('HTML/Header/Phone Number RO'))?></a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="social-icons pull-right">
              <ul class="nav navbar-nav">
                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="header-middle">
      <div class="container">
        <div class="row">
          <div class="col-sm-4">
            <div class="logo pull-left">
              <a href="<?php echo href_website('website/homepage')?>"><div id="logo-top"></div></a>
            </div>
            <div class="btn-group pull-right">
              <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                  <?php echo ($oTranslations->getLanguage() == 'ro_RO' ? 'RO' : '')?>
                  <?php echo ($oTranslations->getLanguage() == 'en_EN' ? 'EN' : '')?>
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo href_website('website/save_language', CURRENT_URL)?>&language=en_EN"><div class="flag-en"></div> <span>EN</span></a></li>
                  <li><a href="<?php echo href_website('website/save_language', CURRENT_URL)?>&language=ro_RO"><div class="flag-ro"></div> <span>RO</span></a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="shop-menu pull-right">
              <ul class="nav navbar-nav">
                <?php $user = User::theUser()?>
                <?php if ($user instanceof SetterGetter && $user->getIsAdmin()) : ?>
                  <li><a href="<?php echo href_admin('dashboard/stats')?>"><i class="fa fa-bomb"></i> <?php echo $this->__('Admin')?></a></li>
                <?php endif;?>
                <?php if (User::isLoggedIn()) : ?>
                  <li><a href="#"><i class="fa fa-user"></i> <?php echo $this->__('Account')?></a></li>
                  <li><a href="<?php echo href_website('user/logout')?>"><i class="fa fa-share"></i> <?php echo $this->__('Logout')?></a></li>
                <?php else : ?>
                  <li><a href="<?php echo href_website('user/login')?>"><i class="fa fa-key"></i> <?php echo $this->__('Login')?></a></li>
                  <li><a href="<?php echo href_website('user/create_account')?>"><i class="fa fa-user"></i> <?php echo $this->__('Create account')?></a></li>
                <?php endif;?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="header-bottom">
      <div class="container">
        <div class="row">
          <div class="col-sm-9">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <div class="mainmenu pull-left">
              <ul class="nav navbar-nav collapse navbar-collapse">
                <li><a href="<?php echo href_website('website/homepage')?>" class="<?php echo (MVC_ACTION == 'homepage' ? 'active' : '')?>"><?php echo $this->__('Home')?></a></li>
                <li><a href="<?php echo href_website('website/contact')?>" class="<?php echo (MVC_ACTION == 'contact' ? 'active' : '')?>"><?php echo $this->__('Contact')?></a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="search_box pull-right">
              <input type="text" placeholder="<?php echo $this->__('Search')?>"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="container">

<?php include(VIEW_INCLUDES_DIR . '/messages.php');?>