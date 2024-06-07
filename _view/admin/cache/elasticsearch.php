<!-- Main -->
<div id="main">

  <?php include(VIEW_DIR . '/admin/_include/cache_sidebar.php');?>

  <!-- Content -->
  <div id="content">

    <!-- Box -->
    <div class="box">

      <!-- Box Head -->
      <div class="box-head">
        <h2 class="left"><?php echo $this->__('Elasticsearch Stats')?></h2>
      </div>
      <!-- End Box Head -->

      <!-- Table -->
      <div class="table">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th>
              <?php echo $this->__('Name')?>
            </th>
            <th>
              <?php echo $this->__('Documents Number')?>
            </th>
            <th>
              <?php echo $this->__('Index Size')?>
            </th>
            <th>
              <?php echo $this->__('Status')?>
            </th>
            <th>
              <?php echo $this->__('Actions')?>
            </th>
          </tr>
        <?php foreach ($aElasticData as $statName => $statValue) : ?>
          <tr>
            <td><?php echo $statValue['name']?></td>
            <td><?php echo $statValue['docs_no']?></td>
            <td><?php echo $statValue['storage']?></td>
            <td><?php echo $statValue['status']?></td>
            <td>
              <span class="padding-right20">
                <a href="<?php echo href_admin('cache/delete_elastic_index', $statValue['name'])?>"
                   class="ico del js-user-list-edit js-delete"
                >
                  <?php echo $this->__('Delete')?>
                </a>
              </span>
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
