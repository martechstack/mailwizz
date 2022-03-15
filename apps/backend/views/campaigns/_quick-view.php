<?php declare(strict_types=1);
defined('MW_PATH') or exit('No direct script access allowed');

/**
 * This file is part of the MailWizz EMA application.
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.17
 */

/** @var Controller $controller */
$controller = controller();

/** @var Campaign $campaign */
$campaign = $controller->getData('campaign');

?>
<div class="">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="list-wrapper">
            <ul class="custom-list">
                <li><span class="cl-span"><?php echo t('campaigns', 'Type'); ?></span><span class="cl-span"><?php echo ucfirst(t('campaigns', $campaign->type)); ?></span></li>
                <li><span class="cl-span"><?php echo t('campaigns', 'Status'); ?></span><span class="cl-span"><?php echo $campaign->getStatusName(); ?></span></li>
                <li><span class="cl-span"><?php echo t('campaigns', 'Name'); ?></span><span class="cl-span"><?php echo $campaign->name; ?></span></li>
                <li><span class="cl-span"><?php echo t('campaigns', 'List/Segment'); ?></span><span class="cl-span"><?php echo $campaign->getListSegmentName(); ?></span></li>
                <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('subject'); ?></span><span class="cl-span"><?php echo $campaign->subject; ?></span></li>

                <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('from_name'); ?></span><span class="cl-span"><?php echo $campaign->from_name; ?></span></li>
                <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('from_email'); ?></span><span class="cl-span"><?php echo $campaign->from_email; ?></span></li>
                <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('reply_to'); ?></span><span class="cl-span"><?php echo $campaign->reply_to; ?></span></li>
                <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('to_name'); ?></span><span class="cl-span"><?php echo $campaign->to_name; ?></span></li>

                <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('date_added'); ?></span><span class="cl-span"><?php echo $campaign->dateAdded; ?></span></li>
                <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('send_at'); ?></span><span class="cl-span"><?php echo $campaign->getSendAt(); ?></span></li>

				<?php if ($campaign->getIsRegular()) { ?>
                    <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('lastOpen'); ?></span><span class="cl-span"><?php echo $campaign->getLastOpen(); ?></span></li>
                    <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('started_at'); ?></span><span class="cl-span"><?php echo $campaign->getStartedAt() ? $campaign->getStartedAt() : $campaign->getSendAt(); ?></span></li>
                    <li><span class="cl-span"><?php echo $campaign->getAttributeLabel('finished_at'); ?></span><span class="cl-span"><?php echo $campaign->getFinishedAt(); ?></span></li>
				<?php } ?>
				<?php if ($campaign->getIsAutoresponder()) { ?>
                    <li><span class="cl-span"><?php echo t('campaigns', 'Autoresponder event'); ?></span><span class="cl-span"><?php echo t('campaigns', $campaign->option->autoresponder_event); ?></span></li>
                    <li><span class="cl-span"><?php echo t('campaigns', 'Autoresponder time unit'); ?></span><span class="cl-span"><?php echo ucfirst(t('app', $campaign->option->autoresponder_time_unit)); ?></span></li>
                    <li><span class="cl-span"><?php echo t('campaigns', 'Autoresponder time value'); ?></span><span class="cl-span"><?php echo $campaign->option->autoresponder_time_value; ?></span></li>
					<?php if ($arTimeMinHourMinute = $campaign->option->getAutoresponderTimeMinHourMinute()) { ?>
                        <li><span class="cl-span"><?php echo t('campaigns', 'Send only at/after this time'); ?></span><span class="cl-span"><?php echo $arTimeMinHourMinute; ?> (UTC 00:00)</span></li>
					<?php } ?>
                    <li><span class="cl-span"><?php echo t('campaigns', 'Include imported subscribers'); ?></span><span class="cl-span"><?php echo ucfirst(t('app', $campaign->option->autoresponder_include_imported)); ?></span></li>
                    <li><span class="cl-span"><?php echo t('campaigns', 'Include current subscribers'); ?></span><span class="cl-span"><?php echo ucfirst(t('app', $campaign->option->autoresponder_include_current)); ?></span></li>
				<?php } ?>
            </ul>
        </div>
    </div>
</div>