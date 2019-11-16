<?php if (! $this->getSkipCss()) : ?>
  <?php foreach ($_aCSS as $key => $value) : ?>
    <link rel="stylesheet" type="text/css" href="<?php echo ($https ? HTTPS_CSS : HTTP_CSS) . $value;?>?id=<?php echo $key?>" />
  <?php endforeach;?>
<?php endif;?>