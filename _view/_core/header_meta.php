<?php if (! $this->getSkipMeta()) : ?>
  <?php if ($this->getPageTitle()) : ?>
    <title><?php echo $this->getPageTitle()?></title>
  <?php endif; ?>
  <?php foreach ($_aMETA as $name => $content) : ?>
    <meta name="<?php echo $name;?>" content="<?php echo $content?>"/>
  <?php endforeach; ?>
<?php endif; ?>