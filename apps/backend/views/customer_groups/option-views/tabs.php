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
 * @since 1.3.4
 */

/** @var Controller $controller */
$controller = controller();

/** @var CActiveForm $form */
$form = $controller->getData('form');

/** @var array $tabs */
$tabs = $controller->getData('tabs');

?>
<ul class="nav nav-tabs" style="border-bottom: 0px;">
    <?php foreach ($tabs as $tab) { ?>
        <li class="<?php echo $tab['id'] === 'common' ? 'active' : ''; ?>">
            <a href="#tab-<?php echo $tab['id']; ?>" data-toggle="tab"><?php echo $tab['label']; ?></a>
        </li>
    <?php } ?>
</ul>

<div class="tab-content">
    <?php foreach ($tabs as $tab) { ?>
        <div class="tab-pane <?php echo $tab['id'] === 'common' ? 'active' : ''; ?>" id="tab-<?php echo $tab['id']; ?>">
            <?php $controller->renderPartial($tab['view'], ['model' => $tab['model'], 'form' => $form]); ?>
        </div>
    <?php } ?>
</div>
