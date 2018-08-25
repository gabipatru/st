<!-- Main -->
<div id="main">
  <div class="cl">&nbsp;</div>
  
  <!-- Main Sidebar -->
  <div id="sidebar">
  
  <div class="cl">&nbsp;</div>
  
  <?php include(VIEW_DIR. '/admin/_include/user_sidebar.php');?>
  
  <!-- Sidebar -->
  <div id="sidebar">
  
    <!-- Box -->
    <div class="box">
    
      <!-- Box Head -->
      <div class="box-head">
        <h2><?php echo $this->__('Management')?></h2>      
      </div>
      <!-- End Box Head-->
      
      <!-- Box Content -->
      <div class="box-content">
        <a href="<?php echo href_admin('user_groups/edit')?>" class="add-button">
          <span><?php echo $this->__('Add New User Group')?></span>
        </a>
        <div class="cl">&nbsp;</div>
      </div>
      <!-- End Box Content -->
  
    </div>
    <!-- End Box -->
    
  </div>
  <!-- End Sidebar -->
  
  <!-- End Main Sidebar -->
  </div>

  <!-- Content -->
  <div id="content">
  
  <?php if (count($oGroupsCollection)):?>
    <!-- Box -->
    <div class="box">
    
      <div class="box-head">
        <h2 class="left"><?php echo $this->__('User Groups List')?></h2>
      </div>
      
      <!-- Table -->
      <div class="table">
      
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th><?php echo $this->__('User Group ID')?></th>
            <th><?php echo $this->__('User Group Name')?></th>
            <th><?php echo $this->__('Status')?></th>
            <th><?php echo $this->____('Actions')?></th>
          </tr>
          
        <?php foreach ($oGroupsCollection as $oCat):?>
          <tr>
            <td><?php echo $oCat->getUserGroupId()?></td>
            <td><?php echo $oCat->getName()?></td>
            <td><?php echo $oCat->getStatus()?></td>
            <td>
              <span class="padding-right20">
                <a href="<?php echo href_admin('user_groups/edit', $oCat->getUserGroupId())?>" 
                   class="ico edit js-user-list-edit"
                >
                  <?php echo $this->__('Edit')?>
                </a>
              </span>
              <span class="padding-right20">
                <a href="<?php echo href_admin('user_groups/delete', $oCat->getUserGroupId())?>" 
                   class="ico del js-user-list-edit"
                   onclick="return confirm('<?php echo $this->__('Are you sure you want to delete this item ?')?>')"
                >
                  <?php echo $this->__('Delete')?>
                </a>
              </span>
            </td>
          </tr>
        <?php endforeach;?>
        
        </table>
      
      </div>
      <!-- End table -->
    
    </div>
    <!-- End Box -->
  <?php else:?>
    <h2><?php echo $this->__('No user groups could be found.');?></h2>
  <?php endif;?>
  
  </div>
  <!-- End Content -->

  <div class="cl">&nbsp;</div>
</div>
<!-- Main -->