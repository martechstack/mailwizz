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
 * @since 1.3.4.8
 */

/** @var Controller $controller */
$controller = controller();

/** @var string $pageHeading */
$pageHeading = (string)$controller->getData('pageHeading');

/** @var CActiveForm $form */
$form = $controller->getData('form');

/** @var SendingDomain $domain */
$domain = $controller->getData('domain');

?>

<div class="box box-primary borderless">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?php echo IconHelper::make('glyphicon-user') . t('sending_domains', 'Customer'); ?></h3>
        </div>
        <div class="pull-right"></div>
        <div class="clearfix"><!-- --></div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <?php echo $form->labelEx($domain, 'customer_id'); ?>
                    <?php echo $form->hiddenField($domain, 'customer_id', $domain->fieldDecorator->getHtmlOptions('customer_id')); ?>
                    <?php
                    $controller->widget('zii.widgets.jui.CJuiAutoComplete', [
                        'name'          => 'customer',
                        'value'         => !empty($domain->customer) ? $domain->customer->getFullName() : null,
                        'source'        => createUrl('customers/autocomplete'),
                        'cssFile'       => false,
                        'options'       => [
                            'minLength' => '2',
                            'select'    => 'js:function(event, ui) {
                        $("#' . CHtml::activeId($domain, 'customer_id') . '").val(ui.item.customer_id);
                    }',
                            'search'    => 'js:function(event, ui) {
                        $("#' . CHtml::activeId($domain, 'customer_id') . '").val("");
                    }',
                            'change'    => 'js:function(event, ui) {
                        if (!ui.item) {
                            $("#' . CHtml::activeId($domain, 'customer_id') . '").val("");
                        }
                    }',
                        ],
                        'htmlOptions'   => $domain->fieldDecorator->getHtmlOptions('customer_id'),
                    ]);
                    ?>
                    <?php echo $form->error($domain, 'customer_id'); ?>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <?php echo $form->labelEx($domain, 'locked'); ?>
                    <?php echo $form->dropDownList($domain, 'locked', $domain->getYesNoOptions(), $domain->fieldDecorator->getHtmlOptions('locked')); ?>
                    <?php echo $form->error($domain, 'locked'); ?>
                </div>
            </div>
        </div>        
    </div>
</div>
