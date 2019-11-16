<!-- Main -->
<div id="main">
  <div class="cl">&nbsp;</div>
  
  <?php include(VIEW_DIR . '/admin/_include/email_sidebar.php');?>

<!-- Content -->
<div id="content">

<?php if (!$oCollection) : ?>
  <p><?php echo $this->__('No emails found')?></p>
<?php else : ?>
  <!-- Box -->
  <div class="box">
  
    <!-- Box Head -->
    <div class="box-head">
    
    <!-- Search and filters form -->
    <form id="searchAndFilters" method="get" action="<?php echo MVC_ACTION_URL?>" >
      <h2 class="left"><?php echo $this->__('Emails list')?></h2>
      <div class="right">
        <label><?php echo $this->__('Search for emails')?></label>
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
      
      <input type="hidden" name="page" value="<?php echo $oPagination->getPage()?>">
      <input type="hidden" name="sort" value="<?php echo $sort?>" />
      <input type="hidden" name="sort_crit" value="<?php echo $sort_crit?>" />
    </form>  
    <!-- End search and filters form -->
    
    </div><!-- End Box Head -->
    
  <!-- Table -->
  <div class="table">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th>
        <?php echo ($sort == 'email_queue_id' ? '<span class="' . ($sort_crit == 'asc' ? 'sortAsc' : 'sortDesc' ) . '"></span>' : '')?>
        <a href="<?php echo MVC_ACTION_URL . '?' . $GF->GFHref(true, true, false) . $GF->sortParams('email_queue_id', $sort_crit)?>">
          <?php echo $this->__('ID')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'created_at' ? '<span class="' . ($sort_crit == 'asc' ? 'sortAsc' : 'sortDesc' ) . '"></span>' : '')?>
        <a href="<?php echo MVC_ACTION_URL . '?' . $GF->GFHref(true, true, false) . $GF->sortParams('created_at', $sort_crit)?>">
          <?php echo $this->__('Created at')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'status' ? '<span class="' . ($sort_crit == 'asc' ? 'sortAsc' : 'sortDesc' ) . '"></span>' : '')?>
        <a href="<?php echo MVC_ACTION_URL . '?' . $GF->GFHref(true, true, false) . $GF->sortParams('status', $sort_crit)?>">
          <?php echo $this->__('Status')?>
        </a>
      </th>
      <th>
        <?php echo $this->__('Error Info')?>
      </th>
      <th>
        <?php echo $this->__('Debug')?>
      </th>
    </tr>
  
  <?php foreach ($oCollection as $email) : ?>
    <tr>
      <td><?php echo $email->getEmailLogId()?></td>
      <td><?php echo $email->getCreatedAt()?></td>
      <td><?php echo $email->getStatus()?></td>
      <td>
        <a href="#" class="js-show-error-info" data-body="<?php echo base64_encode($email->getErrorInfo())?>">
          <?php echo $this->__('Show error info')?>
        </a>
      </td>
      <td>
        <a href="#" class="js-show-debug" data-body="<?php echo base64_encode($email->getDebug())?>">
          <?php echo $this->__('Show debug')?>
        </a>
      </td>
    </tr>
  <?php endforeach;?>
  
  </table>
  
  <div class="pagging">
    <?php echo $oPagination->getHtml()?>
  </div>
  
  </div> <!-- End table -->

  </div> <!-- End box -->
<?php endif;?>

</div>
<!-- End Content -->

  <div class="cl">&nbsp;</div>
</div>
<!-- Main -->