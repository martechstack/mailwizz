<?php declare(strict_types=1);
defined('MW_PATH') or exit('No direct script access allowed');

/**
 * CampaignOptionShareReports
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.7.3
 */

/**
 * @property string $share_reports_mask_email_addresses
 */
class CampaignOptionShareReports extends CampaignOption
{
    /**
     * @var string
     */
    public $share_reports_email;

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{campaign_option}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['share_reports_enabled, share_reports_password, share_reports_mask_email_addresses', 'required'],
            ['share_reports_email', 'required', 'on' => 'send-email'],
            ['share_reports_email', 'email', 'validateIDN' => true],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'shareUrl'              => t('campaigns', 'Share url'),
            'share_reports_email'   => t('campaigns', 'Email'),
        ];

        return CMap::mergeArray($labels, parent::attributeLabels());
    }

    /**
     * @return array
     */
    public function attributePlaceholders()
    {
        $placeholders = [
            'share_reports_email' => 'email@domain.com',
        ];
        return CMap::mergeArray($placeholders, parent::attributePlaceholders());
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CampaignOption the static model class
     */
    public static function model($className=__CLASS__)
    {
        /** @var CampaignOption $model */
        $model = parent::model($className);

        return $model;
    }

    /**
     * @return string
     */
    public function getShareUrl(): string
    {
        return apps()->getAppUrl('frontend', 'campaigns/' . $this->campaign->campaign_uid . '/overview', true);
    }

    /**
     * @return void
     */
    protected function afterConstruct()
    {
        $this->share_reports_password = StringHelper::random(12);
        parent::afterConstruct();
    }

    /**
     * @return void
     */
    protected function afterFind()
    {
        if (empty($this->share_reports_password)) {
            $this->share_reports_password = StringHelper::random(12);
        }
        parent::afterFind();
    }
}
