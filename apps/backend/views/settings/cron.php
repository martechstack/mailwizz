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
 * @since 1.0
 */

/** @var Controller $controller */
$controller = controller();

/**
 * This hook gives a chance to prepend content or to replace the default view content with a custom content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->getData()}
 * In case the content is replaced, make sure to set {@CAttributeCollection $collection->add('renderContent', false)}
 * in order to stop rendering the default content.
 * @since 1.3.3.1
 */
hooks()->doAction('before_view_file_content', $viewCollection = new CAttributeCollection([
    'controller'    => $controller,
    'renderContent' => true,
]));

// and render if allowed
if (!empty($viewCollection) && $viewCollection->itemAt('renderContent')) {
    /**
     * This hook gives a chance to prepend content before the active form or to replace the default active form entirely.
     * Please note that from inside the action callback you can access all the controller view variables
     * via {@CAttributeCollection $collection->controller->getData()}
     * In case the form is replaced, make sure to set {@CAttributeCollection $collection->add('renderForm', false)}
     * in order to stop rendering the default content.
     * @since 1.3.3.1
     */
    hooks()->doAction('before_active_form', $collection = new CAttributeCollection([
        'controller'    => $controller,
        'renderForm'    => true,
    ]));

    // and render if allowed
    if (!empty($collection) && $collection->itemAt('renderForm')) {
        $form = $controller->beginWidget('CActiveForm');
        $controller->renderPartial('_delivery', compact('form'));
        $controller->renderPartial('_bounce_servers', compact('form'));
        $controller->renderPartial('_feedback_loop_servers', compact('form'));
        $controller->renderPartial('_email_box_monitors', compact('form'));
        $controller->renderPartial('_logs', compact('form'));
        $controller->renderPartial('_subscribers', compact('form'));
        $controller->renderPartial('_responders', compact('form'));
        $controller->renderPartial('_transactional_emails', compact('form'));
        $controller->renderPartial('_delete_logs', compact('form')); ?>
        <div class="box box-primary borderless">
            <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary btn-flat"><?php echo IconHelper::make('save') . t('app', 'Save changes'); ?></button>
                </div>
                <div class="clearfix"><!-- --></div>
            </div>
        </div>
        <?php
        $controller->endWidget();
    }
    /**
     * This hook gives a chance to append content after the active form.
     * Please note that from inside the action callback you can access all the controller view variables
     * via {@CAttributeCollection $collection->controller->getData()}
     * @since 1.3.3.1
     */
    hooks()->doAction('after_active_form', new CAttributeCollection([
        'controller'      => $controller,
        'renderedForm'    => $collection->itemAt('renderForm'),
    ]));
}
/**
 * This hook gives a chance to append content after the view file default content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->getData()}
 * @since 1.3.3.1
 */
hooks()->doAction('after_view_file_content', new CAttributeCollection([
    'controller'        => $controller,
    'renderedContent'   => $viewCollection->itemAt('renderContent'),
]));
