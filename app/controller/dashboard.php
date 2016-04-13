<?php
$this->respond("GET", "/?", function ($request, $response, $service) {
    //header params
    $service->pageTitle = "Principal";
    
    //content params
    
    //render
    $service->render(__PATH__ . "/app/view/dashboard/main.phtml");
});