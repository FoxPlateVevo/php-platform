<?php

class folder{
    public static function getComponents($path){
        return array_diff(scandir($path), [".", ".."]);
    }
    
    public static function create($path){
        return mkdir($path, 0777, true);
    }
}

