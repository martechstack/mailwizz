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
 * @since 1.3.4.4
 */

/** @var Controller $controller */
$controller = controller();

/** @var string $pageHeading */
$pageHeading = (string)$controller->getData('pageHeading');

/** @var PricePlanPromoCode $promoCode */
$promoCode = $controller->getData('promoCode');

/**
 * This hook gives a chance to prepend content or to replace the default view content with a custom content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->getData()}
 * In case the content is replaced, make sure to set {@CAttributeCollection $collection->add('renderContent', false)}
 * in order to stop rendering the default content.
 * @since 1.3.3.1
 */
hooks()->doAction('views_before_content', $viewCollection = new CAttributeCollection([
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
    hooks()->doAction('views_before_form', $collection = new CAttributeCollection([
        'controller'    => $controller,
        'renderForm'    => true,
    ]));

    // and render if allowed
    if (!empty($collection) && $collection->itemAt('renderForm')) {
        /** @var CActiveForm $form */
        $form = $controller->beginWidget('CActiveForm'); ?>
        <div class="box box-primary borderless">
            <div class="box-header">
        		<div class="pull-left">
                    <?php BoxHeaderContent::make(BoxHeaderContent::LEFT)
                        ->add('<h3 class="box-title">' . IconHelper::make('fa-code') . $pageHeading . '</h3>')
                        ->render(); ?>
                </div>
        		<div class="pull-right">
                    <?php BoxHeaderContent::make(BoxHeaderContent::RIGHT)
                        ->addIf(HtmlHelper::accessLink(IconHelper::make('create') . t('app', 'Create new'), ['promo_codes/create'], ['class' => 'btn btn-primary btn-flat', 'title' => t('app', 'Create new')]), !$promoCode->getIsNewRecord())
                        ->add(HtmlHelper::accessLink(IconHelper::make('cancel') . t('app', 'Cancel'), ['promo_codes/index'], ['class' => 'btn btn-primary btn-flat', 'title' => t('app', 'Cancel')]))
                        ->render(); ?>
        		</div>
        	</div>
            <div class="box-body">
                <?php
                /**
                 * This hook gives a chance to prepend content before the active form fields.
                 * Please note that from inside the action callback you can access all the controller view variables
                 * via {@CAttributeCollection $collection->controller->getData()}
                 * @since 1.3.3.1
                 */
                hooks()->doAction('views_before_form_fields', new CAttributeCollection([
                    'controller'    => $controller,
                    'form'          => $form,
                ])); ?>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?php echo $form->labelEx($promoCode, 'code'); ?>
                            <?php echo $form->textField($promoCode, 'code', $promoCode->fieldDecorator->getHtmlOptions('code')); ?>
                            <?php echo $form->error($promoCode, 'code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?php echo $form->labelEx($promoCode, 'type'); ?>
                            <?php echo $form->dropDownList($promoCode, 'type', $promoCode->getTypesList(), $promoCode->fieldDecorator->getHtmlOptions('type')); ?>
                            <?php echo $form->error($promoCode, 'type'); ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?php echo $form->labelEx($promoCode, 'discount'); ?>
                            <?php echo $form->textField($promoCode, 'discount', $promoCode->fieldDecorator->getHtmlOptions('discount')); ?>
                            <?php echo $form->error($promoCode, 'discount'); ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?php echo $form->labelEx($promoCode, 'total_amount'); ?>
                            <?php echo $form->textField($promoCode, 'total_amount', $promoCode->fieldDecorator->getHtmlOptions('total_amount')); ?>
                            <?php echo $form->error($promoCode, 'total_amount'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?php echo $form->labelEx($promoCode, 'total_usage'); ?>
                            <?php echo $form->textField($promoCode, 'total_usage', $promoCode->fieldDecorator->getHtmlOptions('total_usage')); ?>
                            <?php echo $form->error($promoCode, 'total_usage'); ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?php echo $form->labelEx($promoCode, 'customer_usage'); ?>
                            <?php echo $form->textField($promoCode, 'customer_usage', $promoCode->fieldDecorator->getHtmlOptions('customer_usage')); ?>
                            <?php echo $form->error($promoCode, 'customer_usage'); ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?php echo $form->labelEx($promoCode, 'date_start'); ?>
                            <?php
                            $controller->widget('zii.widgets.jui.CJuiDatePicker', [
                                'model'     => $promoCode,
                                'attribute' => 'date_start',
                                'language'  => $promoCode->getDatePickerLanguage(),
                                'cssFile'   => null,
                                'options'   => [
                                    'showAnim'      => 'fold',
                                    'dateFormat'    => $promoCode->getDatePickerFormat(),
                                ],
                                'htmlOptions'=>$promoCode->fieldDecorator->getHtmlOptions('date_start'),
                            ]); ?>
                            <?php echo $form->error($promoCode, 'date_start'); ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?php echo $form->labelEx($promoCode, 'date_end'); ?>
                            <?php
                            $controller->widget('zii.widgets.jui.CJuiDatePicker', [
                                'model'     => $promoCode,
                                'attribute' => 'date_end',
                                'language'  => $promoCode->getDatePickerLanguage(),
                                'cssFile'   => null,
                                'options'   => [
                                    'showAnim'      => 'fold',
                                    'dateFormat'    => $promoCode->getDatePickerFormat(),
                                ],
                                'htmlOptions'=>$promoCode->fieldDecorator->getHtmlOptions('date_end'),
                            ]); ?>
                            <?php echo $form->error($promoCode, 'date_end'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <?php echo $form->labelEx($promoCode, 'status'); ?>
                        <?php echo $form->dropDownList($promoCode, 'status', $promoCode->getStatusesList(), $promoCode->fieldDecorator->getHtmlOptions('status')); ?>
                        <?php echo $form->error($promoCode, 'status'); ?>
                    </div>
                </div>
                <?php
                /**
                 * This hook gives a chance to append content after the active form fields.
                 * Please note that from inside the action callback you can access all the controller view variables
                 * via {@CAttributeCollection $collection->controller->getData()}
                 * @since 1.3.3.1
                 */
                hooks()->doAction('views_after_form_fields', new CAttributeCollection([
                    'controller'    => $controller,
                    'form'          => $form,
                ])); ?>    
                <div class="clearfix"><!-- --></div>
            </div>
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
    hooks()->doAction('views_after_form', new CAttributeCollection([
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
hooks()->doAction('views_after_content', new CAttributeCollection([
    'controller'        => $controller,
    'renderedContent'   => $viewCollection->itemAt('renderContent'),
]));
