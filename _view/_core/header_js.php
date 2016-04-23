<script type="text/javascript">
var HTTP_IMAGES = '<?php echo HTTP_IMAGES;?>';
var HTTP = '<?php echo HTTP;?>';
var HTTP_MAIN = '<?php echo HTTP_MAIN;?>';
var IS_LOGGED_IN = 0;
</script>
<?php
if (!$_SKIP_JS) {
	foreach($_aJS as $value) {
		?><script type="text/javascript" src="<?php echo ($https ? HTTPS_JS : HTTP_JS).$value?>"></script>
<?php }
}
?>