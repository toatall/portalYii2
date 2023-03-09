<?php
namespace app\helpers;

class StringHelper 
{

    public static function manyReplace(string $str, array $subs)
    {
        foreach($subs as $find=>$replace) {
            $str = str_replace($find, $replace, $str);
        }
        return $str;
    }
}