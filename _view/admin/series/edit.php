<!-- Main -->
<div id="main">
  <div class="cl">&nbsp;</div>
  
  <!-- Content -->
  <div id="content">
  
    <!-- Box -->
    <div class="box">
    
      <div class="box-head">
        <h2><?php echo (! $seriesId ? $this->__('Add New Series') : $this->__('Edit Series'))?></h2>
      </div>
      
      <form id="editForm" action="<?php echo CURRENT_URL?>" method="post" enctype="multipart/form-data">
        <!-- Form -->
        <div class="form">
        
          <p>
            <label><?php echo $this->__('Category')?> <span>(<?php echo $this->__('Required Field')?>)</span></label>
            <select id="category_id" name="category_id" class="field size4">
                <option value=""><?php echo $this->__('-- Choose one --')?></option>
              <?php foreach ($oCategoriesCollection as $oCat):?>
                <option <?php $this->selected($FV->category_id, $oCat->getCategoryId())?> value="<?php echo $oCat->getCategoryId()?>"><?php echo $oCat->getName()?></option>
              <?php endforeach;?>
            </select>
            <label id="category_id-error" class="error"><?php echo $FV->category_id_error?></label>
          </p>
          
          <p>
            <label><?php echo $this->__('Name')?> <span>(<?php echo $this->__('Required Field')?>)</span></label>
            <input type="text" name="name" id="name" class="field size1" value="<?php echo $FV->name;?>" />
            <label id="name-error" class="error" for="name"><?php echo $FV->name_error?></label>
          </p>
          
          <p>
            <label><?php echo $this->__('Description')?></label>
            <textarea id="description" name="description" class="field size1"><?php echo $FV->description;?></textarea>
          </p>
          
          <p>
            <label><?php echo $this->__('Status')?> <span>(<?php echo $this->__('Required Field')?>)</span></label>
            <select id="status" name="status" class="field size4">
              <option <?php $this->selected($FV->status, 'online')?> value="online"><?php echo $this->__('Online')?></option>
              <option <?php $this->selected($FV->status, 'offline')?> value="offline"><?php echo $this->__('Offline')?></option>
            </select>
            <label id="status-error" class="error"><?php echo $FV->status_error?></label>
          </p>
          
          <p>
            <label><?php echo $this->__('Image')?></label>
            <?php if ($oSeries->getFile()):?>
              <img 
                  alt="<?php echo $oSeries->getFile()?>" 
                  src="<?php echo Series::HTTP_DIR .'/'. $oSeries->getFile()?>"
              >
            <?php endif;?>
          </p>
          
          <p>
            <label><?php echo $this->__('New Image')?></label>
            <input type="file" name="fileImage">
            <label id="fileImage-error" class="error"><?php echo $FV->fileImage_error?></label>
          </p>
          
        </div>
        <!-- End Form -->
        
        <div class="buttons">
          <input id="save-series" type="submit" class="button" value="<?php echo $this->__('Save')?>" />
        </div>
        
        <input type="hidden" name="token" value="<?php echo $this->securityGetToken()?>">
        
      </form>
      <?php echo $FV->_js_code;?>
    
    </div>
    <!-- End Box -->
  
  </div>
  <!-- End Content -->

  <div class="cl">&nbsp;</div>
</div>
<!-- Main -->