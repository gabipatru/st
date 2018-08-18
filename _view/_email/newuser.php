<p><?php echo $this->__('Welcome to surprizeturbo.ro') ?>, <?php echo $username?></p>
<p>
	<?php echo $this->__('In order to activate your account please click')?> &nbsp;
	<a href="<?php echo href_website('user/confirm', $confirmationCode)?>"><?php echo $this->__('here')?></a>
</p>
<p><?php echo $this->__('Or copy-paste this link to your browser address')?>:</p>
<p><?php echo href_website('user/confirm', $confirmationCode)?></p>