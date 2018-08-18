<p>
<?php echo $this->__('You have made a request to reset your password. In order to reset the password please follow the link below')?>
</p>
<p><a href="<?php echo href_website('user/reset_passwd', $confirmationCode)?>"><?php echo $this->__('Reset password')?></a></p>
<p>
	<?php echo $this->__('Or copy-paste this link to your browser address')?>: 
	<?php echo href_website('user/reset_passwd', $confirmationCode)?> >
</p>