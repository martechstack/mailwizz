<?php declare(strict_types=1);
defined('MW_PATH') or exit('No direct script access allowed');

/**
 * SurveyFieldBuilderTypeRating
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.0
 */

/**
 * The followings are the available behaviors:
 * @property SurveyFieldBuilderTypeRatingCrud $_crud
 * @property SurveyFieldBuilderTypeRatingResponder $_responder
 */
class SurveyFieldBuilderTypeRating extends SurveyFieldBuilderType
{
    /**
     * @return void
     */
    public function run()
    {
        /** @var Controller $controller */
        $controller = app()->getController();

        if (empty($controller)) {
            return;
        }

        clientScript()->registerScriptFile(apps()->getAppUrl('frontend', 'assets/js/bootstrap-rating-input/bootstrap-rating-input.min.js', false, true));

        parent::run();
    }
}
