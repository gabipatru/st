<div id="form-page">
  <div class="bg">
    <div class="row">
      <div class="col-sm-12">
        <h2 class="title text-center"><?php echo $oSeries->getName()?></h2>
      </div>
    </div> <!-- End row -->
    
    <div class="row">
      <div class="col-sm-3 right">
        <h4 class="title"><?php echo $this->__('Filter')?></h2>
        <select id="filterGroup">
          <option value="0"><?php echo $this->__('All')?></option>
          <?php foreach ($oGroupCollection as $oGroup) : ?>
            <option value="<?php echo $oGroup->getGroupId()?>"><?php echo $oGroup->getName();?></option>
          <?php endforeach;?>
        </select>
      </div>
    </div>
    
    <br style="clear:both;" />
    
    <div class="row">
      <?php foreach ($oSurpriseCollection as $oSurprise) : ?>
        <div class="col-sm-3 margin-bottom-30 js-surprise" data-group-id="<?php echo $oSurprise->getGroupId() ?>">
          <div class="category-box height-210">
            <?php if ($oSurprise->getFile()) : ?>
              <a href="<?php echo href_website('website/series', [$oSeries->getName() => $oSeries->getSeriesId()])?>">
                <img alt="" src="<?php echo Series::HTTP_DIR . '/' . $oSurprise->getFile();?>">
              </a>
            <?php else : ?>
              <div class="no-image-category"></div>
            <?php endif;?>
          </div>
          <div class="category-box">
            <a class="category-link" href="<?php echo href_website('website/series', [$oSeries->getName() => $oSeries->getSeriesId()])?>">
              <?php echo $oSurprise->getName();?>
            </a>
          </div>
        </div>
      <?php endforeach;?>
    </div> <!-- End row -->

  </div> <!-- End bg -->
</div> <!-- End form page -->