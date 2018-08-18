<!-- Main -->
<div id="main">
    <div class="cl">&nbsp;</div>
 
    <!-- Sidebar -->
    <div id="sidebar">
    
    <!-- Box -->
    <div class="box">
   
        <!-- Box Head -->
        <div class="box-head">
            <h2><?php echo $this->__('Management')?></h2>
        </div>
        <!-- End Box Head-->
     
        <div class="box-content">
           <a href="<?php echo href_admin('config/add')?>" class="add-button"><span><?php echo $this->__('Add new Config')?></span></a>
           <div class="cl">&nbsp;</div>
        </div>
    </div>
 
    <!-- Box -->
    <div class="box">
   
        <!-- Box Head -->
        <div class="box-head">
            <h2><?php echo $this->__('Config')?></h2>
        </div>
        <!-- End Box Head-->
     
        <div class="box-content">
        <?php foreach ($aConfig as $config_name => $ConfigItem):?>
            <a href="<?php echo MVC_ACTION_URL?>?name=<?php echo $config_name?>" class="box-link"><?php echo $config_name;?></a>
        <?php endforeach;?>
            <div class="cl">&nbsp;</div>
        </div>
    </div>
    <!-- End Box -->
    
    </div>
    <!-- End Sidebar -->
    
    <!-- Content -->
    <div id="content">
    
    <?php if ($configName):?>
    <form id="saveForm" action="<?php echo MVC_MODULE_URL?>/save_all.html" method="post">
        <input type="hidden" name="configName" value="<?php echo $configName?>" />
    <?php foreach ($aConfig[$configName] as $configKey => $aConfigItem):?>
        <!-- Box -->
        <div class="box">
        
            <a href="#">
            <!-- Box Head -->
            <div class="box-head js-slide">
                <h2 class="left"><?php echo $configKey?></h2>
                <div class="right img-open-box"></div>
            </div>
            <!-- End Box Head -->
            </a>
            
            <!-- Table -->
            <div class="table hidden" data-slided="false">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?php foreach ($aConfigItem as $itemName => $aItem):?>
                <tr>
                    <td><h3><?php echo $itemName?></h3></td>
                    <td>
                        <?php if ($aItem['type'] == 'text'):?>
                            <input type="text" name="config<?php echo $aItem['config_id']?>" value="<?php echo $aItem['value']?>" class="field size650" />
                        <?php endif;?>
                        <?php if ($aItem['type'] == 'textarea'):?>
                            <textarea name="config<?php echo $aItem['config_id']?>" class="field size650"><?php echo $aItem['value']?></textarea>
                        <?php endif;?>
                        <?php if ($aItem['type'] == 'yesno'):?>
                            <select name="config<?php echo $aItem['config_id']?>" class="field size3">
                                <option value="1" <?php echo ($aItem['value'] == "1" ? 'selected="SELECTED"' : '')?>><?php echo $this->__('Yes')?></option>
                                <option value="0" <?php echo ($aItem['value'] == "0" ? 'selected="SELECTED"' : '')?>><?php echo $this->__('No')?></option>
                            </select>
                        <?php endif;?>
                    </td>
                </tr>
                <input type="hidden" name="config_ids[]" value="<?php echo $aItem['config_id']?>" />
                <?php endforeach;?>
                </table>
            </div>
            <!-- End Table -->
        
        </div>
        <!-- End Box -->
    <?php endforeach;?>
        <input type="hidden" name="token" value="<?php echo $this->securityGetToken()?>">
        <input type="submit" class="button" value="Save" />
    </form>
    <?php endif;?>
    
    </div>
    <!-- End Content -->
            
    
            
    <div class="cl">&nbsp;</div>
</div>
<!-- Main -->