<?php
session_start();

class UploadService_Config_Constraints{
    private $maxSize;
    private $extensions;
    
    function __construct() {
        $this->maxSize = 0.5;
    }
    
    function getMaxSize() {
        return $this->maxSize;
    }

    function getExtensions() {
        return $this->extensions;
    }

    function setMaxSize($maxSize) {
        $this->maxSize = $maxSize;
    }

    function setExtensions(array $extensions) {
        $this->extensions = $extensions;
    }
}

class UploadService_Config{
    private $path;
    private $constraints;
    
    function getPath() {
        return $this->path;
    }

    function getConstraints() {
        return $this->constraints;
    }

    function setPath($path) {
        $this->path = $path;
    }

    function setConstraints(UploadService_Config_Constraints $constraints) {
        $this->constraints = $constraints;
    }
}

class UploadService{
    //private vars
    private $config;
    
    //resources
    public $file;
    public $token;
            
    function __construct() {
        $this->file     = new UploadService_File_Resource($this);
        $this->token    = new Token_Resource();
    }
    
    function getConfig() {
        return $this->config;
    }

    function setConfig(UploadService_Config $config) {
        $this->config = $config;
    }
}

class UploadService_File{
    private $name;
    private $size;
    
    function getName() {
        return $this->name;
    }

    function getSize() {
        return $this->size;
    }
    
    function setName($name) {
        $this->name = $name;
    }

    function setSize($size) {
        $this->size = $size;
    }
}

class UploadService_File_Resource{
    private $uploadService;
    
    function __construct(UploadService $uploadService) {
        $this->uploadService = $uploadService;
    }
    
    public function prepare(UploadService_File $file){
        $config = $this->uploadService->getConfig();
        
//        $config = new UploadService_Config();
        
        $configConstraints = $config->getConstraints();
        
//        $configConstraints = new UploadService_Config_Constraints();
        
        $response = [
            "success"   => false,
            "message"   => null
        ];
        
        $file->setName(strtolower($file->getName()));
        
        $fileNameExtension      = pathinfo($file->getName(), PATHINFO_EXTENSION);
        $maxSizeMegaBytesValue  = $configConstraints->getMaxSize() * 1024 * 1024;
        
        if(in_array($fileNameExtension, $configConstraints->getExtensions()) && $file->getSize() < $maxSizeMegaBytesValue){
            $response["success"] = true;
        }else if(!in_array($fileNameExtension, $configConstraints->getExtensions())){
            $extensionsString = implode(", ", $configConstraints->getExtensions());
            
            $response["message"] = "La extensión del archivo es {$fileNameExtension}, debe tener las siguientes extensiones {$extensionsString}";
        }else if(!($file->getSize() < $maxSizeMegaBytesValue)){
            $response["message"] = "El archivo supera los {$configConstraints->getMaxSize()} Megabytes";
        }
        
        return $response;
    }
    
    public function put(){
        $config = $this->uploadService->getConfig();
        
//        $config = new UploadService_Config();
        
        $response = [
            "success"   => false,
            "message"   => null,
            "file"      => [
                "name"      => null,
                "uri"       => null
            ]
        ];
        
        $path = file::cleanPath($config->getPath());
        
        //prepare file
        $file = $_FILES["file"];
        
        $fileError      = $file["error"];
        $fileName       = $file["name"];
        $fileSize       = $file["size"];
        $fileTempName   = $file["tmp_name"];
        $fileType       = $file["type"];
        
        $fileName = file::cleanName($fileName);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        
        //destinies
        $basePath   = __PATH__ . "/public";
        $publicPath  = "/resources/upload/{$path}.{$fileExtension}";
        $destiny    = "{$basePath}/{$publicPath}";
        
        if($fileError === UPLOAD_ERR_OK){
            set_time_limit(600);
            
            //destiny file
            $destinyParticles = explode("/", $destiny);
            array_pop($destinyParticles);
            
            $destinyFolder = implode("/", $destinyParticles);
            !file_exists($destinyFolder) && folder::create($destinyFolder);
            
            $response["success"] = move_uploaded_file($fileTempName, $destiny);
            
            if($response["success"]){
                $response["file"]["name"] = $fileName;
                
                if(file::isImage($fileName)){
                    $response["file"]["uri"] = get_domain() . "/{$publicPath}";
                }
            }else{
                $response["message"] = "Destiny directory {$destiny} don't exists or isn't writable";
            }
        }else{
            $response["message"] = "Happened a error whit code {$fileError}";
        }
        
        return $response;
    }
}

class Token_Resource{
    public function generate(){
        $length = 10;
        $tokenString = "";

        for($i = 0; $i < $length; $i++){
            $tokenString .= $this->generateChar();
        }
        
        return $tokenString;
    }
    
    private function generateChar(){
        if($this->randBool()){
            //range of ascii number code
            $asciiCode = rand(97,122);
        }else{
            if($this->randBool()){
                //range of ascii lower case
                $asciiCode = rand(48,57);
            }else{
                //range of ascii upper case
                $asciiCode = rand(65,90);
            }
        }
        
        return chr($asciiCode);
    }
    
    private function randBool(){
        return (bool) rand(0, 1);
    }
}