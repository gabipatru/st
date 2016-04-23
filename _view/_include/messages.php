<?php foreach ($_MESSAGES as $msg => $err):?>
	<div class="<?php echo ($err === true ? 'div-error' : 'div-message');?>">
		<span><?php echo $msg; ?></span>
	</div>
<?php endforeach;?>