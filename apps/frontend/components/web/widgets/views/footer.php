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
 * @since 1.9.15
 */

/** @var Controller $controller */
$controller = controller();

?>

<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <span class="copyright">©<?php echo date('Y'); ?> <?php echo t('app', 'All rights reserved.'); ?></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <ul class="links">
					<?php if ($page = Page::findBySlug('terms-and-conditions')) { ?>
                        <li><a href="<?php echo $page->getPermalink(); ?>" title="<?php echo $page->title; ?>"><?php echo $page->title; ?></a></li>
					<?php } ?>
					<?php if ($page = Page::findBySlug('privacy-policy')) { ?>
                        <li><a href="<?php echo $page->getPermalink(); ?>" title="<?php echo $page->title; ?>"><?php echo $page->title; ?></a></li>
					<?php } ?>
                    <li><a href="<?php echo createUrl('articles/index'); ?>" title="<?php echo t('app', 'Articles'); ?>"><?php echo t('app', 'Articles'); ?></a></li>
                    <li><a href="<?php echo createUrl('lists/block_address'); ?>" title="<?php echo t('app', 'Block my email'); ?>"><?php echo t('app', 'Block my email'); ?></a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <ul class="social">
					<?php foreach (['facebook', 'twitter', 'linkedin', 'instagram', 'youtube'] as $item) {
    if (!($url = options()->get('system.social_links.' . $item, ''))) {
        continue;
    } ?>
                        <li>
                            <a href="<?php echo $url; ?>" title="<?php echo ucfirst($item); ?>" target="_blank">
                                <i class="fa fa-<?php echo $item; ?>"></i>
                            </a>
                        </li>
						<?php
} ?>
                </ul>
            </div>
        </div>
    </div>
	<?php hooks()->doAction('layout_footer_html', $controller); ?>
</footer>