<?php
 
if (!function_exists('getCurrentDomain')) {
    function getCurrentDomain()
    {
        return request()->getHost();
    }
}