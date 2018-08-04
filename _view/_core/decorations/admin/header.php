<body>

<!-- Header -->
<div id="header">
    <div class="shell">
        <!-- Logo + Top Nav -->
        <div id="top">
            <h1><a href="#">Admin Surprize Turbo</a></h1>
            <div id="top-navigation">
                <?php echo $this->__('Welcome')?>, <a href="#"><strong><?php echo $userName?></strong></a>
                <span>|</span>
                <a href="<?php echo href_website('user/logout')?>">Log out</a>
            </div>
        </div>
        <!-- End Logo + Top Nav -->
        
        <!-- Main Nav -->
        <div id="navigation">
            <ul>
                <li><a href="<?php echo href_admin('dashboard/stats')?>" class="<?php echo ($menu == 'dashboard' ? 'active' : '')?>"><span><?php echo $this->__('Dashboard')?></span></a></li>
                <li><a href="<?php echo href_admin('categories/list')?>" class="<?php echo ($menu == 'categories' ? 'active' : '')?>"><span><?php echo $this->__('Categories')?></span></a></li>
                <li><a href="<?php echo href_admin('series/list')?>" class="<?php echo ($menu == 'series' ? 'active' : '')?>"><span><?php echo $this->__('Series')?></span></a></li>
                <li><a href="<?php echo href_admin('config/list_items')?>" class="<?php echo ($menu == 'config' ? 'active' : '')?>"><span><?php echo $this->__('Config')?></span></a></li>
                <li><a href="<?php echo href_admin('cache/list_cache')?>" class="<?php echo ($menu == 'cache' ? 'active' : '')?>"><span><?php echo $this->__('Cache')?></span></a></li>
                <li><a href="<?php echo href_admin('users/list_users')?>" class="<?php echo ($menu == 'users' ? 'active' : '')?>"><span><?php echo $this->__('Users')?></span></a></li>
                <li><a href="<?php echo href_admin('email/list_menu')?>" class="<?php echo ($menu == 'email' ? 'active' : '')?>"><span><?php echo $this->__('Email')?></span></a></li>
            </ul>
        </div>
        <!-- End Main Nav -->
    </div>
</div>
<!-- End Header -->

<!-- Container -->
<div id="container">
    <div class="shell">
    
        <?php include(VIEW_INCLUDES_DIR . '/breadcrumbs.php');?>
        
        <?php include(VIEW_INCLUDES_DIR .'/messages.php');?>