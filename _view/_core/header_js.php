<script type="text/javascript">
var CONTROLLER_NAME = '<?php echo mvc::getControllerClass(); ?>';
var ACTION_NAME = '<?php echo mvc::getControllerFunction(); ?>';

var HTTP_IMAGES = '<?php echo HTTP_IMAGES;?>';
var HTTP = '<?php echo HTTP;?>';
var HTTP_MAIN = '<?php echo HTTP_MAIN;?>';
var IS_LOGGED_IN = 0;
</script>

<script type="text/javascript" src="<?php echo ($https ? HTTPS_JS : HTTP_JS);?>/bundle.js"></script>

<?php
if (!$_SKIP_JS) {
	foreach($_aJS as $value) {
		?><script type="text/javascript" src="<?php echo ($https ? HTTPS_JS : HTTP_JS).$value?>"></script>
<?php }
}
?>