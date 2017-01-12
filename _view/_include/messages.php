<?php var_dump($_MESSAGES)?>
<?php foreach ($_MESSAGES as $msg => $err):?>
	<div class="<?php echo ($err === true ? 'msg msg-error' : 'msg msg-ok');?>">
		<p><strong><?php echo $msg; ?></strong></p>
		<a href="#" class="close">close</a>
	</div>
<?php endforeach;?>

<br />