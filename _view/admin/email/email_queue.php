<!-- Main -->
<div id="main">
  <div class="cl">&nbsp;</div>
  
  <?php include(VIEW_DIR. '/admin/_include/email_sidebar.php');?>
  
<!-- Content -->
<div id="content">
  
<?php if (!$oCollection):?>
  <p><?php echo $this->__('No emails found')?></p>
<?php else:?>
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
        <?php echo ($sort == 'email_queue_id' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
        <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('email_queue_id', $sort_crit)?>">
          <?php echo $this->__('ID')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'to' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
        <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('to', $sort_crit)?>">
          <?php echo $this->__('To')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'subject' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
        <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('subject', $sort_crit)?>">
          <?php echo $this->__('Subject')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'priority' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
        <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('priority', $sort_crit)?>">
          <?php echo $this->__('Priority')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'sent_attempts' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
        <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('send_attempts', $sort_crit)?>">
          <?php echo $this->__('Send Attempts')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'created_at' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
        <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('created_at', $sort_crit)?>">
          <?php echo $this->__('Created at')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'updated_at' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
        <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('updated_at', $sort_crit)?>">
          <?php echo $this->__('Updated at')?>
        </a>
      </th>
      <th>
        <?php echo ($sort == 'status' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
        <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('status', $sort_crit)?>">
          <?php echo $this->__('Status')?>
        </a>
      </th>
      <th>
        <?php echo $this->__('Body')?>
      </th>
    </tr>
  <?php foreach ($oCollection as $email):?>
    <tr>
      <td><?php echo $email->getEmailQueueId()?></td>
      <td><?php echo $email->getToo()?></td>
      <td><?php echo $email->getSubject()?></td>
      <td><?php echo $email->getPriority()?></td>
      <td><?php echo $email->getSendAttempts()?></td>
      <td><?php echo $email->getCreatedAt()?></td>
      <td><?php echo $email->getUpdatedAt()?></td>
      <td><?php echo $email->getStatus()?></td>
      <td>
        <a href="#" class="js-show-email-body" data-body="<?php echo base64_encode($email->getBody())?>">
          <?php echo $this->__('Show email body')?>
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