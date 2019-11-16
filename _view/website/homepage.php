<div id="form-page">
  <div class="bg">
    <div class="row">
      <div class="col-sm-12">
        <h2 class="title text-center"><?php echo $this->__('Surprise Categories')?></h2>
      </div>
    </div>
    
    <div class="row">
      <?php foreach ($oCategoriesCollection as $oCat) : ?>
        <div class="col-sm-3 margin-bottom-30">
          <div class="category-box">
            <?php if ($oCat->getFile()) : ?>
              <a href="<?php echo href_website('website/category', [$oCat->getName() => $oCat->getCategoryId()])?>">
                <img alt="" src="<?php echo Category::HTTP_DIR . '/' . $oCat->getFile();?>">
              </a>
            <?php else : ?>
              <div class="no-image-category"></div>
            <?php endif;?>
          </div>
          <div class="category-box">
            <a class="category-link" href="<?php echo href_website('website/category', [$oCat->getName() => $oCat->getCategoryId()])?>">
              <?php echo $oCat->getName();?>
            </a>
          </div>
        </div>
      <?php endforeach;?>
    </div> <!-- End row -->
  
  </div> <!-- End bg -->
</div> <!-- End form page -->