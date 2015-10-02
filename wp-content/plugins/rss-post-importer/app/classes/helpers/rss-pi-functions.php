<?php

/**
 * Fallback Functions
 *
 * @author mobilova UG (haftungsbeschränkt) <rsspostimporter@feedsapi.com>
 */

/*
 * array_intersect_key for PHP earlier than 5.1.0
 */
if (!function_exists('array_intersect_key'))
{
    function array_intersect_key ($isec, $arr2)
    {
        $argc = func_num_args();
 
        for ($i = 1; !empty($isec) && $i < $argc; $i++)
        {
             $arr = func_get_arg($i);
 
             foreach ($isec as $k => $v)
                 if (!isset($arr[$k]))
                     unset($isec[$k]);
        }
 
        return $isec;
    }
}

