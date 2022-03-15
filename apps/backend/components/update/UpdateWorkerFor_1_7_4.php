<?php declare(strict_types=1);
defined('MW_PATH') or exit('No direct script access allowed');

/**
 * UpdateWorkerFor_1_7_4
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.7.4
 */

class UpdateWorkerFor_1_7_4 extends UpdateWorkerAbstract
{
    public function run()
    {
        // run the sql from file
        $this->runQueriesFromSqlFile('1.7.4');
    }
}
