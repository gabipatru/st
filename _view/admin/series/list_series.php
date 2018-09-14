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
        <a href="<?php echo href_admin('series/edit')?>" class="add-button">
          <span><?php echo $this->__('Add New Series')?></span>
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
  
  <?php if (count($oSeriesCollection)):?>
    <!-- Box -->
    <div class="box">
    
      <div class="box-head">
        <h2 class="left"><?php echo $this->__('Series list')?></h2>
      </div>
      
      <!-- Table -->
      <div class="table">
      
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th><?php echo $this->__('Image')?></th>
            <th><?php echo $this->__('Series ID')?></th>
            <th><?php echo $this->__('Series Name')?></th>
            <th><?php echo $this->__('Category')?></th>
            <th><?php echo $this->__('Status')?></th>
            <th><?php echo $this->__('Actions')?></th>
          </tr>
          
        <?php foreach ($oSeriesCollection as $oCat):?>
          <tr>
            <td>
              <?php if ($oCat->getFile()):?>
                <span class="js-image-cell" data-image-div="image-div-<?php echo $oCat->getSeriesId()?>">
                  <?php echo $this->__('Image')?>
                </span>
                <div id="image-div-<?php echo $oCat->getSeriesId()?>" class="hidden absolute">
                  <img src="<?php echo Series::HTTP_DIR .'/'. $oCat->getFile()?>">
                </div>
              <?php else: ?>
                &nbsp;
              <?php endif;?>
            </td>
            <td><?php echo $oCat->getSeriesId()?></td>
            <td><?php echo $oCat->getName()?></td>
            <td><?php echo ($oCat->getCategory() instanceof SetterGetter ? $oCat->getCategory()->getName() : '')?></td>
            <td><?php echo $oCat->getStatus()?></td>
            <td>
              <span class="padding-right20">
                <a href="<?php echo href_admin('series/edit', $oCat->getSeriesId())?>" 
                   class="ico edit js-user-list-edit"
                >
                  <?php echo $this->__('Edit')?>
                </a>
              </span>
              <span class="padding-right20">
                <a href="<?php echo href_admin('series/delete', $oCat->getSeriesId())?>" 
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
    <h2><?php echo $this->__('No series could be found.');?></h2>
  <?php endif;?>
  
  </div>
  <!-- End Content -->

  <div class="cl">&nbsp;</div>
</div>
<!-- Main -->