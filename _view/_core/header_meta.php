<?php
if (!$this->getSkipMeta()) {
	if($this->getPageTitle()) {
	    ?><title><?php echo $this->getPageTitle()?></title>
<?php }
	foreach($_aMETA as $name => $content) {
		?><meta name="<?php echo $name;?>" content="<?php echo $content?>"/>
<?php }
}
?>