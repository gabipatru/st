<!-- Main -->
<div id="main">
  <div class="cl">&nbsp;</div>
  
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
        <a href="<?php echo href_admin('categories/edit')?>" class="add-button">
          <span><?php echo $this->__('Add New Category')?></span>
        </a>
        <div class="cl">&nbsp;</div>
      </div>
      <!-- End Box Content -->
  
    </div>
    <!-- End Box -->
    
  </div>
  <!-- End Sidebar -->

  <!-- Content -->
  <div id="content">
  
  <?php if (count($oCategoriesCollection)):?>
    <!-- Box -->
    <div class="box">
    
      <div class="box-head">
        <h2 class="left"><?php echo $this->__('Categories list')?></h2>
      </div>
      
      <!-- Table -->
      <div class="table">
      
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th><?php echo $this->__('Image')?></th>
            <th><?php echo $this->__('Category ID')?></th>
            <th><?php echo $this->__('Category Name')?></th>
            <th><?php echo $this->__('Status')?></th>
            <th><?php echo $this->__('Actions')?></th>
          </tr>
          
        <?php foreach ($oCategoriesCollection as $oCat):?>
          <tr>
            <td>
              <?php if ($oCat->getFile()):?>
                <img src="<?php echo Category::HTTP_DIR .'/'. $oCat->getFile()?>">
              <?php else: ?>
                &nbsp;
              <?php endif;?>
            </td>
            <td><?php echo $oCat->getCategoryId()?></td>
            <td><?php echo $oCat->getName()?></td>
            <td><?php echo $oCat->getStatus()?></td>
            <td>
              <span class="padding-right20">
                <a href="<?php echo href_admin('categories/edit', $oCat->getCategoryId())?>" 
                   class="ico edit js-user-list-edit"
                >
                  <?php echo $this->__('Edit')?>
                </a>
              </span>
              <span class="padding-right20">
                <a href="<?php echo href_admin('categories/delete', $oCat->getCategoryId())?>" 
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
    <h2><?php echo $this->__('No categories could be found.');?></h2>
  <?php endif;?>
  
  </div>
  <!-- End Content -->

  <div class="cl">&nbsp;</div>
</div>
<!-- Main -->