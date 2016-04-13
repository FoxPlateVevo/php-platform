<?php

class file{
    public static function cleanName($fileName){
        $search = [" "];
        $fileNameModified = str_replace($search, "_", $fileName);
        
        return $fileNameModified;
    }
    
    public static function cleanPath($path){
        $search = ["//", "../", "./"];
        $pathModified = str_replace($search, "", $path);

        while($pathModified[0] === "/"){
            $array = str_split($pathModified, 1);
            array_shift($array);
            $pathModified = implode("", $array);
        }

        while($pathModified[strlen($pathModified) - 1] === "/"){
            $array = str_split($pathModified, 1);
            array_pop($array);
            $pathModified = implode("", $array);
        }

        return $pathModified;
    }
    
    public static function isImage($fileName){
        $IMAGE_EXTENSIONS = ["jpg", "png", "gif"];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        return in_array($fileExtension, $IMAGE_EXTENSIONS);
    }

    public static function isVideo($fileName){
        $VIDEO_EXTENSIONS = [
            "3g2", "3gp", "aaf", "asf", "avchd", "avi", "drc", "flv", "m2v",
            "m4p", "m4v", "mkv", "mng", "mov", "mp2", "mp4", "mpe", "mpeg",
            "mpg", "mpv", "mxf", "nsv", "ogg", "ogv", "qt", "rm", "rmvb", "roq",
            "svi", "vob", "webm", "wmv", "yuv"
        ];

        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        return in_array($fileExtension, $VIDEO_EXTENSIONS);
    }
}