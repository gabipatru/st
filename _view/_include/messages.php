<?php $aMessageTypes = $this->constMessageTypes()?>

<!-- Messages -->
<?php foreach ($this->getMessages() as $msg => $type) : ?>
  <div class="<?php echo ($type === $aMessageTypes['error'] ? 'msg msg-error' : 'msg msg-ok');?>">
    <p><strong><?php echo $msg; ?></strong></p>
    <a href="#" class="close">close</a>
  </div>
<?php endforeach;?>

<!-- Debug messages -->
<?php foreach ($this->getDebugMessages() as $msg => $type) : ?>
  <div class="msg msg-debug">
    <p><strong><?php echo $msg; ?></strong></p>
    <a href="#" class="close">close</a>
  </div>
<?php endforeach;?>

<br />