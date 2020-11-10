<?php

/**
 * Created by PhpStorm.
 * User: standopen
 * Date: 2019/8/15
 * Time: 上午11:30
 * Email: standopen@foxmail.com
 * Description: 关键词查找辅助类
 */
class Sensitive
{

    private static $instance;

    private static $sensiveArr = [];

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Sensitive();
        }
        return self::$instance;
    }

    /**
     * 添加关键词
     * array或,分隔
     * @param $words
     */
    public static function addSensitiveWords($words)
    {
        self::$sensiveArr = [];
        $wordsArr = is_array($words) ? $words : explode(',', $words);
        foreach ($wordsArr as $item) {
            $treeArr = &self::$sensiveArr;
            $strlen = mb_strlen($item, 'UTF-8');
            for ($i = 0; $i < $strlen; $i++) {
                $char = mb_substr($item, $i, 1, 'UTF-8');
                if (!isset($treeArr[$char])) {
                    $treeArr[$char] = false;
                }
                $treeArr = &$treeArr[$char];
            }
        }
    }

    /**
     * 只检查是否包含
     */
    public static function isExist($str)
    {
        $isFind = false;
        $length = mb_strlen($str, 'UTF-8');
        for ($i = 0; $i < $length; $i++) {
            $result = self::checkWordTree($str, $i, $length);
            if ($result > 0) {
                $isFind = true;
                break;
            }
        }
        return $isFind;
    }

    /**
     * 查找替换
     * @param $str
     * @param string $replace
     */
    public static function replaceWords($str, $replace = '*')
    {
        $length = mb_strlen($str, 'UTF-8');
        $findList = [];
        for ($i = 0; $i < $length; $i++) {
            $result = self::checkWordTree($str, $i, $length);
            if ($result > 0) {
                $word = mb_substr($str, $i, $result);
                $findList[$word] = str_repeat($replace, $result);
            }
        }
        return empty($findList) ?$str: strtr($str, $findList);
    }

    /**
     * 用来检查
     * @param $txt
     * @param $start
     * @param $length
     * @return int
     */
    public static function checkWordTree($txt, $start, $length)
    {
        $treeArr = &self::$sensiveArr;
        $result = 0;
        $flag = false;
        for ($i = $start; $i < $length; $i++) {
            $char = mb_substr($txt, $i, 1, 'UTF-8');
            if (!isset($treeArr[$char])) {
                break;
            }
            $result++;
            if ($treeArr[$char] !== false) {
                $treeArr = &$treeArr[$char];
            } else {
                $flag = true;
                break;
            }
        }

        return $flag ? $result : 0;
    }


}