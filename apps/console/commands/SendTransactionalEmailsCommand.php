<?php declare(strict_types=1);
defined('MW_PATH') or exit('No direct script access allowed');

/**
 * SendTransactionalEmailsCommand
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.4.5
 */

class SendTransactionalEmailsCommand extends ConsoleCommand
{
    /**
     * @return int
     */
    public function actionIndex()
    {
        $lockName = sha1(__METHOD__);

        if (!mutex()->acquire($lockName)) {
            return 1;
        }

        try {

            // added in 1.3.4.7
            hooks()->doAction('console_command_transactional_emails_before_process', $this);

            $this->process();

            // added in 1.3.4.7
            hooks()->doAction('console_command_transactional_emails_after_process', $this);
        } catch (Exception $e) {
            $this->stdout(__LINE__ . ': ' . $e->getMessage());
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
        }

        mutex()->release($lockName);
        return 0;
    }

    /**
     * @return $this
     * @throws CDbException
     * @throws CException
     */
    protected function process()
    {
        // 1.3.7.3
        $criteria = new CDbCriteria();
        $criteria->addCondition('t.status = "unsent" AND t.send_at < NOW() AND t.retries < t.max_retries');
        $criteria->order = 't.priority ASC, t.retries ASC';
        $criteria->limit = 500;

        // 1.3.7.3 - offer a chance to alter this criteria.
        $criteria = hooks()->applyFilters('console_send_transactional_emails_command_find_all_criteria', $criteria, $this);

        $emails = TransactionalEmail::model()->findAll($criteria);

        if (empty($emails)) {
            return $this;
        }

        foreach ($emails as $email) {
            $email->send();
        }

        db()->createCommand('UPDATE {{transactional_email}} SET `status` = "sent" WHERE `status` = "unsent" AND send_at < NOW() AND retries >= max_retries')->execute();
        db()->createCommand('DELETE FROM {{transactional_email}} WHERE `status` = "unsent" AND send_at < NOW() AND date_added < DATE_SUB(NOW(), INTERVAL 1 MONTH)')->execute();

        return $this;
    }
}
