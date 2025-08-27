<?php

use Illuminate\Support\Str;

if (!function_exists('limitText')) {
    function limitText($text, $limit = 50, $end = '...')
    {
        return Str::limit($text, $limit, $end);
    }
}
