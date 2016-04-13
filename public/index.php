<?php
//define constants in the enviroment
define('__PATH__', __DIR__ . '/../');

require_once __PATH__ . "/lib/vendor/autoload.php";
require_once __PATH__ . "/lib/utils.php";
require_once __PATH__ . "/app/config.php";

$klein = new \Klein\Klein();

/*
 * Validators
 */
$klein->service()->addValidator('true', function ($variable) {
    return filter_var($variable, FILTER_VALIDATE_BOOLEAN);
});

$klein->service()->addValidator('empty', function ($variable) {
    if(is_string($variable)){
        $variable = trim($variable);
    }
    
    return empty($variable);
});

$klein->service()->startSession();
$klein->service()->layout(__PATH__ . "/app/view/layouts/default.phtml");

/*
 * Verify session
 */
$klein->respond("*",function($request, $response, $service){
    $pathname = $request->pathname();
    $pieces = explode("/", $pathname);
    
    $controllerPath = $pieces[1];
    
    if($controllerPath !== "login"){
         $user = get_user_session();
    
        if(!$user){
            $response->redirect("/login")->send();
        }
    }
    
    switch($controllerPath){
        case "course":
        case "desktop":
            $service->layout(__PATH__ . "/app/view/layouts/default.desktop.phtml");
            break;
        case "login":
            $service->layout(__PATH__ . "/app/view/layouts/empty.phtml");
            break;
    }
});

/*
 * Principal site
 */
$klein->respond("GET", "/?", function ($request, $response, $service) {
    //header params
    $service->pageTitle = "My title Application";
    
    //content params
    $service->title = "Archivos en el proyecto";
    
    //render
    $service->render(__PATH__ . "/app/view/home/home.phtml");
});

/*
 * Set namespaces
 */
foreach(app_configs::getNamespaces() as $controller) {
    // Include all routes defined in a file under a given namespace
    $klein->with("/{$controller}", __PATH__ . "/app/controller/{$controller}.php");
}

/*
 * Using exact code behaviors via switch/case
 */
$klein->onHttpError(function ($code, $router) {
    //$router is a Klein object
    switch ($code) {
        case 404:
            $router->service()->layout(__PATH__ . "/app/view/layouts/empty.phtml");
            $router->service()->render(__PATH__ . "/public/404.html");
            break;
        case 405:
            $router->response()->body(
                'You can\'t do that!'
            );
            break;
        default:
            $router->response()->body(
                'Oh no, a bad error happened that caused a '. $code
            );
    }
});

$klein->onError(function ($klein, $errorMessage) {
    $klein->response()->json([
        "message" => $errorMessage
    ]);
});

$klein->dispatch();