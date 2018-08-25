<!-- Sidebar -->
<div id="sidebar">
  
    <!-- Box -->
  <div class="box">
  
    <!-- Box Head -->
    <div class="box-head">
      <h2><?php echo $this->__('User menu')?></h2>
    </div>
      <!-- End Box Head-->
      
      <div class="box-content">
      <a href="<?php echo href_admin('users/list_users')?>" class="box-link"><?php echo $this->__('Users list')?></a>
      <a href="<?php echo href_admin('user_groups/list')?>" class="box-link">
        <?php echo $this->__('User Groups List')?>
      </a>
    <div class="cl">&nbsp;</div>
    </div>
  
  </div>
    <!-- End Box -->

</div>
<!-- End Sidebar -->