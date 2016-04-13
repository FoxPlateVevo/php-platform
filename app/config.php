<?php
date_default_timezone_set('America/Lima');
error_reporting(E_ERROR);

class app_configs{
    private static $namespaces = [
        'administration',
        'course',
        'dashboard',
        'desktop',
        'login',
        'localization',
        'product',
        'project',
        'sale',
        'store',
        'upload',
        'user'
    ];
    
    public static function getNamespaces() {
        return self::$namespaces;
    }
}