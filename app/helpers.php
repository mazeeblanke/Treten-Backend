<?php

if (!function_exists('aurl'))
{
    function aurl ($path, $defaultUrl = null)
    {
        return rtrim($defaultUrl ?? config('app.url'), '/').'/'.ltrim($path, '/');
    }
}