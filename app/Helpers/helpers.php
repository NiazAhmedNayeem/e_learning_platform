<?php

use App\Models\SiteSetting;
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

///fav icon
if (!function_exists('favicon_url')) {
    function favicon_url()
    {
        $icon = SiteSetting::where('key', 'favicon')->value('value');
        $default = "https://ui-avatars.com/api/?name=Site+Logo&size=160";
        if (empty($icon) || !file_exists(public_path('upload/site/' . $icon))) {
            return $default;
        }
        return asset('public/upload/site/' . $icon);
    }
}

///site logo
if (!function_exists('site_logo_url')) {
    function site_logo_url()
    {
        $logo = SiteSetting::where('key', 'site_logo')->value('value');
        $default = "https://ui-avatars.com/api/?name=Site+Logo&size=160";
        if (empty($logo) || !file_exists(public_path('upload/site/' . $logo))) {
            return $default;
        }
        return asset('public/upload/site/' . $logo);
    }
}

