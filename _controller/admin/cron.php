<?php

class controller_admin_cron extends ControllerAdminModel
{
    function _prehook() {
        parent::_prehook();

        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Cron'), href_admin('cron/list_items'));

        $this->View->assign('menu', 'cron');
    }

    ###############################################################################
    ## LIST ITEMS PAGE
    ###############################################################################
    function list_items()
    {
        $oCron = new Cron();

        $collectionCron = $oCron->Get();

        $this->View->assign('collectionCron', $collectionCron);

        $this->View->addSEOParams($this->__('List Cron Items :: Admin'), '', '');
    }

    ###############################################################################
    ## EDIT A SCRIPT VIA AJAX
    ###############################################################################
    function ajax_edit_cron()
    {
        $cronId         = $this->filterPOST('cron_id', 'int');
        $newStatus      = $this->filterPOST('new_status', 'string');
        $newInterval    = $this->filterPOST('new_interval', 'int');
        $sToken         = $this->filterPOST('token', 'string');

        try {
            if (!$cronId || !$newStatus || !$newInterval) {
                throw new Exception($this->__('Some parameters are missing'));
            }
            if (!$this->securityCheckToken($sToken)) {
                throw new Exception($this->__('The page delay was too long'));
            }

            $oItem = new SetterGetter();
            $oItem->setInterval($newInterval);
            $oItem->setStatus($newStatus);

            $oCron = new Cron();
            $r = $oCron->Edit($cronId, $oItem);
            if (! $r) {
                throw new Exception($this->__('Error while saving to the database'));
            }

            $this->JsonSuccess();
        } catch (Exception $e) {
            $this->JsonError($e->getMessage());
        }

        exit;
    }
}
