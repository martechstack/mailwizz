<?php declare(strict_types=1);
defined('MW_PATH') or exit('No direct script access allowed');

/**
 * HourlyCommand
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.7.5
 */

class HourlyCommand extends ConsoleCommand
{
    /**
     * @return int
     */
    public function actionIndex()
    {
        // set the lock name
        $lockName = sha1(__METHOD__);

        if (!mutex()->acquire($lockName, 5)) {
            return 0;
        }

        $result = 0;

        try {
            hooks()->doAction('console_command_hourly_before_process', $this);

            $result = $this->process();

            hooks()->doAction('console_command_hourly_after_process', $this);
        } catch (Exception $e) {
            $this->stdout(__LINE__ . ': ' . $e->getMessage());
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
        }

        mutex()->release($lockName);

        return $result;
    }

    /**
     * @return int
     * @throws CDbException
     */
    public function process()
    {
        $this
            ->resetProcessingCampaigns()
            ->resetBounceServers()
            ->handleCampaignsMaxAllowedBounceAndComplaintRates()
            ->updateListsCounters()
            ->updateCustomersQuota()
            ->handleCampaignsResendGiveups();

        return 0;
    }

    /**
     * @return $this
     */
    public function updateListsCounters()
    {
        $limit  = 50;
        $offset = 0;

        while (true) {
            $criteria = new CDbCriteria();
            $criteria->compare('status', Lists::STATUS_ACTIVE);
            $criteria->limit  = $limit;
            $criteria->offset = $offset;

            $lists = Lists::model()->findAll($criteria);
            if (empty($lists)) {
                break;
            }
            $offset = $offset + $limit;

            foreach ($lists as $list) {
                $this->stdout('Processing list uid: ' . $list->list_uid);
                try {
                    $list->flushSubscribersCountCache(-1, true);
                } catch (Exception $e) {
                    $this->stdout('Processing list uid: ' . $list->list_uid . ' failed with: ' . $e->getMessage());
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     * @throws CDbException
     */
    public function handleCampaignsResendGiveups()
    {
        /** @var CampaignResendGiveupQueue[] $queue */
        $queue = CampaignResendGiveupQueue::model()->findAll();

        foreach ($queue as $q) {
            if (empty($q->campaign)) {
                continue;
            }

            $this->stdout('Processing campaign uid: ' . $q->campaign->campaign_uid);

            try {

                /** @var Campaign $campaign */
                $campaign = $q->campaign;
                $campaign->resetSendingGiveups();
                $campaign->updateSendingGiveupCount(0);
                $campaign->updateSendingGiveupCounter(0);

                $campaign->saveStatus(Campaign::STATUS_SENDING);
            } catch (Exception $e) {
                $this->stdout('Processing campaign uid: ' . $q->campaign->campaign_uid . ' failed with: ' . $e->getMessage());
            }

            $q->delete();
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function resetProcessingCampaigns()
    {
        try {
            db()->createCommand('UPDATE `{{campaign}}` SET `status` = "sending", last_updated = NOW() WHERE status = "processing" AND last_updated < DATE_SUB(NOW(), INTERVAL 7 HOUR)')->execute();
        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function resetBounceServers()
    {
        try {
            db()->createCommand('UPDATE `{{bounce_server}}` SET `status` = "active", last_updated = NOW() WHERE status = "cron-running" AND last_updated < DATE_SUB(NOW(), INTERVAL 7 HOUR)')->execute();
        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
        }
        return $this;
    }

    /**
     * @since 1.6.1
     * @return $this
     */
    protected function handleCampaignsMaxAllowedBounceAndComplaintRates()
    {
        /** @var OptionCronDelivery $cronDelivery */
        $cronDelivery = container()->get(OptionCronDelivery::class);

        try {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('status', [Campaign::STATUS_SENDING]);
            $campaigns = Campaign::model()->findAll($criteria);

            foreach ($campaigns as $campaign) {
                $customer         = $campaign->customer;
                $maxBounceRate    = (float)$customer->getGroupOption('campaigns.max_bounce_rate', $cronDelivery->getMaxBounceRate());
                $maxComplaintRate = (float)$customer->getGroupOption('campaigns.max_complaint_rate', $cronDelivery->getMaxComplaintRate());

                if ($maxBounceRate > -1) {
                    $bouncesRate = $campaign->getStats()->getBouncesRate() - $campaign->getStats()->getInternalBouncesRate();
                    if ((float)$bouncesRate > (float)$maxBounceRate) {
                        $campaign->block('Campaign bounce rate is higher than allowed!');
                        continue;
                    }
                }

                if ($maxComplaintRate > -1 && (float)$campaign->getStats()->getComplaintsRate() > (float)$maxComplaintRate) {
                    $campaign->block('Campaign complaint rate is higher than allowed!');
                    continue;
                }
            }
        } catch (Exception $e) {
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function updateCustomersQuota()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('status', Customer::STATUS_ACTIVE);

        /** @var Customer[] $customers */
        $customers = Customer::model()->findAll($criteria);

        foreach ($customers as $customer) {
            try {
                $customer->getIsOverQuota();
            } catch (Exception $e) {
                $this->stdout(__LINE__ . ': ' . $e->getMessage());
                Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            }
        }

        return $this;
    }
}
