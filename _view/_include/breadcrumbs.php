<?php $Breadcrumbs = Breadcrumbs::getSingleton(); ?>
<!-- Small Nav -->
<div class="small-nav">
<?php $first = true;?>
<?php foreach ($Breadcrumbs->getBreadcrumbs() as $key => $aBrc):?>
	<?php if (!$first):?>
		<span>&gt;</span>
	<?php endif;?>
	<a href="<?php echo $aBrc['link']?>"><?php echo $aBrc['name']?></a>
	<?php $first = false?>
<?php endforeach;?>
</div>
<!-- End Small Nav -->