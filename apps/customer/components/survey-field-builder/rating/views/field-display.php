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
 * @since 1.7.8
 */

?>

<div class="form-group field-<?php echo $field->type->identifier; ?> wrap-<?php echo strtolower((string)$field->getTag()); ?>" style="display: <?php echo !empty($visible) ? 'block' : 'none'; ?>">
    <?php echo CHtml::activeLabelEx($model, 'value', ['for' => $field->getTag()]); ?>
    <?php echo CHtml::numberField($field->getTag(), $model->value, $model->getHtmlOptions('value', [
        'class'               => 'rating',
        'data-max'            => $field->max_stars,
        'data-icon-lib'       => $field->icon_lib,
        'data-active-icon'    => $field->active_icon,
        'data-inactive-icon'  => $field->inactive_icon,
        'data-clearable'      => $field->clearable_text,
        'data-clearable-icon' => $field->clearable_icon,
    ])); ?>
    <?php echo CHtml::error($model, 'value'); ?>
    <?php if (!empty($field->description)) { ?>
        <div class="field-description">
            <?php echo $field->description; ?>
        </div>
    <?php } ?>
</div>