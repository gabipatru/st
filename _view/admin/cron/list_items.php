<!-- Main -->
<div id="main">
  <div class="cl">&nbsp;</div>

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
            <th><?php echo $this->__('Cron Id')?></th>
            <th><?php echo $this->__('Script')?></th>
            <th><?php echo $this->__('Last Runtime')?></th>
            <th><?php echo $this->__('Next Runtime')?></th>
            <th><?php echo $this->__('Interval (minutes)')?></th>
            <th><?php echo $this->__('Status')?></th>
            <th><?php echo $this->__('Actions')?></th>
          </tr>

        <?php foreach ($collectionCron as $col):?>
          <tr>
            <td><?php echo $col->getCronId()?></td>
            <td><?php echo $col->getScript()?></td>
            <td><?php echo $col->getLastRuntime()?></td>
            <td><?php echo $col->getNextRuntime()?></td>
            <td><?php echo $col->getInterval()?></td>
            <td><?php echo $col->getStatus()?></td>
            <td>
              <a
                    href="#"
                    class="ico edit js-cron-edit"
                    data-cron-id="<?php echo $col->getCronId()?>"
                    data-cron-script="<?php echo $col->getScript()?>"
                    data-cron-interval="<?php echo $col->getInterval()?>"
                    data-cron-status="<?php echo $col->getStatus()?>"
              >
                    <?php echo $this->__('Edit')?>
              </a>
            </td>
          </tr>
        <?php endforeach;?>
        </table>
      </div>
      <!-- End Table -->

    </div>
    <!-- End Box -->

  </div>
  <!-- End Content -->

  <div class="cl">&nbsp;</div>

</div>
<!-- Main -->

<!-- Dialog for editing crons -->

<div id="cron-edit-dialog" class="hidden" title="<?php echo $this->__('Edit script')?>">
  <table cellspacing="5" cellpadding="5">
    <tr>
      <td>Script: </td>
      <td><span class="dialog-data-span" id="dialog-script"></span></td>
    </tr>
    <tr>
      <td>Interval: </td>
      <td>
        <input type="text" id="dialog-new-interval" value="<?php echo $col->getInterval()?>" />
      </td>
    </tr>
    <tr>
      <td>Status:</td>
      <td>
        <select id="dialog-new-status">
          <option value="<?php echo Cron::CRON_ENABLED?>"><?php echo $this->__('Enabled')?></option>
          <option value="<?php echo Cron::CRON_DISABLED?>"><?php echo $this->__('Disabled')?></option>
        </select>
      </td>
    </tr>
  </table>
  <p class="dialog-error" id="dialog-error"></p>
  <p class="dialog-actions" data-dialog="cron-edit-dialog">
    <button id="dialog-save" class="ui-button ui-widget ui-corner-all"><?php echo $this->__('Save')?></button>
    <button class="ui-button ui-widget ui-corner-all js-dialog-cancel"><?php echo $this->__('Cancel')?></button>
  </p>
  <p id="dialog-ajax-spinner"></p>
  <p id="dialog-ajax-error" class="dialog-ajax-error"></p>
</div>

<!-- End dialog  -->