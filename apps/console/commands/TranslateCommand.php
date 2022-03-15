<?php declare(strict_types=1);
defined('MW_PATH') or exit('No direct script access allowed');

/**
 * TranslateCommand
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.6.6
 *
 */

class TranslateCommand extends ConsoleCommand
{
    /**
     * @var int
     */
    public $verbose = 1;

    /**
     * @return int
     */
    public function actionIndex()
    {
        hooks()->doAction('console_command_translate_before_process', $this);

        $result = $this->process();

        hooks()->doAction('console_command_translate__after_process', $this);

        return $result;
    }

    /**
     * @param string $fileName
     * @return array
     */
    public function extractMessages(string $fileName): array
    {
        $messages = [];
        $subject  = (string)file_get_contents($fileName);
        $n = preg_match_all('/\bt\s*\(\s*(\'[\w.\/]*?(?<!\.)\'|"[\w.]*?(?<!\.)")\s*,\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s', $subject, $matches, PREG_SET_ORDER);
        for ($i = 0; $i < $n; ++$i) {
            if (($pos = strpos($matches[$i][1], '.')) !== false) {
                $category = substr($matches[$i][1], $pos+1, -1);
            } else {
                $category = substr($matches[$i][1], 1, -1);
            }
            $message = trim($matches[$i][2]);

            // make sure we remove the single/double quotes from start/end of string
            $message = trim($message);
            $message = substr($message, 1);
            $message = substr($message, 0, -1);

            $messages[$category][] = $message;
        }
        return $messages;
    }

    /**
     * @return int
     */
    protected function process()
    {
        $attributes   = [];
        $languageName = $this->prompt('Please provide the language name(i.e: English) : ');
        if (empty($languageName)) {
            $this->stdout('Please provide a valid language name!', false);
            return 0;
        }
        $attributes['name'] = $languageName;

        $languageCode = $this->prompt('Please enter the 2 letter language code(i.e: en) : ');
        if (!preg_match('/[a-z]{2}/', $languageCode)) {
            $this->stdout('Please provide a valid 2 letter language code!', false);
            return 0;
        }
        $attributes['language_code'] = $languageCode;

        $regionCode = $this->prompt('Please enter the 2 letter region code(i.e: us). This is optional, leave empty if you are not sure. : ');
        if (!empty($regionCode) && !preg_match('/[a-z]{2}/', $languageCode)) {
            $this->stdout('Please provide a valid 2 letter region code!', false);
            return 0;
        }
        $attributes['region_code'] = $regionCode;

        $language = Language::model()->findByAttributes($attributes);
        if (empty($language)) {
            $language = new Language();
            $language->setAttributes($attributes);
            if (!$language->save()) {
                $this->stdout($language->shortErrors->getAllAsString("\n"), false);
                return 0;
            }
        }

        $directories = [
            Yii::getPathOfAlias('api'),
            Yii::getPathOfAlias('backend'),
            Yii::getPathOfAlias('common'),
            Yii::getPathOfAlias('customer'),
            Yii::getPathOfAlias('extensions'),
            Yii::getPathOfAlias('frontend'),
        ];

        $stub = (string)file_get_contents(Yii::getPathOfAlias('common.extensions.translate.common.stub') . '.php');
        $messagesPath = Yii::getPathOfAlias('common.messages') . '/' . $languageCode;
        if (!empty($regionCode)) {
            $messagesPath .= '_' . $regionCode;
        }

        if ((!file_exists($messagesPath) || !is_dir($messagesPath)) && !mkdir($messagesPath, 0777, true)) {
            $this->stdout(sprintf('Please make sure the folder "%s" is writable!', dirname($messagesPath)), false);
            return 0;
        }

        $input = $this->confirm(sprintf('Do you agree to create the translation messages in: %s ?', $messagesPath), true);
        if (!$input) {
            return 0;
        }

        $finder = (new Symfony\Component\Finder\Finder())
            ->files()
            ->name('*.php')
            ->in($directories);

        foreach ($finder as $file) {
            $this->stdout(sprintf('Processing: "%s"', $file->getRealPath()));
            $messages = $this->extractMessages((string)$file->getRealPath());
            foreach ($messages as $category => $_messages) {
                $data = [];
                if (is_file($categoryFile = $messagesPath . '/' . $category . '.php')) {
                    $data = require $categoryFile;
                }
                foreach ($_messages as $value) {
                    if (!empty($data[$value])) {
                        continue;
                    }
                    $data[$value] = $value;
                }
                $newStub = str_replace('[[category]]', $category, $stub);
                $newStub .= 'return ' . var_export($data, true) . ';' . "\n";
                $newStub = str_replace("\\\\\\'", "\\'", $newStub);
                file_put_contents($categoryFile, $newStub);
            }
        }

        return 0;
    }
}
