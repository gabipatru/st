<div id="form-page">
  <div class="bg">
    <div class="row">
      <div class="col-sm-12">
        <h2 class="title text-center"><?php echo $oCategory->getName()?></h2>
      </div>
    </div> <!-- End row -->
    
    <div class="row">
      <?php foreach ($oSeriesCollection as $oSeries):?>
        <div class="col-sm-3 margin-bottom-30">
          <div class="category-box height-210">
            <?php if ($oSeries->getFile()):?>
              <a href="<?php echo href_website('website/category', [$oSeries->getName() => $oSeries->getSeriesId()])?>">
                <img alt="" src="<?php echo Series::HTTP_DIR .'/'. $oSeries->getFile();?>">
              </a>
            <?php else:?>
              <div class="no-image-category"></div>
            <?php endif;?>
          </div>
          <div class="category-box">
            <a class="category-link" href="<?php echo href_website('website/category', [$oSeries->getName() => $oSeries->getSeriesId()])?>">
              <?php echo $oSeries->getName();?>
            </a>
          </div>
        </div>
      <?php endforeach;?>
    </div> <!-- End row -->
  
  </div> <!-- End bg -->
</div> <!-- End form page -->