<?php
if (!$_SKIP_META) {
	if($_PAGE_TITLE) {
		?><title><?php echo $_PAGE_TITLE?></title>
<?php }
	foreach($_aMETA as $name => $content) {
		?><meta name="<?php echo $name;?>" content="<?php echo $content?>"/>
<?php }
}
?>