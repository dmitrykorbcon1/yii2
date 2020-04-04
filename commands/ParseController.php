<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use UAParser\Parser;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Class ParseController
 * @package app\commands
 */
class ParseController extends Controller
{
    private $parser = null;

    /**
     * Подключение библиотеки
     * @return null|Parser
     * @throws \UAParser\Exception\FileNotFoundException
     */
    public function getParser()
    {
        if ($this->parser) {
            return $this->parser;
        }
        $this->parser = Parser::create();
        return $this->parser;
    }

    /**
     * Парсер
     * @param string $path
     * @return int
     * @throws \UAParser\Exception\FileNotFoundException
     * @throws \yii\db\Exception
     */
    public function actionIndex($path)
    {
        $i = 0;
        $arr = [];
        $file = @fopen($path, 'r');
        if ($file) {
            while ($i <= 2000) {//ставлю искуственное ограничение на кол-во записей
                $line = fgets($file);
                if (!$line) {
                    continue;
                }
                //парсим IP
                preg_match('/([0-9]{1,3}[\.]){3}[0-9]{1,3}/', $line, $match);
                $arr[$i]['ip'] = $match[0] ?? null;
                //парсим дату
                preg_match('/([0-9]{2}\/[a-zA-Z]{3}\/[0-9]{4}):(([0-9]{2}:){2}[0-9]{2})/', $line, $match);
                $arr[$i]['createdAt'] = strtotime(str_replace('/', '-', $match[1]) . $match[2]);
                //парсим URL
                preg_match('/"((http|https):\/\/[^\s]+)"/', $line, $match);
                $arr[$i]['url'] = $match[0] ?? null;
                //парсим OS (используем библиотеку)
                $arr[$i]['os'] =  self::getParser()->parse($line)->os->family;
                //парсим browser (используем библиотеку)
                $arr[$i]['browser'] =  self::getParser()->parse($line)->ua->family;
                //парсим архитектуру
                if (preg_match('/(x86_64|WOW64|Win64|x64)/', $line)) {
                    $arr[$i]['arch'] = 'x64';
                } elseif (preg_match('/i686/', $line)) {
                    $arr[$i]['arch'] = 'x86';
                } else {
                    $arr[$i]['arch'] = null;
                }
                $i++;
            }
        }
        if (!empty($arr)) {
            Yii::$app->db->createCommand()->batchInsert(
                'logs',
                ['ip', 'created_at','url','os','browser','arch'],
                $arr
            )->execute();
        }
        fclose($file);
        return ExitCode::OK;
    }
}
