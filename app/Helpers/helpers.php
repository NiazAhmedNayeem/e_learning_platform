<?php

use Illuminate\Support\Str;

if (!function_exists('limitText')) {
    function limitText($text, $limit = 50, $end = '...')
    {
        return Str::limit($text, $limit, $end);
    }
}

if (!function_exists('getYoutubeId')) {
    function getYoutubeId($url) {
        preg_match('/(youtu\.be\/|v=)([^&]+)/', $url, $matches);
        return $matches[2] ?? null;
    }
}

