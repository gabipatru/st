</div><!-- End container-->

<footer id="footer">
  <div class="footer-bottom">
    <?php if (DEBUGGER_AGENT && Config::configByPath('HTML/Footer Debug/Display in website')) : ?>
      <div class="container">
        <div><?php echo $this->__('Script footprint')?>: <?php echo $this->displayBytes($memFootprint)?></div>
        <div><?php echo $this->__('Generated in')?> <?php printf("%5.1f", $executionTime)?> ms</div>
        <div>
          <?php echo $this->__('Number of queries');?>: <?php echo $_queryNo?> 
          <a class="yellow-text" id="js-query-display" href="#">+</a>
        </div>
        <div class="class-hidden" id="js-query-container">
          <?php foreach ($_queriesRun as $sql) : ?>
            <div class="debug-item"><?php echo $sql; ?></div>
          <?php endforeach;?>
        </div>
      </div>
    <?php endif;?>
    <div class="container">
      <div class="row">
        <p class="pull-left"><?php echo $this->__('Copyright © 2017 Surprize Turbo. All rights reserved.')?></p>
        <p class="pull-right"><?php echo $this->__('Designed by Surprize Turbo')?></p>
      </div>
    </div>
  </div>
</footer>

<?php require_once(VIEW_DIR . '/_core/header_js.php');?>

</body>
