<!-- Main -->
<div id="main">

<div class="cl">&nbsp;</div>

<?php include(VIEW_DIR. '/admin/_include/user_sidebar.php');?>
    
<!-- Content -->
<div id="content">
    
<?php if (!$oUserCol):?>
    <p><?php echo $this->__('No users found')?></p>
<?php else:?>
    <!-- Box -->
    <div class="box">
    
        <!-- Box Head -->
        <div class="box-head">
        
        <!-- Search and filters form -->
        <form id="searchAndFilters" method="get" action="<?php echo MVC_ACTION_URL?>" >
            <h2 class="left"><?php echo $this->__('Users list')?></h2>
            <div class="right">
                <label><?php echo $this->__('Search for users')?></label>
                <input name="search" type="text" class="field small-field" value="<?php echo $search?>" />
                <input type="submit" class="button" value="<?php echo $this->__('Search')?>" />
            </div>
                
            <div class="cl">&nbsp;</div>
            <div class="box-continue">
                <h2 class="left"><?php echo $this->__('Filter')?></h2>
                <div class="right">
                    <label><?php echo $this->__('Filter by status')?></label>
                    <span class="GF-select"><?php echo $GF->GFSelect('status');?></span>
                </div>
            </div>
            
            <input type="hidden" name="page" value="<?php echo $oPagination->getPage()?>">
            <input type="hidden" name="sort" value="<?php echo $sort?>" />
            <input type="hidden" name="sort_crit" value="<?php echo $sort_crit?>" />
        </form>    
        <!-- End search and filters form -->
        
        </div>
        <!-- End Box Head -->
        
    <!-- Table -->
    <div class="table">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <th>
                <?php echo ($sort == 'user_id' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
                <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('user_id', $sort_crit)?>">
                    <?php echo $this->__('ID')?>
                </a>
            </th>
            <th>
                <?php echo ($sort == 'username' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
                <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('username', $sort_crit)?>">
                    <?php echo $this->__('Username')?>
                </a>
            </th>
            <th>
                <?php echo ($sort == 'email' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
                <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('email', $sort_crit)?>">
                    <?php echo $this->__('Email')?>
                </a>
            </th>
            <th>
                <?php echo ($sort == 'first_name' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
                <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('first_name', $sort_crit)?>">
                    <?php echo $this->__('First Name')?>
                </a>
            </th>
            <th>
                <?php echo ($sort == 'last_name' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
                <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('last_name', $sort_crit)?>">
                    <?php echo $this->__('Last Name')?>
                </a>
            </th>
            <th><?php echo $this->__('Status')?></th>
            <th>
                <?php echo ($sort == 'created_at' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
                <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('created_at', $sort_crit)?>">
                    <?php echo $this->__('Created at')?>
                </a>
            </th>
            <th>
                <?php echo ($sort == 'last_login' ? '<span class="'.($sort_crit == 'asc' ? 'sortAsc':'sortDesc' ).'"></span>':'')?>
                <a href="<?php echo MVC_ACTION_URL.'?'.$GF->GFHref(true, true, false). $GF->sortParams('last_login', $sort_crit)?>">
                    <?php echo $this->__('Last login')?>
                </a>
            </th>
            <th><?php echo $this->__('Actions')?></th>
        </tr>
    <?php foreach ($oUserCol as $user):?>
        <tr>
            <td><?php echo $user->getUserId()?></td>
            <td><?php echo $user->getUsername()?></td>
            <td><?php echo $user->getEmail()?></td>
            <td><?php echo $user->getFirstName()?></td>
            <td><?php echo $user->getLastName()?></td>
            <td><?php echo $user->getStatus()?></td>
            <td><?php echo $user->getCreatedAt()?></td>
            <td><?php echo $user->getLastLogin()?></td>
            <td>
                <a 
                    href="#" 
                    class="ico edit js-user-list-edit"
                    data-user-id="<?php echo $user->getUserId()?>"
                    data-user-username="<?php echo $user->getUsername()?>"
                    data-user-email="<?php echo $user->getEmail()?>"
                    data-user-status="<?php echo $user->getStatus()?>"
                >
                    <?php echo $this->__('Edit')?>
                </a>
            </td>
        </tr>
    <?php endforeach;?>
    </table>
    
    <div class="pagging">
        <?php echo $oPagination->getHtml()?>
    </div>
    
    </div> <!-- End table -->
    </div> <!-- End box -->
<?php endif;?>
    
</div>
<!-- End Content -->
    
<div class="cl">&nbsp;</div>

</div>
<!-- Main -->

<!-- Dialog for editing users -->
<div id="user-edit-dialog" class="hidden" title="<?php echo $this->__('Edit user')?>">
    <table cellspacing="5" cellpadding="5">
      <tr>
        <td>Username: </td>
        <td><span class="dialog-data-span" id="dialog-username"></span></td>
      </tr>
      <tr>
        <td>Email: </td>
        <td><span class="dialog-data-span" id="dialog-email"></span></td>
      </tr>
      <tr>
        <td>Status: </td>
        <td><span class="dialog-data-span" id="dialog-status"></span></td>
      </tr>
      <tr>
        <td><?php echo $this->__('Change status to')?></td>
        <td>
          <select id="dialog-new-status">
            <option value="<?php echo User::STATUS_ACTIVE?>"><?php echo $this->__('Active')?></option>
            <option value="<?php echo User::STATUS_BANNED?>"><?php echo $this->__('Banned')?></option>
            <option value="<?php echo User::STATUS_NEW?>"><?php echo $this->__('New')?></option>
          </select>
        </td>
      </tr>
    </table>
    <p class="dialog-actions" data-dialog="user-edit-dialog">
        <button id="dialog-save" class="ui-button ui-widget ui-corner-all"><?php echo $this->__('Save')?></button>
        <button class="ui-button ui-widget ui-corner-all js-dialog-cancel"><?php echo $this->__('Cancel')?></button>
    </p>
    <p id="dialog-ajax-spinner"></p>
    <p id="dialog-ajax-error" class="dialog-ajax-error"></p>
</div>
<!-- End dialog  -->