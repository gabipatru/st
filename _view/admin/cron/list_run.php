<!-- Main -->
<div id="main">
  <div class="cl">&nbsp;</div>

  <!-- Sidebar -->
  <div id="sidebar">

    <!-- Box -->
    <div class="box">

      <!-- Box Head -->
      <div class="box-head">
        <h2><?php echo $this->__('Cron management')?></h2>
      </div>
      <!-- End Box Head-->

      <div class="box-content">
        <a href="<?php echo href_admin('cron/list_items')?>" class="box-link"><?php echo $this->__('Cron List')?></a>
        <a href="<?php echo href_admin('cron/list_run')?>" class="box-link"><?php echo $this->__('Cron Runs')?></a>
        <div class="cl">&nbsp;</div>
      </div>

    </div>
    <!-- End Box -->

  </div>
  <!-- End Sidebar -->

  <!-- Content -->
  <div id="content">

    <!-- Box -->
    <div class="box">

      <!-- Box Head -->
      <div class="box-head">
        <h2 class="left"><?php echo $this->__('Scheduled Scripts')?></h2>
      </div>
      <!-- End Box Head -->

      <!-- Table -->
      <div class="table">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th>
              <?php echo ($sort == 'cron_run_id' ? '<span class="' . ($sort_crit == 'asc' ? 'sortAsc' : 'sortDesc' ) . '"></span>' : '')?>
              <a href="<?php echo MVC_ACTION_URL . '?' . $GF->GFHref(true, true, false) . $GF->sortParams('cron_run_id', $sort_crit)?>">
                <?php echo $this->__('Cron Run Id')?>
              </a>
            </th>
            <th>
              <?php echo $this->__('Cron')?>
            </th>
            <th>
              <?php echo ($sort == 'duration' ? '<span class="' . ($sort_crit == 'asc' ? 'sortAsc' : 'sortDesc' ) . '"></span>' : '')?>
              <a href="<?php echo MVC_ACTION_URL . '?' . $GF->GFHref(true, true, false) . $GF->sortParams('duration', $sort_crit)?>">
                <?php echo $this->__('Duration')?>
              </a>
            </th>
            <th>
              <?php echo ($sort == 'created_at' ? '<span class="' . ($sort_crit == 'asc' ? 'sortAsc' : 'sortDesc' ) . '"></span>' : '')?>
              <a href="<?php echo MVC_ACTION_URL . '?' . $GF->GFHref(true, true, false) . $GF->sortParams('created_at', $sort_crit)?>">
                <?php echo $this->__('Run at')?>
              </a>
            </th>
          </tr>

        <?php foreach ($collectionCron as $col) : ?>
          <tr>
            <td><?php echo $col->getCronRunId()?></td>
            <td><?php echo $col->getCron()->getScript()?></td>
            <td><?php echo $col->getDuration()?></td>
            <td><?php echo $col->getCreatedAt()?></td>
          </tr>
        <?php endforeach;?>

        </table>

        <div class="pagging">
          <?php echo $oPagination->getHtml()?>
        </div>

      </div>
      <!-- End Table -->

    </div>
    <!-- End Box -->

  </div>
  <!-- End Content -->

  <div class="cl">&nbsp;</div>

</div>
<!-- Main -->