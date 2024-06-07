<!-- Sidebar -->
<div id="sidebar">

  <!-- Box -->
  <div class="box">

    <!-- Box Head -->
    <div class="box-head">
      <h2><?php echo $this->__('Cache Management')?></h2>
    </div>
    <!-- End Box Head-->

    <div class="box-content">
      <a href="<?php echo href_admin('cache/memcached')?>" class="box-link"><?php echo $this->__('Memcached')?></a>
      <a href="<?php echo href_admin('cache/elasticsearch')?>" class="box-link"><?php echo $this->__('Elasticsearch')?></a>
      <div class="cl">&nbsp;</div>
    </div>

  </div>
  <!-- End Box -->

</div>
<!-- End Sidebar -->