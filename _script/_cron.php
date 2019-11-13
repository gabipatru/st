<?php

namespace Cron;

class Cron extends AbstractCron
{
    public function run()
    {
        $collectionCrons = $this->getCronsToRun();

        if (count($collectionCrons) == 0) {
            $this->displayMsg('No crons to run');
        }

        foreach ($collectionCrons as $cron) {
            $this->runCron($cron);

            // calculate next runtime
            $nextRuntime = $this->calculateNextRuntime($cron);

            $this->updateCron($cron, $nextRuntime);
        }
    }

    /**
     * Fetch the crons which have to be run from the DB
     *
     * @return \Collection
     */
    protected function getCronsToRun(): \Collection
    {
        $oCron = new \Cron();
        return $oCron->getCronsToRun();
    }

    /**
     * Run a specific CRON
     *
     * @param \SetterGetter $cron - the cron item which has to be run
     */
    protected function runCron(\SetterGetter $cron)
    {
        $debugParam = ($this->getDebug() ? 'debug' : 'no_debug');
        $cronName = $cron->getScript();
        $cronId = $cron->getCronId();

        $this->displayMsg('Running cron ' . $cronName);

        shell_exec("php " . SCRIPT_DIR . "/script.php $debugParam $cronName $cronId > /dev/null 2>&1 &");
    }

    /**
     * Get the next runtime of a cron
     *
     * @param \SetterGetter $cron - the cron item for which the next runtime is being calculated
     *
     * @return string
     */
    protected function calculateNextRuntime(\SetterGetter $cron): string
    {
        return date('Y-m-d H:i:s', strtotime("+{$cron->getInterval()} minutes"));
    }

    /**
     * Update the cron after running it
     *
     * @param \SetterGetter $cron - the cron item which has to be updated
     * @param string $nextRuntime - the time of the next run for the cron
     */
    protected function updateCron(\SetterGetter $cron, string $nextRuntime)
    {
        $oCron = new \Cron();

        // update the cron
        $oItem = new \SetterGetter();
        $oItem->setLastRuntime($cron->getNextRuntime());
        $oItem->setNextRuntime($nextRuntime);

        $r = $oCron->Edit($cron->getCronId(), $oItem);
        if (! $r) {
            $this->displayMsg("Failed to update cron with id $cronId");
        }
    }
}
