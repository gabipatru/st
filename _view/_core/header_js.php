<script type="text/javascript" nonce="29af2i">
  var CONTROLLER_NAME = '<?php echo mvc::getSingleton()->getControllerClass(); ?>';
  var ACTION_NAME     = '<?php echo mvc::getSingleton()->getControllerFunction(); ?>';

  var HTTP_IMAGES     = '<?php echo HTTP_IMAGES;?>';
  var HTTP            = '<?php echo HTTP;?>';
  var HTTP_MAIN       = '<?php echo HTTP_MAIN;?>';

  var IS_LOGGED_IN    = <?php echo (User::isLoggedIn() ? 1 : 0)?>;
  var DEBUGGER_AGENT  = <?php echo DEBUGGER_AGENT ?>;

  var MVC_MODULE_URL  = '<?php echo MVC_MODULE_URL?>';
  var MVC_ACTION_URL  = '<?php echo MVC_ACTION_URL?>';
  var CURRENT_URL     = '<?php echo CURRENT_URL?>';

  var TOKEN           = '<?php echo $this->securityGetToken()?>';

  var LANGUAGE        = '<?php echo $oTranslations->getLanguage()?>';
</script>

<?php if (!$this->getSkipJs()) : ?>
  <?php foreach ($_aJS as $key => $value) : ?>
    <script type="text/javascript" src="<?php echo ($https ? HTTPS_JS : HTTP_JS) . $value?>?id=<?php echo $key?>"></script>
  <?php endforeach;?>
<?php endif;?>