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
        <a href="<?php echo href_admin('surprises/edit')?>" class="add-button">
          <span><?php echo $this->__('Add New Surprise')?></span>
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
  
  <?php if (count($oSurprisesCollection)) : ?>
    <!-- Box -->
    <div class="box">
    
      <div class="box-head">
        <form id="searchAndFilters" method="get" action="<?php echo MVC_ACTION_URL?>" >
          <h2 class="left"><?php echo $this->__('Surprises list')?></h2>
          <div class="right">
            <label><?php echo $this->__('Search for surprises')?></label>
            <input name="search" type="text" class="field small-field" value="<?php echo $search?>" />
            <input type="submit" class="button" value="<?php echo $this->__('Search')?>" />
          </div>

          <div class="cl">&nbsp;</div>
          <div class="box-continue">
            <h2 class="left"><?php echo $this->__('Filter')?></h2>
            <div class="right">
              <label><?php echo $this->__('Filter by status')?></label>
              <span class="GF-select"><?php echo $GF->GFSelect('status');?></span>
            </div>
          </div>
        </form>
      </div>
      
      <!-- Table -->
      <div class="table">
      
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th>
              <?php echo ($sort == 'surprise_id' ? '<span class="' . ($sort_crit == 'asc' ? 'sortAsc' : 'sortDesc' ) . '"></span>' : '')?>
              <a href="<?php echo MVC_ACTION_URL . '?' . $GF->GFHref(true, true, false) . $GF->sortParams('surprise_id', $sort_crit)?>">
                <?php echo $this->__('Surprise ID')?>
              </a>
            </th>
            <th>
              <?php echo ($sort == 'name' ? '<span class="' . ($sort_crit == 'asc' ? 'sortAsc' : 'sortDesc' ) . '"></span>' : '')?>
              <a href="<?php echo MVC_ACTION_URL . '?' . $GF->GFHref(true, true, false) . $GF->sortParams('name', $sort_crit)?>">
                <?php echo $this->__('Surprise Name')?>
              </a>
            </th>
            <th>
              <?php echo $this->__('Series_sg')?>
            </th>
            <th>
              <?php echo $this->__('Group')?>
            </th>
            <th>
              <?php echo $this->__('Status')?>
            </th>
            <th>
              <?php echo $this->__('Actions')?>
            </th>
          </tr>
          
        <?php foreach ($oSurprisesCollection as $oCat) : ?>
          <tr>
            <td><?php echo $oCat->getSurpriseId()?></td>
            <td><?php echo $oCat->getName()?></td>
            <td><?php echo ($oCat->getGroup() instanceof SetterGetter && $oCat->getGroup()->getSeries() instanceof SetterGetter ? $oCat->getGroup()->getSeries()->getName() : '')?></td>
            <td><?php echo ($oCat->getGroup() instanceof SetterGetter ? $oCat->getGroup()->getName() : '')?></td>
            <td><?php echo $oCat->getStatus()?></td>
            <td>
              <span class="padding-right20">
                <a href="<?php echo href_admin('surprises/delete', $oCat->getSurpriseId())?>" 
                   class="ico del js-user-list-edit"
                   onclick="return confirm('<?php echo $this->__('Are you sure you want to delete this item ?')?>')"
                >
                  <?php echo $this->__('Delete')?>
                </a>
              </span>
              <span class="padding-right20">
                <a href="<?php echo href_admin('surprises/edit', $oCat->getSurpriseId())?>" 
                   class="ico edit js-user-list-edit"
                >
                  <?php echo $this->__('Edit')?>
                </a>
              </span>
            </td>
          </tr>
        <?php endforeach;?>
        </table>
        
        <div class="pagging">
          <?php echo $oPagination->getHtml()?>
        </div>
      
      </div>
      <!-- End table -->
    
    </div>
    <!-- End Box -->
  <?php else : ?>
    <h2><?php echo $this->__('No surprises could be found.');?></h2>
  <?php endif;?>
  
  </div>
  <!-- End Content -->

  <div class="cl">&nbsp;</div>
</div>
<!-- Main -->