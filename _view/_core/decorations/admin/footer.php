
    </div> <!-- shell -->
</div> <!-- container -->

<div class="shell-debug">
  <div><?php echo $this->__('Script footprint')?>: <?php echo $this->displayBytes($memFootprint)?></div>
  <div><?php echo $this->__('Generated in')?> <?php printf("%5.1f", $executionTime)?> ms</div>
</div>

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
