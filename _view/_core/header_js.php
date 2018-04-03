<script type="text/javascript" nonce="29af2i">
var CONTROLLER_NAME	= '<?php echo mvc::getControllerClass(); ?>';
var ACTION_NAME 	= '<?php echo mvc::getControllerFunction(); ?>';

var HTTP_IMAGES 	= '<?php echo HTTP_IMAGES;?>';
var HTTP 			= '<?php echo HTTP;?>';
var HTTP_MAIN 		= '<?php echo HTTP_MAIN;?>';
var IS_LOGGED_IN 	= <?php echo (User::isLoggedIn() ? 1 : 0)?>;

var MVC_MODULE_URL	= '<?php echo MVC_MODULE_URL?>';
var MVC_ACTION_URL	= '<?php echo MVC_ACTION_URL?>';
var CURRENT_URL		= '<?php echo CURRENT_URL?>';

var TOKEN			= '<?php echo securityGetToken()?>';

var language		= '<?php echo $language ?>';
</script>

<?php if (!$_SKIP_JS): ?>
	<?php foreach($_aJS as $key => $value): ?>
		<script type="text/javascript" src="<?php echo ($https ? HTTPS_JS : HTTP_JS).$value?>?id=<?php echo $key?>"></script>
	<?php endforeach;?>
<?php endif;?>