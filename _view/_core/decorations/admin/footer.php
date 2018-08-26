
    </div> <!-- shell -->
</div> <!-- container -->

<?php if (Config::configByPath('HTML/Footer Debug/Dsiplay in admin')):?>
<!-- Shell-debug -->
<div class="shell-debug">
  <div><?php echo $this->__('Script footprint')?>: <?php echo $this->displayBytes($memFootprint)?></div>
  <div><?php echo $this->__('Generated in')?> <?php printf("%5.1f", $executionTime)?> ms</div>
</div>
<!-- End Shell-debug -->

<!-- Shell-debug -->
<div class="shell-debug">
  <div>
    <?php echo $this->__('Number of queries');?>: <?php echo $_queryNo?> 
    <a class="yellow-text" id="js-query-display" href="#">+</a>
  </div>
  <div class="hidden" id="js-query-container">
    <?php foreach ($_queriesRun as $sql):?>
      <div class="shell-debug-item"><?php echo $sql; ?></div>
    <?php endforeach;?>
  </div>
</div>
<!-- End Shell-debug -->
<?php endif;?>

<!-- Footer -->
<div id="footer">
    <div class="shell">
        <span class="left"><?php echo $this->__('Copyright © 2017 Surprize Turbo. All rights reserved.')?></span>
        <span class="right">
            <?php echo $this->__('Designed by Sutprize Turbo')?>
        </span>
    </div>
</div>
<!-- End Footer -->

<?php require_once(VIEW_DIR.'/_core/header_js.php');?>

</body>
