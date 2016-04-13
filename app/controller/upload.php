<?php
$this->respond("POST", "/?", function ($request, $response, $service) {
    $token      = $request->param("token");
    $prepare    = $request->param("PREPARE_UPLOAD");
    
    $upload = unserialize($_SESSION["upload"][$token]);
//    $upload = new UploadService();
    
    $fileResource = $upload->file;
//    $fileResource = new UploadService_File_Resource();
    
    if($prepare){
        $name = $request->param("name");
        $size = $request->param("size");
        
        $file = new UploadService_File();
        $file->setName($name);
        $file->setSize($size);

        $successData = $fileResource->prepare($file);
    }else{
        $successData = $fileResource->put();
    }

    $response->json($successData);
});

$this->respond("GET", "/sample/?", function ($request, $response, $service) {
//    $token = create_upload_token("/products/courses/CU1/tema", 2, ["jpg", "png"]);
    
//    vd($token);
//    
//    unset($_SESSION["upload"]);
    
//    vd($_SESSION);
});